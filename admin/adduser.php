<?php
    session_start();
    if(!(isset($_SESSION['adminname']))) {
        $_SESSION['message'] = 'You must be logged in to view this page';
        header('Location: login.php');
        return;
    }
    if(isset($_POST['username']) && isset($_POST['passwd']) && isset($_POST['email']) && isset($_POST['phone']) && isset($_POST['street']) && isset($_POST['city']) && isset($_POST['state']) && isset($_POST['zipcode'])) {
        require_once 'connect.php';
        $query="select user_name from users where user_name='".$_POST['username']."';";
        $data=$pdo->query($query);
        if($data->rowCount()==0) {
            $stmt=$pdo->prepare("insert into users(user_name, password, email, phone, street, city, state, zip_code) values (:username, :passwd, :email, :phone, :street, :city, :state, :zipcode);");
            $stmt->execute(array(
                ':username' => $_POST['username'],
                ':passwd' => $_POST['passwd'],
                ':email' => $_POST['email'],
                ':phone' => $_POST['phone'],
                ':street' => $_POST['street'],
                ':city' => $_POST['city'],
                ':state' => $_POST['state'],
                ':zipcode' => $_POST['zipcode']
            ));
            $_SESSION['message'] = 'User added successfully';
            header('Location: adduser.php');
            return;
        }
        else {
            $_SESSION['message']="Username already exists";
            header('Location: adduser.php');
            return;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
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
                    <form action="adduser.php" method="post">
                        <h1 class="h3 mb-3 fw-normal">Add User</h1>
                        <div class="form-floating">
                            <input type="text" class="form-control mt-2" name="username" id="uname" placeholder="User Name" required>
                            <label for="uname">User Name</label>
                        </div>
                        <div class="form-floating">
                            <input type="password" class="form-control mt-2" name="passwd" id="pass" placeholder="Password" required>
                            <label for="pass">Password</label>
                        </div>
                        <div class="form-floating">
                            <input type="email" class="form-control mt-2" name="email" id="mail" placeholder="Email" required>
                            <label for="mail">Email</label>
                        </div>
                        <div class="form-floating">
                            <input type="tel" class="form-control mt-2" name="phone" id="phone" placeholder="Phone" required>
                            <label for="phone">Phone</label>
                        </div>
                        <div class="form-floating">
                            <input type="text" class="form-control mt-2" name="street" id="street" placeholder="Street" required>
                            <label for="street">Street</label>
                        </div>
                        <div class="form-floating">
                            <input type="text" class="form-control mt-2" name="city" id="city" placeholder="City" required>
                            <label for="city">City</label>
                        </div>
                        <div class="form-floating">
                            <input type="text" class="form-control mt-2" name="state" id="state" placeholder="State" required>
                            <label for="state">State</label>
                        </div>
                        <div class="form-floating">
                            <input type="number" class="form-control mt-2" name="zipcode" id="zip" min="100000" max="999999" placeholder="Zip Code" required>
                            <label for="zip">Zip Code</label>
                        </div>

                        <input type="submit" class="w-100 btn btn-lg btn-dark mt-4" name="adduser" value="Add User">
                        <button class="w-100 btn btn-lg btn-dark mt-2" onclick="location.href='adduser.php'; return false">Clear</button>
                    </form>
                </div>
            </div>
        </div>     
        <div class="pt-5">
            <button class="fixed-bottom w-100 btn btn-lg btn-dark mt-4" onclick="location.href='users.php'; return false">Back</button>
        </div>
    </main>
</body>
</html>