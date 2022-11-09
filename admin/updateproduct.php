<?php
    session_start();
    if(!(isset($_SESSION['adminname']))) {
        $_SESSION['message'] = 'You must be logged in to view this page';
        header('Location: login.php');
        return;
    }

    if(isset($_POST['update_confirm']) && $_POST['update_confirm'] == 1){
        if (isset($_POST['product_name']) && isset($_POST['product_category']) && isset($_POST['product_price']) && isset($_POST['product_quantity']) && isset($_POST['block_no']) && isset($_POST['row_no'])) {
            $product_name = $_POST['product_name'];
            $product_category = $_POST['product_category'];
            $product_price = $_POST['product_price'];
            $product_quantity = $_POST['product_quantity'];
            $block_no = $_POST['block_no'];
            $row_no = $_POST['row_no'];
            $action = 'U';
            date_default_timezone_set('Asia/Kolkata');
            $now = new DateTime();
            $action_date = $now->format('Y-m-d');
    
            $details=array();
            if (isset($_POST['desc_attr1']) && isset($_POST['desc_val1'])) {
                if(!($_POST['desc_attr1'])=="" && !($_POST['desc_val1'])=="") {
                    $details[$_POST['desc_attr1']]=$_POST['desc_val1'];
                 }
            }
            if (isset($_POST['desc_attr2']) && isset($_POST['desc_val2'])) {
                if(!($_POST['desc_attr2'])=="" && !($_POST['desc_val2'])=="") {
                    $details[$_POST['desc_attr2']]=$_POST['desc_val2'];
                 }
            }
            if (isset($_POST['desc_attr3']) && isset($_POST['desc_val3'])) {
                if(!($_POST['desc_attr3'])=="" && !($_POST['desc_val3'])=="") {
                    $details[$_POST['desc_attr3']]=$_POST['desc_val3'];
                 }
            }
            if (isset($_POST['desc_attr4']) && isset($_POST['desc_val4'])) {
                if(!($_POST['desc_attr4'])=="" && !($_POST['desc_val4'])=="") {
                    $details[$_POST['desc_attr4']]=$_POST['desc_val4'];
                 }
            }
            if (isset($_POST['desc_attr5']) && isset($_POST['desc_val5'])) {
                if(!($_POST['desc_attr5'])=="" && !($_POST['desc_val5'])=="") {
                    $details[$_POST['desc_attr5']]=$_POST['desc_val5'];
                 }
            }
            
            require_once 'connect.php';
            $query = "SELECT description FROM storage WHERE storage_id = :storage_id";
            $stmt=$pdo->prepare($query);
            $stmt->execute(array(':storage_id' => $_SESSION['storage_id']));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $details = $row['description'];
            $details = json_decode($details, true);
            $product_image_destination = $details['product_image'];
            if (isset($_FILES['product_image']) && $_FILES['product_image']['tmp_name'] != '') {
                $product_image = $_FILES['product_image'];
                $product_image_name = $product_image['name'];
                $product_image_tmp_name = $product_image['tmp_name'];
                $product_image_size = $product_image['size'];
                $product_image_error = $product_image['error'];
                $product_image_type = $product_image['type'];
                $product_image_ext = explode('.', $product_image_name);
                $product_image_actual_ext = strtolower(end($product_image_ext));
                $allowed = array('jpg', 'jpeg', 'png');
                if (in_array($product_image_actual_ext, $allowed)) {
                    if ($product_image_error === 0) {
                        if ($product_image_size < 1000000) {
                            if ($product_image_destination == false){
                                $product_image_name_new = uniqid('', true).".".$product_image_actual_ext;
                                $current_directory = getcwd();
                                $product_image_destination = str_replace('admin', 'uploads/images/', $current_directory).$product_image_name_new;
                            }
                            move_uploaded_file($product_image_tmp_name, $product_image_destination);
                        }
                        else {
                            $_SESSION['message'] = "Update Failed! file size should be less than 1 MB!";
                            header('Location: updateproduct.php');
                            return;
                        }
                    } 
                    else {
                        $_SESSION['message'] = "Update Failed! There was an error uploading your image!";
                        header('Location: updateproduct.php');
                        return;
                    }
                } 
                else {
                    $_SESSION['message'] = "Update failed! You cannot upload files of this type!";
                    header('Location: updateproduct.php');
                    return;
                }
            }
            $product_image = $product_image_destination;
            $details['product_image'] = $product_image;
    
            if(isset($_POST['product_desc'])){
                $product_desc = $_POST['product_desc'];
            }
            else{
                $product_desc = "";
            }
            $details['product_desc'] = $product_desc;
    
            $details = json_encode($details);
    
            require_once 'connect.php';
            $query = "select admin_id from admins where admin_name = '".$_SESSION['adminname']."'";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);
            $query = "update storage set admin_id = :admin_id, product_name = :product_name, category = :product_category, description = :description, price = :product_price, action = :action, action_date = :action_date, quantity = :product_quantity, block_no = :block_no, row_no = :row_no where storage_id = :storage_id";
            $stmt = $pdo->prepare($query);
            $stmt->execute(array(
                ':admin_id' => $admin['admin_id'],
                ':product_name' => $product_name,
                ':product_category' => $product_category,
                ':description' => $details,
                ':product_price' => $product_price,
                ':action' => $action,
                ':action_date' => $action_date,
                ':product_quantity' => $product_quantity,
                ':block_no' => $block_no,
                ':row_no' => $row_no,
                ':storage_id' => $_SESSION['storage_id']
            ));
            $_SESSION['message'] = "Product updated successfully!";
            header('Location: updateproduct.php');
            return;        
        }
    }

    if (isset($_POST['storage_id'])) {
        $_SESSION['storage_id'] = $_POST['storage_id'];
        header('Location: updateproduct.php');
        return;
    }
    
    if (isset($_SESSION['storage_id'])) {
        require_once 'connect.php';
        $query = "SELECT * FROM storage WHERE storage_id = :storage_id";
        $stmt=$pdo->prepare($query);
        $stmt->execute(array(':storage_id' => $_SESSION['storage_id']));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row === false) {
            $_SESSION['message'] = 'Bad value for storage_id';
            header('Location: products.php');
            return;
        }
        $storage_id = htmlentities($row['storage_id']);
        $admin_id = htmlentities($row['admin_id']);
        $product_name = htmlentities($row['product_name']);
        $category = htmlentities($row['category']);
        $price = htmlentities($row['price']);
        $quantity = htmlentities($row['quantity']);
        $action = htmlentities($row['action']);
        $action_date = htmlentities($row['action_date']);
        $block_no = htmlentities($row['block_no']);
        $row_no = htmlentities($row['row_no']);

        $details = $row['description'];
        $details = json_decode($details, true);
        $p_details = $details;
        $a_details = array_splice($p_details, -2);

        $spec_names=array_keys($p_details);
        $spec_values=array_values($p_details);
        while(count($spec_names)<5){
            array_push($spec_names, "");
            array_push($spec_values, "");
        }

        $product_desc = $a_details['product_desc'];
        $product_image_path = $a_details['product_image'];
        $product_image = explode('/', $product_image_path);
        $product_image = '/warehouse/uploads/images/'.end($product_image);
        
    } 
    else {
        $_SESSION['message'] = 'Missing storage_id';
        header('Location: products.php');
        return;
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
    <script src="/warehouse/js/scripts.js"></script>
    <title>Warehouse Management system</title>
</head>
<body>
    <header>Edit Product Information</header>
    <div id="msg">
        <?php 
           if (isset($_SESSION['message'])) {
            echo $_SESSION['message'];
            unset($_SESSION['message']);
            } 
        ?>
    </div>
    <div>
        <form action="updateproduct.php" method="POST" enctype="multipart/form-data">
            <p>
                <label for="p_name">Product Name: </label>
                <input type="text" name="product_name" id="p_name" required value="<?= $product_name ?>">
            </p>
            <p>
                <label for="cat">Product Category:</label>
                <select name="product_category" id="cat" required selected="<?= $category ?>">
                    <option value="Electronics">Electronics</option>
                    <option value="Clothing">Clothing</option>
                    <option value="Grocery">Grocery</option>
                    <option value="Stationary">Stationary</option>
                    <option value="Furniture">Furniture</option>
                    <option value="Others">Others</option>
                </select>
            </p>
            <p>
                <label for="p_price">Product Price: </label>
                <input type="number" name="product_price" id="p_price" min="0" required value="<?= $price ?>">
            </p>
            <p>
                <label for="p_quantity">Product Quantity: </label>
                <input type="number" name="product_quantity" id="p_quantity" min="0" required value="<?= $quantity ?>">
            </p>
            <p>
                <div>
                    Product Details
                    <div>
                        <p>
                            <label for="attr1">1.</label>
                            <input type="text" name="desc_attr1" id="attr1" placeholder="Attribute 1" value="<?= $spec_names[0] ?>"> : 
                            <input type="text" name="desc_val1" id="val1" placeholder="Value 1" value="<?= $spec_values[0] ?>">
                        </p>
                        <p>
                            <label for="attr2">2.</label>
                            <input type="text" name="desc_attr2" id="attr2" placeholder="Attribute 2" value="<?= $spec_names[1] ?>"> :  
                            <input type="text" name="desc_val2" id="val2" placeholder="Value 2" value="<?= $spec_values[1] ?>">
                        </p>
                        <p>
                            <label for="attr3">3.</label>
                            <input type="text" name="desc_attr3" id="attr3" placeholder="Attribute 3" value="<?= $spec_names[2] ?>"> : 
                            <input type="text" name="desc_val3" id="val3" placeholder="Value 3" value="<?= $spec_values[2] ?>">
                        </p>
                        <p>
                            <label for="attr4">4.</label>
                            <input type="text" name="desc_attr4" id="attr4" placeholder="Attribute 4" value="<?= $spec_names[3] ?>"> :  
                            <input type="text" name="desc_val4" id="val4" placeholder="Value 4" value="<?= $spec_values[3] ?>">
                        </p>
                        <p>
                            <label for="attr5">5.</label>
                            <input type="text" name="desc_attr5" id="attr5" placeholder="Attribute 5" value="<?= $spec_names[4] ?>"> : 
                            <input type="text" name="desc_val5" id="val5" placeholder="Value 5" value="<?= $spec_values[4] ?>">
                        </p>
                    </div>
                </div>
                <div>
                    Additional Details
                    <div>
                        <p>
                            <label for="p_image">Product Image: </label>
                            <input type="file" name="product_image" id="p_image"><br>
                            <figure class="updateproduct_figure">
                                <figcaption>Image preview</figcaption>
                                <img src="<?= $product_image ?>" alt="<?= $product_name ?>" class="updateproduct_image" id="product_image"> 
                            </figure>
                        </p>
                        <p>
                            <label for="p_desc">Product Description: </label><br>
                            <textarea name="product_desc" id="p_desc" cols="30" rows="10"><?= $product_desc ?></textarea>
                        </p>
                    </div>
                </div>
            </p>
            
            <p>
                <label for="blk_no">Block-number: </label>
                <input type="number" name="block_no" id="blk_no" min="0" required value="<?= $block_no ?>">
            </p>
            <p>
                <label for="row_no">Row-number: </label>
                <input type="number" name="row_no" id="row_no" min="0" required value="<?= $row_no ?>">
            </p>
            <p>
                <input type="hidden" name="update_confirm" id="update_confirm" value="0">
                <input type="submit" value="Update Product" onclick="update_confirm.value = '1';">
                <button onclick="location.href='updateproduct.php'; return false;">Reset</button>
            </p>
        </form>
    </div>
    <div>
        <button onclick="location.href='products.php'; return false;">Back</button>
    </div>

</body>
</html>