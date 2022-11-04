<?php
    session_start();
    if(!(isset($_SESSION['adminname']))) {
        $_SESSION['message'] = 'You must be logged in to view this page';
        header('Location: login.php');
        return;
    }
    require_once 'connect.php';
    $query = "SELECT order_id, product_name, quantity, date, time, status FROM orders order by date desc, time desc";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $status_map = array('P' => 'Pending', 'C' => 'Confirmed', 'D' => 'Cancelled', 'R' => 'Rejected');
    
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
    <header>Order Management</header>
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
                <th>Order ID</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
                <th colspan="3">Actions</th>
            </tr>
            <?php
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr><td>";
                    echo($row['order_id']);
                    echo "</td><td>";
                    echo($row['product_name']);
                    echo("</td><td>");
                    echo($row['quantity']);
                    echo("</td><td>");
                    echo($row['date']);
                    echo("</td><td>");
                    echo($row['time']);
                    echo("</td><td>");
                    echo($status_map[$row['status']]);
                    echo("</td><td>");
                    echo('<form action="orderdetails.php" method="get">
                                <input type="hidden" name="order_id" value="'.$row['order_id'].'">
                                <input type="submit" name="view" value="View">
                            </form>');
                    echo("</td><td>");
                    if ($row['status'] == 'P') {
                        echo('<form action="confirmorder.php" method="post">
                                <input type="hidden" name="order_id" value="'.$row['order_id'].'">
                                <input type="submit" name="confirm" value="Confirm">
                            </form>');
                    }
                    echo("</td><td>");
                    if ($row['status'] == 'P') {
                        echo('<form action="rejectorder.php" method="post">
                                <input type="hidden" name="order_id" value="'.$row['order_id'].'">
                                <input type="submit" name="reject" value="Reject">
                            </form>');
                    }
                    echo("</td></tr>");
                }
            ?>
        </table>
    </div>
    <div>
        <button onclick="location.href='dashboard.php'; return false;">Back to Home</button>
    </div> 

</body>
</html>