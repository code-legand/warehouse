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
        $action_map = array(
            'A' => 'Add',
            'U' => 'Update',
        );
        $action = htmlentities($action_map[$row['action']]);
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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="/warehouse/css/bootstrap.min.css">
    <link rel="stylesheet" href="/warehouse/css/styles.css">
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

        <div class="container px-0 scroll-enable"> 
            <table class="table p-2 table-striped table-hover">
                <tbody class="align-middle">
                    <tr>
                        <th class="table-dark">
                            Storage Id
                        </th>
                        <td>
                            <?= $storage_id ?>
                        </td>
                    </tr>
                    <tr>
                        <th class="table-dark">
                            Admin Id
                        </th>
                        <td>
                            <?= $admin_id ?>
                        </td>
                    </tr>
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
                            Product Category
                        </th>
                        <td>
                            <?= $category ?>
                        </td>
                    </tr>
                    <tr>
                        <th class="table-dark">
                            Product Price
                        </th>
                        <td>
                            <?= $price ?>
                        </td>
                    </tr>
                    <tr>
                        <th class="table-dark">
                            Product Quantity
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
                            Product Image
                        </th>
                        <td>
                            <img src="<?= $product_image ?>" alt="<?= $product_name ?>" class="productdetails_image">
                        </td>
                    </tr>
                    <tr>
                        <th class="table-dark">
                            Product Description
                        </th>
                        <td>
                            <?= $product_desc ?>
                        </td>
                    </tr>
                    <tr>
                        <th class="table-dark">
                            Action
                        </th>
                        <td>
                            <?= $action ?>
                        </td>
                    </tr>
                    <tr>
                        <th class="table-dark">
                            Action Date
                        </th>
                        <td>
                            <?= $action_date ?>
                        </td>
                    </tr>
                    <tr>
                        <th class="table-dark">
                            Block number
                        </th>
                        <td>
                            <?= $block_no ?>
                        </td>
                    </tr>
                    <tr>
                        <th class="table-dark">
                            Row number
                        </th>
                        <td>
                            <?= $row_no ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="pt-5">
            <button class="fixed-bottom w-100 btn btn-lg btn-dark mt-4"
                onclick="location.href='products.php'; return false;">Back</button>
        </div>
    </main>
</body>

</html>