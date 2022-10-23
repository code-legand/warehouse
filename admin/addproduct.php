<?php
    session_start();
    if(!(isset($_SESSION['adminname']))) {
        $_SESSION['message'] = 'You must be logged in to view this page';
        header('Location: login.php');
        return;
    }
    if (isset($_POST['product_name']) && isset($_POST['product_category']) && isset($_POST['product_price']) && isset($_POST['product_quantity']) && isset($_POST['action']) && isset($_POST['action_date']) && isset($_POST['block_no']) && isset($_POST['row_no'])) {
        $product_name = $_POST['product_name'];
        $product_category = $_POST['product_category'];
        $product_price = $_POST['product_price'];
        $product_quantity = $_POST['product_quantity'];
        $action = $_POST['action'];
        $action_date = $_POST['action_date'];
        $block_no = $_POST['block_no'];
        $row_no = $_POST['row_no'];

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
        
        if (isset($_FILES['product_image'])) {
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
                        $product_image_name_new = uniqid('', true).".".$product_image_actual_ext;
                        $current_directory = getcwd();
                        $product_image_destination = str_replace('admin', 'uploads/images/', $current_directory).$product_image_name_new;
                        move_uploaded_file($product_image_tmp_name, $product_image_destination);
                        $product_image = $product_image_destination;
                    }
                    else {
                        $_SESSION['message'] = "file size should be less than 1 MB!";
                        header('Location: addproduct.php');
                        return;
                    }
                } 
                else {
                    $_SESSION['message'] = "There was an error uploading your image!";
                    header('Location: addproduct.php');
                    return;
                }
            } 
            else {
                $_SESSION['message'] = "You cannot upload files of this type!";
                header('Location: addproduct.php');
                return;
            }
        }

        if(isset($_POST['product_desc'])){
            $product_desc = $_POST['product_desc'];
        }
        else{
            $product_desc = "";
        }

        $details['product_image'] = $product_image;
        $details['product_desc'] = $product_desc;

        $details = json_encode($details);

        require_once 'connect.php';
        $query = "select admin_id from admins where admin_name = '".$_SESSION['adminname']."'";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        $query = "INSERT INTO storage (admin_id, product_name, category, description, price, action, action_date, quantity, block_no, row_no) VALUES (:admin_id, :product_name, :product_category, :description, :price, :action, :action_date, :quantity, :block_no, :row_no)";
        $stmt=$pdo->prepare($query);
        $stmt->execute(array(
            ':admin_id' => $admin['admin_id'],
            ':product_name' => $product_name,
            ':product_category' => $product_category,
            ':description' => $details,
            ':price' => $product_price,
            ':action' => $action,
            ':action_date' => $action_date,
            ':quantity' => $product_quantity,
            ':block_no' => $block_no,
            ':row_no' => $row_no
        ));
        $_SESSION['message'] = "Product added successfully!";
        header('Location: addproduct.php');
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
    <header>Add Product</header>
    <div id="msg">
        <?php 
           if (isset($_SESSION['message'])) {
            echo $_SESSION['message'];
            unset($_SESSION['message']);
            } 
        ?>
    </div>
    <div>
        <form action="addproduct.php" method="POST" enctype="multipart/form-data">
            <p>
                <label for="p_name">Product Name: </label>
                <input type="text" name="product_name" id="p_name" required>
            </p>
            <p>
                <label for="cat">Product Category:</label>
                <select name="product_category" id="cat" required>
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
                <input type="number" name="product_price" id="p_price" min="0" required>
            </p>
            <p>
                <label for="p_quantity">Product Quantity: </label>
                <input type="number" name="product_quantity" id="p_quantity" min="0" required>
            </p>
            <p>
                <div>
                    Product Details
                    <div>
                        <p>
                            <label for="attr1">1.</label>
                            <input type="text" name="desc_attr1" id="attr1" placeholder="Attribute 1"> : 
                            <input type="text" name="desc_val1" id="val1" placeholder="Value 1">
                        </p>
                        <p>
                            <label for="attr2">2.</label>
                            <input type="text" name="desc_attr2" id="attr2" placeholder="Attribute 2"> : 
                            <input type="text" name="desc_val2" id="val2" placeholder="Value 2">
                        </p>
                        <p>
                            <label for="attr3">3.</label>
                            <input type="text" name="desc_attr3" id="attr3" placeholder="Attribute 3"> : 
                            <input type="text" name="desc_val3" id="val3" placeholder="Value 3">
                        </p>
                        <p>
                            <label for="attr4">4.</label>
                            <input type="text" name="desc_attr4" id="attr4" placeholder="Attribute 4"> : 
                            <input type="text" name="desc_val4" id="val4" placeholder="Value 4">
                        </p>
                        <p>
                            <label for="attr5">5.</label>
                            <input type="text" name="desc_attr5" id="attr5" placeholder="Attribute 5"> : 
                            <input type="text" name="desc_val5" id="val5" placeholder="Value 5">
                        </p>
                    </div>
                </div>
                <div>
                    Additional Details
                    <div>
                        <p>
                            <label for="p_image">Product Image: </label>
                            <input type="file" name="product_image" id="p_image">
                        </p>
                        <p>
                            <label for="p_desc">Product Description: </label><br>
                            <textarea name="product_desc" id="p_desc" cols="30" rows="10"></textarea>
                        </p>
                    </div>
                </div>
            </p>
            <p>
                <label for="act">Action: </label>
                <select name="action" id="act" required>
                    <option value="A">Add</option>
                    <option value="R">Remove</option>
                </select>
            </p>
            <p>
                <label for="act_date">Action-date: </label>
                <input type="date" name="action_date" id="act_date" required>
            </p>
            <p>
                <label for="blk_no">Block-number: </label>
                <input type="number" name="block_no" id="blk_no" min="0" required>
            </p>
            <p>
                <label for="row_no">Row-number: </label>
                <input type="number" name="row_no" id="row_no" min="0" required>
            </p>
            <p>
                <input type="submit" value="Add Product">
                <button onclick="location.href='addproducts.php'; return false;">Clear</button>
            </p>
        </form>
    </div>
    <div>
        <button onclick="location.href='products.php'; return false;">Back</button>
    </div>

</body>
</html>