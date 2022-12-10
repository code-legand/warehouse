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
<!-- <body>
    <header>Order Details</header>
    <div id="msg">
        
    </div>
    <div>
        <table> -->

        <body class="text-center d-flex justify-content-center">
<main class="px-0 m-auto">
        <?php 
            if (isset($_SESSION['message'])) {
            echo ('<div id="msg" class="alert alert-warning alert-dismissible fade show" role="alert">'.
                    $_SESSION['message'].
                    '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>'.
                '</div>');      
            unset($_SESSION['message']);
            } 
        ?>

    <div class="container px-0">
    <h1 class="h3 my-3 fw-normal">Order Details</h1>
            <table class="table p-2">
            <tbody class="align-middle">    <!--align vertically center-->
            <tr>
                <th class="table-dark">
                    User ID
                </th>
                <td>
                    <?= $row['user_id'] ?>
                </td>
            </tr>
            <tr>
                <th class="table-dark">
                    User Name
                </th>
                <td>
                    <?= $row['user_name'] ?>
                </td>
            </tr>
            <tr>
                <th class="table-dark">
                    Street
                </th>
                <td>
                    <?= $row['street'] ?>
                </td>
            </tr>
            <tr>
                <th class="table-dark">
                    City
                </th>
                <td>
                    <?= $row['city'] ?>
                </td>
            </tr>
            <tr>
                <th class="table-dark">
                    State
                </th>
                <td>
                    <?= $row['state'] ?>
                </td>
            </tr>
            <tr>
                <th class="table-dark">
                    Zip Code
                </th>
                <td>
                    <?= $row['zip_code'] ?>
                </td>
            </tr>
            <tr>
                <th class="table-dark">
                    Phone
                </th>
                <td>
                    <?= $row['phone'] ?>
                </td>
            </tr>
            <tr>
                <th class="table-dark">
                    Email
                </th>
                <td>
                    <?= $row['email'] ?>
                </td>
            </tr>
            <tr>
                <th class="table-dark">
                    Storage ID
                </th>
                <td>
                    <?= $row['storage_id'] ?>
                </td>
            </tr>
            <tr>
                <th class="table-dark">
                    Product Name
                </th>
                <td>
                    <?= $row['product_name'] ?>
                </td>
            </tr>
            <tr>
                <th class="table-dark">
                    Category
                </th>
                <td>
                    <?= $row['category'] ?>
                </td>
            </tr>
            <tr>
                <th class="table-dark">
                    Price
                </th>
                <td>
                    <?= $row['price'] ?>
                </td>
            </tr>
            <tr>
                <th class="table-dark">
                    Block No
                </th>
                <td>
                    <?= $row['block_no'] ?>
                </td>
            </tr>
            <tr>
                <th class="table-dark">
                    Row No
                </th>
                <td>
                    <?= $row['row_no'] ?>
                </td>
            </tr>
            <tr>
                <th class="table-dark">
                    Quantity
                </th>
                <td>
                    <?= $row['quantity'] ?>
                </td>
            </tr>
            <tr>
                <th class="table-dark">
                    Date
                </th>
                <td>
                    <?= $row['date'] ?>
                </td>
            </tr>
            <tr>
                <th class="table-dark">
                    Time
                </th>
                <td>
                    <?= $row['time'] ?>
                </td>
            </tr>
            <tr>
                <th class="table-dark">
                    Status
                </th>
                <td>
                    <?= $status_map[$row['status']] ?>
                </td>
            </tr>
        </tbody>
        </table>
    </div>
    <div class="pt-5">
        <button class="fixed-bottom w-100 btn btn-lg btn-dark mt-4" onclick="location.href='orders.php'; return false;">Back</button>
    </div> 

</body>
</html>