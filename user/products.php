<?php
    session_start();
    if(!(isset($_SESSION['username']))) {
        $_SESSION['message'] = 'You must be logged in to view this page';
        header('Location: login.php');
        return;
    }
    require_once 'connect.php';
    $query = "SELECT storage_id, product_name, category, price, quantity, description FROM storage;";
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
        <div class="col-12">
            <h1 class="h3 mb-3 fw-normal">Products</h1>
        </div>
        <div class="album py-5 bg-light">
            <div class="container">
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                    <?php
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            $details = $row['description'];
                            $details = json_decode($details, true);
                            $product_image_path = $details['product_image'];
                            $product_image = explode('/', $product_image_path);
                            $product_image = '/warehouse/uploads/images/'.end($product_image);
                            echo '<div class="col">
                                    <div class="card shadow-sm h-100">
                                        <div class="justify-content-center align-items-center">
                                            <img src="'.$product_image.'" alt="product image" width="225" height="225" class="p-4">
                                        </div>
                                        <div class="card-body d-flex align-items-center flex-column mb-3">
                                            <div class="p-2 card-text">'.$row['product_name'].'</div>
                                            <div class="mt-auto p-2 d-flex justify-content-between align-items-center">
                                                <form action="productdetails.php" method="get">
                                                    <input type="hidden" name="storage_id" value="'.$row['storage_id'].'">
                                                    <input class="btn btn-lg btn-dark btn-outline-light" type="submit" value="View">
                                                </form>
                                                <form action="placeorder.php" method="post">
                                                    <input type="hidden" name="storage_id" value="'.$row['storage_id'].'">
                                                    <input class="btn btn-lg btn-dark btn-outline-light" type="submit" value="Order">
                                                </form>   
                                            </div>
                                            <strong class="text-muted">₹'.$row['price'].'</strong>
                                        </div>   
                                    </div>
                                </div>';
                        }
                    ?>
                </div>
            </div>
        </div>
        <div class="pt-5">
            <button class="fixed-bottom w-100 btn btn-lg btn-dark mt-4" onclick="location.href='dashboard.php'; return false;">Dashboard</button>
        </div>
    </main>
</body>
</html>
                    

<!-- <body class="text-center d-flex justify-content-center">
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
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 class="h3 mb-3 fw-normal">Products</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Product Image</th>
                                <th>Product Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th colspan="2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    $details = $row['description'];
                                    $details = json_decode($details, true);
                                    $product_image_path = $details['product_image'];
                                    $product_image = explode('/', $product_image_path);
                                    $product_image = '/warehouse/uploads/images/'.end($product_image);

                                    echo "<tr><td>";
                                    echo '<img src="'.$product_image.'" alt="product image" width="100" height="100">';
                                    echo "</td><td>";
                                    echo($row['product_name']);
                                    echo "</td><td>";
                                    echo($row['category']);
                                    echo "</td><td>";
                                    echo($row['price']);
                                    echo "</td><td>";
                                    echo($row['quantity'] - ($blocked_quantity[$row['storage_id']] ?? 0));      //null collaesce operator
                                    
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
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</body>
</html> -->
<!-- <body>
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
                <th>Product Image</th>
                <th>Product Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Quantity</th>
                <th colspan="2">Actions</th>
            </tr>
            <?php
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $details = $row['description'];
                    $details = json_decode($details, true);
                    $product_image_path = $details['product_image'];
                    $product_image = explode('/', $product_image_path);
                    $product_image = '/warehouse/uploads/images/'.end($product_image);

                    echo "<tr><td>";
                    echo '<img src="'.$product_image.'" alt="product image" width="100" height="100">';
                    echo "</td><td>";
                    echo($row['product_name']);
                    echo "</td><td>";
                    echo($row['category']);
                    echo "</td><td>";
                    echo($row['price']);
                    echo "</td><td>";
                    echo($row['quantity'] - ($blocked_quantity[$row['storage_id']] ?? 0));      //null collaesce operator
                    
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
</html> -->