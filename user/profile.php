<?php
    session_start();
    if(!(isset($_SESSION['username']))) {
        $_SESSION['message'] = 'You must be logged in to view this page';
        header('Location: login.php');
        return;
    }
    else{
        $username = $_SESSION['username'];
        $query = "SELECT * FROM users WHERE user_name = :username;";
        require_once 'connect.php';
        $stmt = $pdo->prepare($query);
        $stmt->execute(array(':username' => $username));
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
    <main class="p-5 m-auto scroll-enable">
        <?php 
            if (isset($_SESSION['message'])) {
            echo ('<div id="msg" class="alert alert-warning alert-dismissible fade show" role="alert">'.
                    $_SESSION['message'].
                    '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>'.
                '</div>');      
            unset($_SESSION['message']);
            } 
        ?>

        <div>
            <h1 class="h3 my-3 fw-normal">Profile</h1>
        </div>

        <!-- <div>
        <table> -->
        <div class="container px-0 scroll-enable">
            <table class="table p-2 table-striped table-hover">
                <tr>
                    <th class="table-dark">User ID</th>
                    <td>
                        <?= $user_id ?>
                    </td>
                </tr>
                <tr>
                    <th class="table-dark">User Name</th>
                    <td>
                        <?= $user_name ?>
                    </td>
                </tr>
                <tr>
                    <th class="table-dark">Street</th>
                    <td>
                        <?= $street ?>
                    </td>
                </tr>
                <tr>
                    <th class="table-dark">City</th>
                    <td>
                        <?= $city ?>
                    </td>
                </tr>
                <tr>
                    <th class="table-dark">State</th>
                    <td>
                        <?= $state ?>
                    </td>
                </tr>
                <tr>
                    <th class="table-dark">Zip Code</th>
                    <td>
                        <?= $zip_code ?>
                    </td>
                </tr>
                <tr>
                    <th class="table-dark">Phone</th>
                    <td>
                        <?= $phone ?>
                    </td>
                </tr>
                <tr>
                    <th class="table-dark">Email</th>
                    <td>
                        <?= $email ?>
                    </td>
                </tr>
                <tr>
                    <th class="table-dark">Password</th>
                    <td>
                        <?= $password ?>
                    </td>
                </tr>
            </table>
        </div>
        <div>
            <button class="w-100 btn btn-lg btn-dark" onclick="location.href='updateprofile.php';">Update
                Profile</button>
        </div>
        <div class="pt-5">
            <button class="fixed-bottom w-100 btn btn-lg btn-dark mt-4"
                onclick="location.href='dashboard.php'; return false;">Dashboard</button>
        </div>
    </main>
</body>

</html>