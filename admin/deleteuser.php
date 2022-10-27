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
    <header>Delete User</header>
    <div id="msg">
        <?php 
           if (isset($_SESSION['message'])) {
            echo $_SESSION['message'];
            unset($_SESSION['message']);
            } 
        ?>
    </div>
    <div>
        Are you sure you want to delete this user?
        <form action="deleteuser.php" method="POST">
            <input type="hidden" name="delete_confirm" id="delete_confirm" value="0">
            <input type="submit" value="YES" onclick="delete_confirm.value = '1';">
            <button onclick="location.href='users.php'; return false;">NO</button>
        </form>
    </div>    

</body>
</html>