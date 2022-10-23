<?php
    session_start();
    if(!(isset($_SESSION['adminname']))) {
        $_SESSION['message'] = 'You must be logged in to view this page';
        header('Location: login.php');
        return;
    }
    if (isset($_GET['storage_id'])) {
        require_once 'connect.php';
        $query = "SELECT * FROM storage WHERE storage_id = :storage_id";
        $stmt=$pdo->prepare($query);
        $stmt->execute(array(':storage_id' => $_GET['storage_id']));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row === false) {
            $_SESSION['message'] = 'Bad value for storage_id';
            header('Location: products.php');
            return;
        }
        $storage_id = htmlentities($row['storage_id']);
        $admin_id = htmlentities($row['admin_id']);
        $product_name = htmlentities($row['product_name']);
        $category = htmlentities($row['category']);
        $price = htmlentities($row['price']);
        $quantity = htmlentities($row['quantity']);
        $action = htmlentities($row['action']);
        $action_date = htmlentities($row['action_date']);
        $block_no = htmlentities($row['block_no']);
        $row_no = htmlentities($row['row_no']);

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
    <header>Product Details</header>
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
                    Admin Id
                </td>
                <td>
                    <?= $admin_id ?>
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
                    Product Category
                </td>
                <td>
                    <?= $category ?>
                </td>
            </tr>
            <tr>
                <td>
                    Product Price
                </td>
                <td>
                    <?= $price ?>
                </td>
            </tr>
            <tr>
                <td>
                    Product Quantity
                </td>
                <td>
                    <?= $quantity ?>
                </td>
            </tr>
            <?php
                foreach ($p_details as $key => $value) {
                    echo "<tr><td>$key</td><td>$value</td></tr>";
                }
            ?>
            <tr>
                <td>
                    Product Image
                </td>
                <td>
                    <img src="<?= $product_image ?>" alt="<?= $product_name ?>" class="productdetails_image">
                </td>
            </tr>
            <tr>
                <td>
                    Product Description
                </td>
                <td>
                    <?= $product_desc ?>
                </td>
            </tr>
            <tr>
                <td>
                    Action
                </td>
                <td>
                    <?= $action ?>
                </td>
            </tr>
            <tr>
                <td>
                    Action Date
                </td>
                <td>
                    <?= $action_date ?>
                </td>
            </tr>
            <tr>
                <td>
                    Block number
                </td>
                <td>
                    <?= $block_no ?>
                </td>
            </tr>
            <tr>
                <td>
                    Row number
                </td>
                <td>
                    <?= $row_no ?>
                </td>
        </table>
    </div>
    <div>
        <button onclick="location.href='products.php'; return false;">Back</button>
    </div>

</body>
</html>