<?php
    session_start();
    if(!(isset($_SESSION['adminname']))) {
        $_SESSION['message'] = 'You must be logged in to view this page';
        header('Location: login.php');
        return;
    }
    if (isset($_POST['user_id'])) {
        $_SESSION['user_id'] = $_POST['user_id'];
        header('Location: deleteuser.php');
        return;
    }
    if(isset($_POST['delete_confirm']) && $_POST['delete_confirm'] == '1') {
        $user_id = $_SESSION['user_id'];
        require_once 'connect.php';
        $query = "DELETE FROM users WHERE user_id = :user_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(array(':user_id' => $user_id));
        if($stmt->rowCount() != 0) {
            $_SESSION['message'] = 'User deleted successfully';
            header('Location: users.php');
            return;
        }
        else {
            $_SESSION['message'] = 'User deletion failed';
            header('Location: users.php');
            return;
        }
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
                <form action="deleteuser.php" method="POST">
                    <label class="form-label form-control-lg px-0">Are you sure you want to delete this user?</label>
                    <input type="hidden" name="delete_confirm" id="delete_confirm" value="0">
                    <input class="w-100 btn btn-lg btn-dark mt-4" type="submit" value="YES" onclick="delete_confirm.value = '1';">
                    <button class="w-100 btn btn-lg btn-dark mt-2 mb-4" onclick="location.href='users.php'; return false;">NO</button>
                </form>
            </div>
        </div> 
    </div>
    </main>
</body>
<!-- <body>
    <header>Delete User</header>
    <div id="msg">
        
    </div>
    <div>
        Are you sure you want to delete this user?
        <form action="deleteuser.php" method="POST">
            <input type="hidden" name="delete_confirm" id="delete_confirm" value="0">
            <input type="submit" value="YES" onclick="delete_confirm.value = '1';">
            <button onclick="location.href='users.php'; return false;">NO</button>
        </form>
    </div>    

</body> -->
</html>