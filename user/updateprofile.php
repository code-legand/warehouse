<?php
    session_start();
    if(!(isset($_SESSION['username']))) {
        $_SESSION['message'] = 'You must be logged in to view this page';
        header('Location: login.php');
        return;
    }
    if (isset($_POST['update_confirm']) && $_POST['update_confirm'] == '1'){
        if(isset($_POST['username']) && isset($_POST['email']) && isset($_POST['phone']) && isset($_POST['street']) && isset($_POST['city']) && isset($_POST['state']) && isset($_POST['zipcode'])){
            $username = $_POST['username'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $street = $_POST['street'];
            $city = $_POST['city'];
            $state = $_POST['state'];
            $zip_code = $_POST['zipcode'];
            require_once 'connect.php';
            $query = "SELECT user_name FROM users WHERE user_name = :username";
            $stmt = $pdo->prepare($query);
            $stmt->execute(array(':username' => $username));
            if($stmt->rowCount() > 0 && $username != $_SESSION['username']) {
                $_SESSION['message'] = 'Username already exists';
                header('Location: updateprofile.php');
                return;
            }
            else{
                $query = "UPDATE users SET user_name = :newusername, email = :email, phone = :phone, street = :street, city = :city, state = :state, zip_code = :zip_code WHERE user_name = :currentusername;";
                $stmt = $pdo->prepare($query);
                $stmt->execute(array(
                    ':newusername' => $username,
                    ':email' => $email,
                    ':phone' => $phone,
                    ':street' => $street,
                    ':city' => $city,
                    ':state' => $state,
                    ':zip_code' => $zip_code,
                    ':currentusername' => $_SESSION['username']
                ));
                $_SESSION['username'] = $username;
                $_SESSION['message'] = 'User updated successfully';
                header('Location: updateprofile.php');
                return;
            } 
        }
    }
    require_once 'connect.php';
    $query = "SELECT * FROM users WHERE user_name = :username";
    $stmt = $pdo->prepare($query); 
    $stmt->execute(array(':username' => $_SESSION['username']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $user_name = htmlentities($row['user_name']);
    $email = htmlentities($row['email']);
    $phone = htmlentities($row['phone']);
    $street = htmlentities($row['street']);
    $city = htmlentities($row['city']);
    $state = htmlentities($row['state']);
    $zip_code = htmlentities($row['zip_code']);
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
                    <form action="updateprofile.php" method="post">
                        <h1 class="h3 mb-3 fw-normal">Update Profile</h1>
                        <div class="form-floating">
                            <input type="text" class="form-control" name="username" id="uname" required
                                value="<?= $user_name ?>" autofocus>
                            <label for="uname">User Name: </label>
                        </div>
                        <div class="form-floating mt-2">
                            <input type="email" class="form-control" name="email" id="mail" required
                                value="<?= $email ?>">
                            <label for="mail">Email: </label>
                        </div>
                        <div class="form-floating mt-2">
                            <input type="tel" class="form-control" name="phone" id="phone" required
                                value="<?= $phone ?>">
                            <label for="phone">Phone: </label>
                        </div>
                        <div class="form-floating mt-2">
                            <input type="text" class="form-control" name="street" id="street" required
                                value="<?= $street ?>">
                            <label for="street">Street: </label>
                        </div>
                        <div class="form-floating mt-2">
                            <input type="text" class="form-control" name="city" id="city" required value="<?= $city ?>">
                            <label for="city">City: </label>
                        </div>
                        <div class="form-floating mt-2">
                            <input type="text" class="form-control" name="state" id="state" required
                                value="<?= $state ?>">
                            <label for="state">State: </label>
                        </div>
                        <div class="form-floating mt-2">
                            <input type="number" class="form-control" name="zipcode" id="zip" min="100000" max="999999"
                                required value="<?= $zip_code ?>">
                            <label for="zip">Zip Code: </label>
                        </div>

                        <input type="hidden" name="update_confirm" id="update_confirm" value="0">
                        <input type="submit" class="w-100 btn btn-lg btn-dark mt-4" name="update" value="Update"
                            onclick="update_confirm.value='1';">
                        <button class="w-100 btn btn-lg btn-dark mt-2 mb-4"
                            onclick="location.href='updateprofile.php'; return false">Reset</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="pt-5">
            <button class="fixed-bottom w-100 btn btn-lg btn-dark mt-4"
                onclick="location.href='dashboard.php'; return false;">Dashboard</button>
        </div>
    </main>
</body>
</html>