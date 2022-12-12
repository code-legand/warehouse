<?php
    session_start();
    if(!(isset($_SESSION['adminname']))) {
        $_SESSION['message'] = 'You must be logged in to view this page';
        header('Location: login.php');
        return;
    }
    require_once 'connect.php';
    $query = "SELECT user_id, user_name, phone, email FROM users";
    $stmt=$pdo->prepare($query);
    $stmt->execute();
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
<!-- <body>
    <header>User Management</header>
    <div id="msg">
        
    </div>
    <div>
        <div>
            <button onclick="location.href='adduser.php';">Add User</button>
        </div>
        <table>
            <tr> -->

<body class="text-center d-flex justify-content-center">
    <main class="p-5 m-auto scroll-enable">
        <?php 
            if (isset($_SESSION['message'])) {
            echo ('<div id="msg" class="alert alert-warning alert-dismissible fade show" role="alert">'.
                    $_SESSION['message'].
                    '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>'.
                '</div>');      
            unset($_SESSION['message']);
            } 
        ?>

        <div>
            <h1 class="h3 my-3 fw-normal">User Management</h1>
            <div>
                <button class="w-100 btn btn-lg btn-dark mb-4" onclick="location.href='adduser.php';">Add User</button>
            </div>
        </div>

        <div class="container px-0 scroll-enable">
            <table class="table p-2 table-striped table-hover">
                <thead class="table-dark">
                    <tr class="text-center">
                        <th>User ID</th>
                        <th>User Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th colspan="3">Operations</th>
                    </tr>
                </thead>
                <tbody class="align-middle">
                    <?php
                        while($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr><td>";
                            echo($row['user_id']);
                            echo "</td><td>";
                            echo($row['user_name']);
                            echo "</td><td>";
                            echo($row['phone']);
                            echo "</td><td>";
                            echo($row['email']);
                            echo "</td><td>";
                            echo '<form action="userdetails.php">
                                    <input type="hidden" name="user_id" value="'.$row['user_id'].'">
                                    <input type="submit" class="w-100 btn btn-lg btn-dark" value="View">
                                    </form>';
                            echo "</td><td>";
                            echo '<form action="updateuser.php" method="POST">
                                    <input type="hidden" name="user_id" value="'.$row['user_id'].'">
                                    <input class="w-100 btn btn-lg btn-dark" type="submit" value="Edit">
                                    </form>';
                            echo "</td><td>";
                            echo '<form action="deleteuser.php" method="POST">
                                    <input type="hidden" name="user_id" value="'.$row['user_id'].'">
                                    <input class="w-100 btn btn-lg btn-dark" type="submit" value="Delete">
                                    </form>';
                            echo "</td></tr>";
                        }
                    ?>
                </tbody class="align-middle">
            </table>
        </div>
        <div class="pt-5">
            <button class="fixed-bottom w-100 btn btn-lg btn-dark mt-4"
                onclick="location.href='dashboard.php'; return false;">Back to Home</button>
        </div>
    </main>
</body>
</html>