<?php
    session_start();
    if(!(isset($_SESSION['adminname']))) {
        $_SESSION['message'] = 'You must log in first';
        header('Location: login.php');
        return;
    }
    if (isset($_POST['current_pass']) && isset($_POST['new_pass']) && isset($_POST['re_new_pass'])) {
        require_once "connect.php";
        $query='SELECT admin_name FROM admins WHERE admin_name = :adminname and password = :passwd;';
        $stmt = $pdo->prepare($query);
        $stmt->execute(array(':adminname' => $_SESSION['adminname'], 'passwd'=> $_POST['current_pass']));
        if ($stmt->rowCount() != 0) {
            if($_POST['new_pass'] == $_POST['re_new_pass']) {
                if ($_POST['new_pass'] != $_POST['current_pass']){
                    $query='UPDATE admins SET password = :new_pass WHERE admin_name = :adminname;';
                    $stmt = $pdo->prepare($query);
                    $stmt->execute(array(':new_pass' => $_POST['new_pass'], ':adminname' => $_SESSION['adminname']));
                    $_SESSION['message'] = 'Password changed successfully';
                    header('Location: changepassword.php');
                    return;
                }
                else {
                    $_SESSION['message'] = 'New password cannot be same as current password';
                    header('Location: changepassword.php');
                    return;
                }  
            } 
            else {
                $_SESSION['message'] = 'Re-entered password is incorrect';
                header('Location: changepassword.php');
                return;
            }
        } 
        else {
            $_SESSION['message'] = 'Invalid current password';
            header('Location: changepassword.php');
            return;
        }
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
        <div class="container ">
            <div class="row d-flex justify-content-center">
                <div class="col-12 col-md-10">
                    <form action="changepassword.php" method="POST">
                        <h1 class="h3 mb-3 fw-normal">Change Password</h1>

                        <div class="form-floating">
                            <input type="password" class="form-control mt-2" name="current_pass" id="c_pass"
                                placeholder="Current password" required>
                            <label for="c_pass">Enter Current Password: </label>
                        </div>
                        <div class="form-floating">
                            <input type="password" class="form-control mt-2" name="new_pass" id="n_pass"
                                placeholder="New password" required>
                            <label for="n_pass">Enter New Password: </label>
                        </div>
                        <div class="form-floating">
                            <input type="password" class="form-control mt-2" name="re_new_pass" id="re_n_pass"
                                placeholder="New password" required>
                            <label for="re_n_pass">Re-enter New Password: </label>
                        </div>

                        <input type="submit" class="w-100 btn btn-lg btn-dark mt-4" value="Change Password">
                        <button class="w-100 btn btn-lg btn-dark mt-2"
                            onclick="location.href='changepassword.php'; return false;">Clear</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="pt-5">
            <button class="fixed-bottom w-100 btn btn-lg btn-dark mt-4" onclick="location.href='dashboard.php'; return false;">Dashboard</button>    <!--************marks**************  -->
        </div>
    </main>

</body>

<!-- <body>
    <header>Change Password</header>
    <div id="msg">
        
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
                <button onclick="location.href='changepassword.php'; return false;">Clear</button>
            </p>
        </form>
    </div>
    <div>
        <button onclick="location.href='dashboard.php'; return false;">Back to Home</button>
    </div>  

</body> -->
</html>