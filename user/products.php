<?php
    session_start();
    if(!(isset($_SESSION['username']))) {
        $_SESSION['message'] = 'You must be logged in to view this page';
        header('Location: login.php');
        return;
    }
    require_once 'connect.php';
    $query = "SELECT storage_id, product_name, category, price, quantity FROM storage;";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $query2 = "SELECT storage_id, sum(quantity) as blocked_quantity FROM orders where status = 'P' GROUP BY storage_id;";
    $stmt2 = $pdo->prepare($query2);
    $stmt2->execute();
    $blocked_quantity = array();
    while($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)){
        $blocked_quantity[$row2['storage_id']] = $row2['blocked_quantity'];
    }
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
    <header>Products</header>
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
                <th>Category</th>
                <th>Price</th>
                <th>Quantity</th>
                <th colspan="2">Actions</th>
            </tr>
            <?php
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr><td>";
                    echo($row['product_name']);
                    echo "</td><td>";
                    echo($row['category']);
                    echo "</td><td>";
                    echo($row['price']);
                    echo "</td><td>";
                    echo($row['quantity'] - $blocked_quantity[$row['storage_id']]);
                    echo "</td><td>";
                    echo('<form action="productdetails.php" method="get">
                            <input type="hidden" name="storage_id" value="'.$row['storage_id'].'">
                            <input type="submit" value="View">
                        </form>');
                    echo "</td><td>";
                    echo('<form action="placeorder.php" method="post">
                            <input type="hidden" name="storage_id" value="'.$row['storage_id'].'">
                            <input type="submit" value="Order">
                        </form>');
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