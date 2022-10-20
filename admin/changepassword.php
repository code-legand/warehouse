<?php
    session_start();
    if(!(isset($_SESSION['adminName']))) {
        $_SESSION['message'] = 'You must log in first';
        header('Location: login.php');
        return;
    }
    if (isset($_POST['current_pass']) && isset($_POST['new_pass']) && isset($_POST['re_new_pass'])) {
        require_once "connect.php";
        $query='SELECT password FROM admins WHERE admin_name = :username;';
        $stmt = $pdo->prepare($query);
        $stmt->execute(array(':username' => $_SESSION['adminName']));
        
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
    <header>Change Password</header>
    <div id="msg">
        <?php 
           if (isset($_SESSION['message'])) {
            echo $_SESSION['message'];
            unset($_SESSION['message']);
            } 
        ?>
    </div>
    <div>
        <form action="changepassword.php" method="POST">
            <p>
                <label for="c_pass">Enter Current Password: </label>
                <input type="password" name="current_pass" id="c_pass" required>
            </p>
            <p>
                <label for="n_pass">Enter New Password: </label>
                <input type="password" name="new_pass" id="n_pass" required>
            </p>
            <p>
                <label for="re_n_pass">Re-enter New Password: </label>
                <input type="password" name="re_new_pass" id="re_n_pass" required>
            </p>
            <p>
                <input type="submit" value="Change Password">
                <button onclick="location.href='changepassword.php'; return false;">Cancel</button>
            </p>
        </form>
    </div>    

</body>
</html>