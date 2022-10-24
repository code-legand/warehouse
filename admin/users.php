<?php
    session_start();
    if(!(isset($_SESSION['adminname']))) {
        $_SESSION['message'] = 'You must be logged in to view this page';
        header('Location: login.php');
        return;
    }
    require_once 'connect.php';
    $query = "SELECT storage_id, product_name, category, price, quantity, block_no, row_no FROM storage";
    $stmt=$pdo->prepare($query);
    $stmt->execute();
    $stmt->
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
        <div>
            <button onclick="location.href='addproduct.php';"">Add Product</button>
        </div>
        <table>
            <tr>
                <th>Storage ID</th>
                <th>Product Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Block No</th>
                <th>Row No</th>
                <th colspan="3">Operations</th>
            </tr>
            <?php
                while($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr><td>";
                    echo($row['storage_id']);
                    echo "</td><td>";
                    echo($row['product_name']);
                    echo "</td><td>";
                    echo($row['category']);
                    echo "</td><td>";
                    echo($row['price']);
                    echo "</td><td>";
                    echo($row['quantity']);
                    echo "</td><td>";
                    echo($row['block_no']);
                    echo "</td><td>";
                    echo($row['row_no']);
                    echo "</td><td>";
                    echo '<form action="productdetails.php">
                            <input type="hidden" name="storage_id" value="'.$row['storage_id'].'">
                            <input type="submit" value="View">
                            </form>';
                    echo "</td><td>";
                    echo '<form action="updateproduct.php" method="POST">
                            <input type="hidden" name="storage_id" value="'.$row['storage_id'].'">
                            <input type="submit" value="Edit">
                            </form>';
                    echo "</td><td>";
                    echo '<form action="deleteproduct.php" method="POST">
                            <input type="hidden" name="storage_id" value="'.$row['storage_id'].'">
                            <input type="submit" value="Delete">
                            </form>';
                    echo "</td></tr>";
                }
            ?>
        </table>
    </div>
    <div>
        <button onclick="location.href='dashboard.php'; return false;">Back to Home</button>
    </div> 

</body>
</html>