<?php
    session_start();
    if(!(isset($_SESSION['adminname']))) {
        $_SESSION['message'] = 'You must be logged in to view this page';
        header('Location: login.php');
        return;
    }
    if(isset($_GET['user_id'])) {
        $user_id=$_GET['user_id'];
        require_once 'connect.php';
        $query="SELECT * FROM users WHERE user_id = :user_id";
        $stmt=$pdo->prepare($query);
        $stmt->execute(array(':user_id' => $user_id));

        if($stmt->rowCount() == 0) {
            $_SESSION['message'] = 'Bad value for user_id';
            header('Location: users.php');
            return;
        }
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $user_id = htmlentities($row['user_id']);
        $user_name = htmlentities($row['user_name']);
        $street = htmlentities($row['street']);
        $city = htmlentities($row['city']);
        $state = htmlentities($row['state']);
        $zip_code = htmlentities($row['zip_code']);
        $phone = htmlentities($row['phone']);
        $email = htmlentities($row['email']);
        $password = htmlentities($row['password']);
    }
    else {
        $_SESSION['message'] = 'Missing user_id';
        header('Location: users.php');
        return;
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/warehouse/css/styles.css">
    <link rel="font" href="">
    <link rel="apple-touch-icon" sizes="180x180" href="/warehouse/img/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/warehouse/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/warehouse/img/favicon-16x16.png">
    <link rel="manifest" href="/warehouse/img/site.webmanifest">
    <script src="#"></script>
    <title>Warehouse Management system</title>
</head>
<body>
    <header>User Details</header>
    <div id="msg">
        <?php 
           if (isset($_SESSION['message'])) {
            echo $_SESSION['message'];
            unset($_SESSION['message']);
            } 
        ?>
    </div>
    <div>
        <table>
            <caption><?= $user_id ?></caption>
            <tr>
                <td>
                    User Name
                </td>
                <td>
                    <?= $user_name ?>
                </td>
            </tr>
            <tr>
                <td>
                    Street
                </td>
                <td>
                    <?= $street ?>
                </td>
            </tr>
            <tr>
                <td>
                    City
                </td>
                <td>
                    <?= $city ?>
                </td>
            </tr>
            <tr>
                <td>
                    State
                </td>
                <td>
                    <?= $state ?>
                </td>
            </tr>
            <tr>
                <td>
                    Zip-Code
                </td>
                <td>
                    <?= $zip_code ?>
                </td>
            </tr>
            <tr>
                <td>
                    Phone
                </td>
                <td>
                    <?= $phone ?>
                </td>
            </tr>
            <tr>
                <td>
                    Email
                </td>
                <td>
                    <?= $email ?>
                </td>
            </tr>
            <tr>
                <td>
                    Password
                </td>
                <td>
                    <?= $password ?>
                </td>
            </tr>
        </table>
    </div>
    <div>
        <button onclick="location.href='users.php'; return false;">Back</button>
    </div> 

</body>
</html>