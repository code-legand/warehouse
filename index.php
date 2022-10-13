<?php 
    session_start();
    if (isset($_SESSION['username'])) {
        header('Location: /warehouse/user/dashboard.php');
        return;
    }
    if (isset($_SESSION['message'])) {
        echo $_SESSION['message'];
        unset($_SESSION['message']);
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
    <header>Warehouse Management System</header>
    
    <div>
        <button onclick="location.href='user/login.php';">Log In</button>
        <button onclick="location.href='user/signup.php';">Register</button>
    </div>  
</body>
</html>