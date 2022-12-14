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
            <h1 class="h3 my-3 fw-normal">Order Management</h1>
        </div>
        <div class="container px-0 scroll-enable">  
            <table class="table p-2 table-striped table-hover">
                <thead class="table-dark">
                    <tr class="text-center">
                        <th>Order ID</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th colspan="3">Actions</th>
                    </tr>
                </thead>
                <tbody class="align-middle">
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
                            echo('<form action="orderdetails.php" method="get">
                                        <input type="hidden" name="order_id" value="'.$row['order_id'].'">
                                        <input type="submit" class="w-100 btn btn-lg btn-dark" name="view" value="View">
                                    </form>');
                            echo("</td>");
                            if ($row['status'] == 'P') {
                                echo('<td>
                                        <form action="confirmorder.php" method="post">
                                            <input type="hidden" name="order_id" value="'.$row['order_id'].'">
                                            <input type="submit" class="w-100 btn btn-lg btn-dark" name="confirm" value="Confirm">
                                        </form>
                                    </td>
                                    <td>
                                        <form action="rejectorder.php" method="post">
                                            <input type="hidden" name="order_id" value="'.$row['order_id'].'">
                                            <input type="submit" class="w-100 btn btn-lg btn-dark" name="reject" value="Reject">
                                        </form>
                                    </td>');
                            }
                            else {
                                echo('<td colspan="2">'.$status_map[$row['status']].'</td>');
                            }
                            echo("</tr>");
                        }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="pt-5">
            <button class="fixed-bottom w-100 btn btn-lg btn-dark mt-4"
                onclick="location.href='dashboard.php'; return false;">Back to Home</button>
        </div>

    </main>
</body>

</html>