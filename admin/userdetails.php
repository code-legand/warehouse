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
    <header>User Details</header>
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
    <h1 class="h3 my-3 fw-normal">User Details</h1>
            <table class="table p-2">
            <tbody class="align-middle">    <!--align vertically center-->
            <tr>
                <th class="table-dark">
                    User ID
                </th>
                <td>
                    <?= $user_id ?>
                </td>
            </tr>
            <tr>
                <th class="table-dark">
                    User Name
                </th>
                <td>
                    <?= $user_name ?>
                </td>
            </tr>
            <tr>
                <th class="table-dark">
                    Street
                </th>
                <td>
                    <?= $street ?>
                </td>
            </tr>
            <tr>
                <th class="table-dark">
                    City
                </th>
                <td>
                    <?= $city ?>
                </td>
            </tr>
            <tr>
                <th class="table-dark">
                    State
                </th>
                <td>
                    <?= $state ?>
                </td>
            </tr>
            <tr>
                <th class="table-dark">
                    Zip-Code
                </th>
                <td>
                    <?= $zip_code ?>
                </td>
            </tr>
            <tr>
                <th class="table-dark">
                    Phone
                </th>
                <td>
                    <?= $phone ?>
                </td>
            </tr>
            <tr>
                <th class="table-dark">
                    Email
                </th>
                <td>
                    <?= $email ?>
                </td>
            </tr>
            <tr>
                <th class="table-dark">
                    Password
                </th>
                <td>
                    <?= $password ?>
                </td>
            </tr>
        </table>
    </div>
    <div class="pt-5">
        <button class="fixed-bottom w-100 btn btn-lg btn-dark mt-4" onclick="location.href='users.php'; return false;">Back</button>
    </div> 

</body>
</html>