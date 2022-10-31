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
    echo $_SESSION['username'];
    echo '<pre>';
    print_r($row);
    echo '</pre>';
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
    <link rel="stylesheet" href="#">
    <link rel="font" href="">
    <link rel="apple-touch-icon" sizes="180x180" href="/warehouse/img/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/warehouse/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/warehouse/img/favicon-16x16.png">
    <link rel="manifest" href="/warehouse/img/site.webmanifest">
    <script src="#"></script>
    <title>Warehouse Management system</title>
</head>
<body>
    <header>Update Profile</header>
    <div id="msg">
        <?php 
           if (isset($_SESSION['message'])) {
            echo $_SESSION['message'];
            unset($_SESSION['message']);
            } 
        ?>
    </div>
    <div>
        <form action="updateprofile.php" method="post">
            <p>
                <label for="uname">User Name: </label>
                <input type="text" name="username" id="uname" required value="<?= $user_name ?>">
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
                <button onclick="location.href='updateprofile.php'; return false">Reset</button>
            </p>
        </form>
    </div>
    <div>
        <button onclick="location.href='profile.php'; return false">Back</button>
    </div>   
</body>
</html>