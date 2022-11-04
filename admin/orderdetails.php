<?php
    session_start();
    if(!(isset($_SESSION['adminname']))) {
        $_SESSION['message'] = 'You must be logged in to view this page';
        header('Location: login.php');
        return;
    }
    if(isset($_GET['order_id'])){
        require_once 'connect.php';
        $query = "SELECT users.user_id, user_name, street, city, state, zip_code, phone, email, storage.storage_id, orders.product_name, category, price, block_no, row_no, orders.quantity, date, time, status FROM users, orders, storage WHERE users.user_id = orders.user_id and orders.storage_id = storage.storage_id and order_id = :order_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(array(':order_id' => $_GET['order_id']));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $status_map = array('P' => 'Pending', 'C' => 'Confirmed', 'D' => 'Cancelled', 'R' => 'Rejected');
    }
    else{
        $_SESSION['message'] = 'Invalid order';
        header('Location: orders.php');
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
    <header>Order Details</header>
    <div id="msg">
        <?php 
           if (isset($_SESSION['message'])) {
            echo $_SESSION['message'];
            unset($_SESSION['message']);
            } 
        ?>
    </div>
    <div>
        <table>
            <tr>
                <td>
                    User ID
                </td>
                <td>
                    <?= $row['user_id'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    User Name
                </td>
                <td>
                    <?= $row['user_name'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    Street
                </td>
                <td>
                    <?= $row['street'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    City
                </td>
                <td>
                    <?= $row['city'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    State
                </td>
                <td>
                    <?= $row['state'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    Zip Code
                </td>
                <td>
                    <?= $row['zip_code'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    Phone
                </td>
                <td>
                    <?= $row['phone'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    Email
                </td>
                <td>
                    <?= $row['email'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    Storage ID
                </td>
                <td>
                    <?= $row['storage_id'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    Product Name
                </td>
                <td>
                    <?= $row['product_name'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    Category
                </td>
                <td>
                    <?= $row['category'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    Price
                </td>
                <td>
                    <?= $row['price'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    Block No
                </td>
                <td>
                    <?= $row['block_no'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    Row No
                </td>
                <td>
                    <?= $row['row_no'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    Quantity
                </td>
                <td>
                    <?= $row['quantity'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    Date
                </td>
                <td>
                    <?= $row['date'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    Time
                </td>
                <td>
                    <?= $row['time'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    Status
                </td>
                <td>
                    <?= $status_map[$row['status']] ?>
                </td>
            </tr>
        </table>
    </div>
    <div>
        <button onclick="location.href='orders.php'; return false;">Back</button>
    </div> 

</body>
</html>