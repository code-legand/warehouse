<?php
    session_start();
    if(!(isset($_SESSION['username']))) {
        $_SESSION['message'] = 'You must be logged in to view this page';
        header('Location: login.php');
        return;
    }  
    if (isset($_GET['storage_id'])) {
        require_once 'connect.php';
        $query = "SELECT product_name, category, description, price, quantity FROM storage WHERE storage_id = :storage_id";
        $stmt=$pdo->prepare($query);
        $stmt->execute(array(':storage_id' => $_GET['storage_id']));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row === false) {
            $_SESSION['message'] = 'Bad value for storage_id';
            header('Location: products.php');
            return;
        }
        $storage_id = $_GET['storage_id'];
        $product_name = htmlentities($row['product_name']);
        $category = htmlentities($row['category']);
        $price = htmlentities($row['price']);
        $quantity = htmlentities($row['quantity']);

        $details = $row['description'];
        $details = json_decode($details, true);
        $p_details = $details;
        $a_details = array_splice($p_details, -2);

        $product_desc = $a_details['product_desc'];
        $product_image_path = $a_details['product_image'];
        $product_image = explode('/', $product_image_path);
        $product_image = '/warehouse/uploads/images/'.end($product_image);
        
    } 
    else {
        $_SESSION['message'] = 'Missing storage_id';
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
    <header>Product details</header>
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
            <caption><?= $storage_id ?></caption>
            <tr>
                <td>
                    Product Image
                </td>
                <td>
                    <img src="<?= $product_image ?>" alt="<?= $product_name ?>">
                </td>
            </tr>
            <tr>
                <td>
                    Product Name
                </td>
                <td>
                    <?= $product_name ?>
                </td>
            </tr>
            <tr>
                <td>
                    Category
                </td>
                <td>
                    <?= $category ?>
                </td>
            </tr>
            <tr>
                <td>
                    Price
                </td>
                <td>
                    <?= $price ?>
                </td>
            </tr>
            <tr>
                <td>
                    Quantity
                </td>
                <td>
                    <?= $quantity ?>
                </td>
            </tr>
            <?php
                foreach ($p_details as $key => $value) {
                    echo '<tr><td>'.$key.'</td><td>'.$value.'</td></tr>';
                }
            ?>
            <tr>
                <td>
                    Product Description
                </td>
                <td>
                    <?= $product_desc ?>
                </td>
            </tr>

        </table>
    </div>
    <div>
        <form action="placeorder.php" method="post">
            <input type="hidden" name="storage_id" value="<?= $storage_id ?>">
            <input type="submit" value="Order">
        </form>
    </div>
    <div>
        <button onclick="location.href='products.php'; return false;">Back</button>
    </div> 
</body>
</html>