<?php
    session_start();
    if(!(isset($_SESSION['username']))) {
        $_SESSION['message'] = 'You must be logged in to view this page';
        header('Location: login.php');
        return;
    }
    if(isset($_POST['order_id'])){
        $_SESSION['order_id'] = $_POST['order_id'];
        header('Location: cancelorder.php');
        return;
    }
    if(isset($_POST['cancel_confirm']) && $_POST['cancel_confirm'] == '1'){
        require_once 'connect.php';
        $query = "UPDATE orders SET status = 'D' WHERE order_id = :order_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(array(':order_id' => $_SESSION['order_id']));
        if($stmt->rowCount() > 0){
            $_SESSION['message'] = 'Order cancelled successfully';
            header('Location: myorders.php');
            return;
        }
        else{
            $_SESSION['message'] = 'Order could not be cancelled';
            header('Location: myorders.php');
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
    <main class="m-auto p-5">
        <?php 
            if (isset($_SESSION['message'])) {
            echo ('<div id="msg" class="alert alert-warning alert-dismissible fade show" role="alert">'.
                    $_SESSION['message'].
                    '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>'.
                '</div>');      
            unset($_SESSION['message']);
            } 
        ?>
    </div>
    <div class="container ">
            <div class="row d-flex justify-content-center">
                <div class="col-12 col-md-10">
                    <form action="cancelorder.php" method="post">
                    <label class="form-label form-control-lg px-0">Are you sure you want to cancel this order?</label>
                    <input type="hidden" name="cancel_confirm" id="cancel_confirm" value="0">
            <input type="submit" class="w-100 btn btn-lg btn-dark mt-4" value="Yes" onclick="cancel_confirm.value = '1'">
            <button class="w-100 btn btn-lg btn-dark mt-2 mb-4" onclick="location.href='myorders.php'; return false;">No</button> 
        </form>
    </div> 
</div>
</div>
    </main>
</body>
</html>