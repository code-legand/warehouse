<?php
    session_start();
    if(!(isset($_SESSION['username']))) {
        $_SESSION['message'] = 'You must be logged in to view this page';
        header('Location: login.php');
        return;
    }
    require_once 'connect.php';
    $query = "SELECT user_id FROM users WHERE user_name = :username";
    $stmt = $pdo->prepare($query);
    $stmt->execute(array(':username' => $_SESSION['username']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $user_id = $row['user_id'];
    $query = "SELECT storage_id, order_id, product_name, quantity, date, time, status FROM orders WHERE user_id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(array(':user_id' => $user_id));
    $status_map = array('P' => 'Pending','C' => 'Confirmed','D' => 'Cancelled', 'R' => 'Rejected');   
    //D = cancelled/Dropped by user, R = rejected by admin, C = confirmed by admin, P = pending/awaiting admin approval

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/warehouse/css/styles.css">
    <link rel="font" href="">
    <link rel="apple-touch-icon" sizes="180x180" href="/warehouse/img/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/warehouse/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/warehouse/img/favicon-16x16.png">
    <link rel="manifest" href="/warehouse/img/site.webmanifest">
    <script src="#"></script>
    <title>Warehouse Management system</title>
</head>
<body>
    <header>My Orders</header>
    <div id="msg">
        <?php 
           if (isset($_SESSION['message'])) {
            echo $_SESSION['message'];
            unset($_SESSION['message']);
            } 
        ?>
    </div>
    <div>
            <table>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                <?php
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr><td>";
                        echo($row['product_name']);
                        echo "</td><td>";
                        echo($row['quantity']);
                        echo "</td><td>";
                        echo($row['date']);
                        echo "</td><td>";
                        echo($row['time']);
                        echo "</td><td>";
                        echo($status_map[$row['status']]);
                        echo "</td><td>";
                        echo('<form action="productdetails.php" method="get">
                                <input type="hidden" name="storage_id" value="'.$row['storage_id'].'">
                                <input type="submit" value="View">
                            </form>');
                        echo "</td><td>";
                        if($row['status'] == 'P') {
                            echo('<form action="cancelorder.php" method="POST">
                                    <input type="hidden" name="order_id" value="'.$row['order_id'].'">
                                    <input type="submit" value="Cancel">
                                </form>');
                        }
                        else{
                            echo($status_map[$row['status']]);
                        }
                        echo "</td></tr>";
                    }
                ?>
            </table>
    </div>
    <div>
        <button onclick="location.href='dashboard.php'; return false;">Dashboard</button>
    </div> 
</body>
</html>