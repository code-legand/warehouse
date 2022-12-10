<?php
    session_start();
    if(!(isset($_SESSION['adminname']))) {
        $_SESSION['message'] = 'You must be logged in to view this page';
        header('Location: login.php');
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


        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-12 col-md-10">
                    <img class="mb-4" src="/warehouse/img/android-chrome-512x512.png" alt="logo-image" width="72"
                        height="57">
                    <h1 class="h3 mb-3 fw-normal">Dashboard</h1>
                    <button class="w-100 btn btn-lg btn-dark" onclick="location.href='changepassword.php';">Change Password</button>
                    <button class="w-100 btn btn-lg btn-dark mt-4" onclick="location.href='orders.php';">Order Management</button>
                    <button class="w-100 btn btn-lg btn-dark mt-4" onclick="location.href='products.php';">Product Management</button>
                    <button class="w-100 btn btn-lg btn-dark mt-4" onclick="location.href='users.php';">User Management</button>
                    <button class="w-100 btn btn-lg btn-dark mt-4" onclick="location.href='queries.php';">Query Manager</button>
                    <button class="w-100 btn btn-lg btn-dark mt-4" onclick="location.href='reports.php';">Reports</button>
                    <button class="w-100 btn btn-lg btn-dark mt-4" onclick="location.href='logout.php';">Logout</button>
                    </div>
            </div>
    </main>
</body>
<!-- <body>
    <header>Dashboard</header>
    <div id="msg">
        
    </div>
    <div>
        <div>
            <a href="changepassword.php">Change Password</a>
        </div>
        <div>
            <a href="orders.php">Order Management</a>
        </div>
        <div>
            <a href="products.php">Product Management</a>
        </div>
        <div>
            <a href="users.php">User Management</a>
        </div>
        <div>
            <a href="queries.php">Query Manager</a>
        </div>
        <div>
            <a href="reports.php">Reports</a>
        </div>
        <div>
            <a href="logout.php">Log Out</a>
        </div> 
    </div>    

</body> -->
</html>