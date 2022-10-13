<?php
    session_start();
    if (isset($_SESSION['username'])) {
        header('Location: dashboard.php');
        return;
    }
    if (isset($_SESSION['error'])) {
        echo $_SESSION['error'];
        unset($_SESSION['error']);
    }
    if(isset($_POST['username']) && isset($_POST['passwd'])) {
        $username = $_POST['username'];
        $passwd = $_POST['passwd'];
        require_once 'connect.php';
        $_SESSION['pdo'] = connect($username, $passwd);
        if ($_SESSION['pdo'] != false) {
            $_SESSION['username'] = $username;
            $_SESSION['success'] = 'You have been logged in successfully';
            header('Location: dashboard.php');
            return;
        } else {
            $_SESSION['error'] = 'Invalid username or password';
            header('Location: login.php');
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
    <header>Log In</header>
    <div id="msg"></div>
    <div>
        <form action="login.php" method="post">
            <p>
                <label for="uname">User Name: </label>
                <input type="text" name="username" id="uname">
            </p>
            <p>
                <label for="pass">Password: </label>
                <input type="password" name="passwd" id="pass">
            </p>
            <p>
                <input type="submit" name="login" value="Log In">
                <button onclick="location.href='login.php'; return false">Cancel</button>
            </p>
        </form>
    </div>  
</body>
</html>