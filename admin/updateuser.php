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
    <div>
       <?php 
        if(isset($_SESSION['message'])) {
            echo "<p>".$_SESSION['message']."</p>";
            unset($_SESSION['message']); 
        }
       ?>
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
</body>
</html>