<?php
    session_start();
    if(isset($_SESSION['adminname'])) {
        header('Location: dashboard.php');
        return;
    }
    if(isset($_POST['adminname']) && isset($_POST['passwd'])) {
        $adminname = $_POST['adminname'];
        $passwd = $_POST['passwd'];
        require_once 'connect.php';
        $query='SELECT admin_name FROM admins WHERE admin_name = :adminname AND password = :passwd;';
        $stmt = $pdo->prepare($query);
        $stmt->execute(array(':adminname' => $adminname, ':passwd' => $passwd));
        if ($stmt->rowCount() != 0) {
            $_SESSION['adminname'] = $adminname;
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
    <header>Admin Log In</header>
    <div id="msg">
        <?php 
           if (isset($_SESSION['error'])) {
            echo $_SESSION['error'];
            unset($_SESSION['error']);
            } 
        ?>
    </div>
    <div>
        <form action="login.php" method="post">
            <p>
                <label for="aname">Admin Name: </label>
                <input type="text" name="adminname" id="aname">
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