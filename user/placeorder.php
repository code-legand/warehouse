<?php
    session_start();
    if(!(isset($_SESSION['username']))) {
        $_SESSION['message'] = 'You must be logged in to view this page';
        header('Location: login.php');
        return;
    }

    if (isset($_POST['storage_id'])){
        $_SESSION['storage_id'] = $_POST['storage_id'];
        header('Location: placeorder.php');
        return;
    }

    if (isset($_SESSION['storage_id'])){
        if (isset($_POST['order_confirm']) && $_POST['order_confirm'] == '1'){
            if (isset($_POST['quantity'])){
                $order_quantity = $_POST['quantity'];
                require_once 'connect.php';
                $query = "SELECT product_name, quantity, price FROM storage WHERE storage_id = :storage_id";
                $stmt = $pdo->prepare($query);
                $stmt->execute(array(':storage_id' => $_SESSION['storage_id']));
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $product_name = $row['product_name'];
                $query2 = "SELECT sum(quantity) as blocked_quantity FROM orders WHERE storage_id = :storage_id AND status = 'P'";
                $stmt2 = $pdo->prepare($query2);
                $stmt2->execute(array(':storage_id' => $_SESSION['storage_id']));
                $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
                $available_quantity = $row['quantity'] - $row2['blocked_quantity'];
                if($order_quantity > $available_quantity){
                    $_SESSION['message'] = 'Insufficient quantity';
                    header('Location: placeorder.php');
                    return;
                }
                else{
                    $query = "SELECT user_id FROM users WHERE user_name = :username";
                    $stmt = $pdo->prepare($query);
                    $stmt->execute(array(':username' => $_SESSION['username']));
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    $user_id = $row['user_id'];
                    $storage_id = $_SESSION['storage_id'];
                    $order_status = 'P';    //pending

                    date_default_timezone_set('Asia/Kolkata');
                    $now = new DateTime();
                    $order_date = $now->format('Y-m-d');
                    $order_time = $now->format('H:i:s');
                    
                    $query = "INSERT INTO orders (user_id, storage_id, product_name, quantity, date, time, status) VALUES (:user_id, :storage_id, :product_name, :quantity, :order_date, :order_time, :order_status)";
                    $stmt = $pdo->prepare($query);
                    $stmt->execute(array(
                        ':user_id' => $user_id,
                        ':storage_id' => $storage_id,
                        ':product_name' => $product_name,
                        ':quantity' => $order_quantity,
                        ':order_date' => $order_date,
                        ':order_time' => $order_time,
                        ':order_status' => $order_status
                    ));
                    $_SESSION['message'] = 'Order placed successfully';
                    unset($_SESSION['storage_id']);
                    header('Location: products.php');
                    return;
                }
            }
            else{
                $_SESSION['message'] = 'Please enter a quantity';
                header('Location: placeorder.php');
                return;
            }
        }

        require_once 'connect.php';
        $query = "SELECT product_name, quantity, price FROM storage WHERE storage_id = :storage_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(array(':storage_id' => $_SESSION['storage_id']));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $query2 = "SELECT sum(quantity) as blocked_quantity FROM orders WHERE storage_id = :storage_id AND status = 'P'";
        $stmt2 = $pdo->prepare($query2);
        $stmt2->execute(array(':storage_id' => $_SESSION['storage_id']));
        $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
        $product_name = htmlentities($row['product_name']);
        $available_quantity = htmlentities($row['quantity']-$row2['blocked_quantity']);
        $price = htmlentities($row['price']);
    }
    else{
        $_SESSION['message'] = 'You must select a product to place an order';
        header('Location: products.php');
        return;
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/warehouse/css/styles.css">
    <link rel="font" href="">
    <link rel="apple-touch-icon" sizes="180x180" href="/warehouse/img/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/warehouse/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/warehouse/img/favicon-16x16.png">
    <link rel="manifest" href="/warehouse/img/site.webmanifest">
    <script src="#"></script>
    <title>Warehouse Management system</title>
</head>
<body>
    <header>Order Page</header>
    <div id="msg">
        <?php 
           if (isset($_SESSION['message'])) {
            echo $_SESSION['message'];
            unset($_SESSION['message']);
            } 
        ?>
    </div>
        <table>
            <tr>
                <td>
                    Product Name
                </td>
                <td>
                    <?php echo $product_name; ?>
                </td>
            </tr>
            <tr>
                <td>
                    Available Quantity
                </td>
                <td>
                    <?php echo $available_quantity; ?>
                </td>
            </tr>
            <tr>
                <td>
                    Price per unit
                </td>
                <td>
                    <?php echo $price; ?>
                </td>
        </table>
        <div>
            <form action="placeorder.php" method="post">
                <p>
                    <label for="quantity">Enter Quantity</label>
                    <input type="number" name="quantity" id="quantity" min="1" max="<?php echo $available_quantity; ?>" required autofocus>
                    
                </p>
                <p>
                    <input type="hidden" name="order_confirm" id="order_confirm" value="0">
                    <input type="submit" value="Place Order" onclick="order_confirm.value='1';">
                    <button onclick="location.href='products.php'; return false;">Cancel</button>
                </p>
            </form>
        </div>
    </div>
    <div>
        <button onclick="location.href='dashboard.php'; return false;">Dashboard</button>
    </div>
     
</body>
</html>