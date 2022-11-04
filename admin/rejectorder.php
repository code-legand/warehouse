<?php
    session_start();
    if(!(isset($_SESSION['adminname']))) {
        $_SESSION['message'] = 'You must be logged in to view this page';
        header('Location: login.php');
        return;
    }
    
    if (isset($_POST['order_id'])){
        $_SESSION['order_id'] = $_POST['order_id'];
        header('Location: rejectorder.php');
        return;
    }

    if (isset($_SESSION['order_id'])){
        if (isset($_POST['reject_confirm']) && $_POST['reject_confirm'] == '1'){
            require_once 'connect.php';
            $query = "UPDATE orders SET status = 'R' WHERE order_id = :order_id";
            $stmt = $pdo->prepare($query);
            $stmt->execute(array(':order_id' => $_SESSION['order_id']));
            $_SESSION['message'] = 'Order rejected successfully';
            header('Location: orders.php');
            return;
        }
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="#">
    <link rel="font" href="">
    <link rel="apple-touch-icon" sizes="180x180" href="/warehouse/img/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/warehouse/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/warehouse/img/favicon-16x16.png">
    <link rel="manifest" href="/warehouse/img/site.webmanifest">
    <script src="#"></script>
    <title>Warehouse Management system</title>
</head>
<body>
    <header>Reject Order</header>
    <div id="msg">
        <?php 
           if (isset($_SESSION['message'])) {
            echo $_SESSION['message'];
            unset($_SESSION['message']);
            } 
        ?>
    </div>
    <div>
        Are you sure you want to reject this order?
        <form action="rejectorder.php" method="post">
            <input type="hidden" name="reject_confirm" id="reject_confirm" value="0">
            <input type="submit" value="YES" onclick="reject_confirm.value = '1'">
            <button onclick="location.href='orders.php'; return false;">NO</button>
    </div>

</body>
</html>