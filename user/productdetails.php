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

        $details = $row['description'];
        $details = json_decode($details, true);
        $p_details = $details;
        $a_details = array_splice($p_details, -2);

        $product_desc = $a_details['product_desc'];
        $product_image_path = $a_details['product_image'];
        $product_image = explode('/', $product_image_path);
        $product_image = '/warehouse/uploads/images/'.end($product_image);

        $query2 = "SELECT sum(quantity) as blocked_quantity FROM orders WHERE storage_id = :storage_id AND status = 'P'";
        $stmt2 = $pdo->prepare($query2);
        $stmt2->execute(array(':storage_id' => $storage_id));
        $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
        $quantity = $row['quantity'] - $row2['blocked_quantity'];
        
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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="/warehouse/css/bootstrap.min.css">
    <link rel="stylesheet" href="/warehouse/css/styles.css">
    <link rel="stylesheet" href="/warehouse/css/userlogin.css">
    <link rel="font" href="">
    <link rel="apple-touch-icon" sizes="180x180" href="/warehouse/img/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/warehouse/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/warehouse/img/favicon-16x16.png">
    <link rel="manifest" href="/warehouse/img/site.webmanifest">
    <script src="/warehouse/js/bootstrap.min.js"></script>
    <script src="scripts.js"></script>
    <title>Warehouse Management system</title>
</head>

<body class="text-center d-flex justify-content-center">
    <main class="p-5 m-auto scroll-enable">
        <?php 
            if (isset($_SESSION['message'])) {
            echo ('<div id="msg" class="alert alert-warning alert-dismissible fade show" role="alert">'.
                    $_SESSION['message'].
                    '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>'.
                '</div>');      
            unset($_SESSION['message']);
            } 
        ?>

        <div>
            <h1 class="h3 my-3 fw-normal">Product Details</h1>
        </div>

        <div class="container scroll-enable">
            <div class="row">
                <div class="col-md-6">
                    <img src="<?= $product_image ?>" alt="<?= $product_name ?>" class="img-fluid">
                </div>
                <div class="col-md-6">
                    <table class="table table-striped table-hover">
                        <tr>
                            <th class="table-dark">
                                Product Name
                            </th>
                            <td>
                                <?= $product_name ?>
                            </td>
                        </tr>
                        <tr>
                            <th class="table-dark">
                                Category
                            </th>
                            <td>
                                <?= $category ?>
                            </td>
                        </tr>
                        <tr>
                            <th class="table-dark">
                                Price
                            </th>
                            <td>
                                <?= $price ?>
                            </td>
                        </tr>
                        <tr>
                            <th class="table-dark">
                                Quantity
                            </th>
                            <td>
                                <?= $quantity ?>
                            </td>
                        </tr>
                        <?php
                            foreach ($p_details as $key => $value) {
                                echo '<tr><th class="table-dark">'.$key.'</th><td>'.$value.'</td></tr>';
                            }
                        ?>
                        <tr>
                            <th class="table-dark">
                                Product Description
                            </th>
                            <td>
                                <?= $product_desc ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="pt-5">
            <button class="fixed-bottom w-100 btn btn-lg btn-dark mt-4" onclick="location.href='dashboard.php'; return false;">Dashboard</button>
        </div> 
    </main>
</body>

</html>
<!-- <body>
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
        <button onclick="location.href='dashboard.php'; return false;">Dashboard</button>
    </div> 
</body> -->
</html>