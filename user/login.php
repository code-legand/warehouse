<?php
    session_start();
    if (isset($_SESSION['username'])) {
        header('Location: dashboard.php');
        return;
    }
    
    if(isset($_POST['username']) && isset($_POST['passwd'])) {
        $username = $_POST['username'];
        $passwd = $_POST['passwd'];
        require_once 'connect.php';
        $query='SELECT user_name FROM users WHERE user_name = :username AND password = :passwd;';
        $stmt = $pdo->prepare($query);
        $stmt->execute(array(':username' => $username, ':passwd' => $passwd));

        if ($stmt->rowCount() != 0) {
            $_SESSION['username'] = $username;
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


        <div class="container ">
            <div class="row d-flex justify-content-center">
                <div class="col-12 col-md-10">
                    <form action="login.php" method="post">
                        <img class="mb-4" src="/warehouse/img/android-chrome-512x512.png" alt="logo-image" width="72"
                            height="57">
                        <h1 class="h3 mb-3 fw-normal">Log In</h1>

                        <div>
                            <input type="text" class="form-control" id="floatingInput" placeholder="User name" name="username">

                        </div>
                        <div>
                            <input type="password" class="form-control mt-2" id="floatingPassword"
                                placeholder="Password" name="passwd">

                        </div>

                        <input class="w-100 btn btn-lg btn-dark mt-4" type="submit" name="login" value="Log in">
                        <button class="w-100 btn btn-lg btn-dark mt-2"
                            onclick="location.href='login.php'; return false">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>
</html>