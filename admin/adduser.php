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
    <header>Add User</header>  
    <div>
        <form action="adduser.php" method="post">
            <p>
                <label for="uname">User Name: </label>
                <input type="text" name="username" id="uname" required>
            </p>
            <p>
                <label for="pass">Password: </label>
                <input type="password" name="passwd" id="pass" required>
            </p>
            <p>
                <label for="mail">Email: </label>
                <input type="email" name="email" id="mail" required>
            </p>
            <p>
                <label for="phone">Phone: </label>
                <input type="tel" name="phone" id="phone" required>
            </p>
            <p>
                <label for="street">Street: </label>
                <input type="text" name="street" id="street" required>
            </p>
            <p>
                <label for="city">City: </label>
                <input type="text" name="city" id="city" required>
            </p>
            <p>
                <label for="state">State: </label>
                <input type="text" name="state" id="state" required>
            </p>
            <p>
                <label for="zip">Zip Code: </label>
                <input type="number" name="zipcode" id="zip" min="100000" max="999999" required>
            </p>
            <p>
                <input type="submit" name="adduser" value="Add User">
                <button onclick="location.href='adduser.php'; return false">Clear</button>
            </p>
        </form>
    </div> 
    <div>
        <button onclick="location.href='users.php'; return false">Back</button>
    </div>
</body>
</html>