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
            $_SESSION['message'] = 'You have been logged in successfully';
            header('Location: dashboard.php');
            return;
        } else {
            $_SESSION['message'] = 'Invalid username or password';
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
    <!-- <header>Log In</header> -->
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
                    <form action="login.php" method="post">
                        <img class="mb-4" src="/warehouse/img/android-chrome-512x512.png" alt="logo-image" width="72"
                            height="57">
                        <h1 class="h3 mb-3 fw-normal">Admin Log In</h1>

                        <div class="form-floating">
                            <input type="text" class="form-control" id="aname" placeholder="Admin name" name="adminname" required>
                            <label for="aname" class="justify-content-start">Admin Name</label>
                        </div>
                        <div class="form-floating">
                            <input type="password" class="form-control mt-2" id="pass"
                                placeholder="Password" name="passwd" required>
                            <label for="pass" class="justify-content-start">Password</label>
                        </div>

                        <input class="w-100 btn btn-lg btn-dark mt-4" type="submit" name="login" value="Log in">
                        <button class="w-100 btn btn-lg btn-dark mt-2"
                            onclick="location.href='login.php'; return false">Cancel</button>
                        <!-- <p class="mt-5 mb-3 text-muted">&copy; 2017–2021</p> -->
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
<!-- <body>
    <header>Admin Log In</header>
    <div id="msg">
        
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
</html> -->