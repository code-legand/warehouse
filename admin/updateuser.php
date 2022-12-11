<?php
    session_start();
    if(!(isset($_SESSION['adminname']))) {
        $_SESSION['message'] = 'You must be logged in to view this page';
        header('Location: login.php');
        return;
    }

    if (isset($_POST['update_confirm']) && $_POST['update_confirm'] == '1'){
        if(isset($_POST['username']) && isset($_POST['passwd']) && isset($_POST['email']) && isset($_POST['phone']) && isset($_POST['street']) && isset($_POST['city']) && isset($_POST['state']) && isset($_POST['zipcode'])){
            $username = $_POST['username'];
            $passwd = $_POST['passwd'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $street = $_POST['street'];
            $city = $_POST['city'];
            $state = $_POST['state'];
            $zip_code = $_POST['zipcode'];
            require_once 'connect.php';
            $query = "SELECT user_id FROM users WHERE user_name = :username AND user_id != :userid";
            $stmt = $pdo->prepare($query);
            $stmt->execute(array(':username' => $username, ':userid' => $_POST['userid']));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if($stmt->rowCount() > 0) {
                $_SESSION['message'] = 'Username already exists';
                header('Location: updateuser.php');
                return;
            }
            else{
                $query = "UPDATE users SET user_name = :username, password = :passwd, email = :email, phone = :phone, street = :street, city = :city, state = :state, zip_code = :zip_code WHERE user_id = :user_id;";
                $stmt = $pdo->prepare($query);
                $stmt->execute(array(
                    ':username' => $username,
                    ':passwd' => $passwd,
                    ':email' => $email,
                    ':phone' => $phone,
                    ':street' => $street,
                    ':city' => $city,
                    ':state' => $state,
                    ':zip_code' => $zip_code,
                    ':user_id' => $_SESSION['user_id']
            ));
            }
            
            $_SESSION['message'] = 'User updated successfully';
            header('Location: updateuser.php');
            return;
        }
    }

    if(isset($_POST['user_id'])){
        $_SESSION['user_id'] = $_POST['user_id'];
        header('Location: updateuser.php');
        return;
    }

    if(isset($_SESSION['user_id'])){
        require_once 'connect.php';
        $query = "SELECT * FROM users WHERE user_id = :user_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(array(':user_id' => $_SESSION['user_id']));
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
                    <form action="updateuser.php" method="post">
                        <!-- <img class="mb-4" src="/warehouse/img/android-chrome-512x512.png" alt="logo-image" width="72"
                            height="57"> -->
                        <h1 class="h3 mb-3 fw-normal">Edit User Information</h1>


            <div class="form-floating">
                <input type="text" class="form-control mt-2" name="username" id="uname" placeholder="User Name" required value="<?= $user_name ?>">
                <label for="uname">User Name</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control mt-2" name="passwd" id="pass" placeholder="Password" required  value="<?= $password ?>">
                <label for="pass">Password</label>
            </div>
            <div class="form-floating">
                <input type="email" class="form-control mt-2" name="email" id="mail" placeholder="Email" required value="<?= $email ?>">
                <label for="mail">Email</label>
            </div>
            <div class="form-floating">
                <input type="tel" class="form-control mt-2" name="phone" id="phone" placeholder="Phone" required value="<?= $phone ?>">
                <label for="phone">Phone</label>
            </div>
            <div class="form-floating">
                <input type="text" class="form-control mt-2" name="street" id="street" placeholder="Street" required value="<?= $street ?>">
                <label for="street">Street</label>
            </div>
            <div class="form-floating">
                <input type="text" class="form-control mt-2" name="city" id="city" placeholder="City" required value="<?= $city ?>">
                <label for="city">City</label>
            </div>
            <div class="form-floating">
                <input type="text" class="form-control mt-2" name="state" id="state" placeholder="State" required value="<?= $state ?>">
                <label for="state">State</label>
            </div>
            <div class="form-floating">
                <input type="number" class="form-control mt-2" name="zipcode" id="zip" min="100000" max="999999" placeholder="Zip Code" required value="<?= $zip_code ?>">
                <label for="zip">Zip Code</label>
            </div>

            <input type="hidden" name="update_confirm" id="update_confirm" value="0">
            <input class="w-100 btn btn-lg btn-dark mt-4" type="submit" name="update" value="Update" onclick="update_confirm.value='1';">
            <button class="w-100 btn btn-lg btn-dark mt-2" onclick="location.href='updateuser.php'; return false">Reset</button>

        </form>
    </div> 
    <div class="pt-5">
        <button class="fixed-bottom w-100 btn btn-lg btn-dark mt-4" onclick="location.href='users.php'; return false">Back</button>
    </div>
</body>

<!-- <body>
    <div>
       
    </div>
    <header>Edit User Information</header>  
    <div>
        <form action="updateuser.php" method="post">
            <p>
                <label for="uname">User Name: </label>
                <input type="text" name="username" id="uname" required value="<?= $user_name ?>">
            </p>
            <p>
                <label for="pass">Password: </label>
                <input type="password" name="passwd" id="pass" required value="<?= $password ?>">
            </p>
            <p>
                <label for="mail">Email: </label>
                <input type="email" name="email" id="mail" required value="<?= $email ?>">
            </p>
            <p>
                <label for="phone">Phone: </label>
                <input type="tel" name="phone" id="phone" required value="<?= $phone ?>">
            </p>
            <p>
                <label for="street">Street: </label>
                <input type="text" name="street" id="street" required value="<?= $street ?>">
            </p>
            <p>
                <label for="city">City: </label>
                <input type="text" name="city" id="city" required value="<?= $city ?>">
            </p>
            <p>
                <label for="state">State: </label>
                <input type="text" name="state" id="state" required value="<?= $state ?>">
            </p>
            <p>
                <label for="zip">Zip Code: </label>
                <input type="number" name="zipcode" id="zip" min="100000" max="999999" required value="<?= $zip_code ?>">
            </p>
            <p>
                <input type="hidden" name="update_confirm" id="update_confirm" value="0">
                <input type="submit" name="update" value="Update" onclick="update_confirm.value='1';">
                <button onclick="location.href='updateuser.php'; return false">Reset</button>
            </p>
        </form>
    </div>
    <div>
        <button onclick="location.href='users.php'; return false">Back</button>
    </div>  
</body> -->
</html>