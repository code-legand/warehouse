<?php
    session_start();
    if(!(isset($_SESSION['adminname']))) {
        $_SESSION['message'] = 'You must be logged in to view this page';
        header('Location: login.php');
        return;
    }
    if (isset($_POST['product_name']) && isset($_POST['product_category']) && isset($_POST['product_price']) && isset($_POST['product_quantity']) && isset($_POST['block_no']) && isset($_POST['row_no'])) {
        $product_name = $_POST['product_name'];
        $product_category = $_POST['product_category'];
        $product_price = $_POST['product_price'];
        $product_quantity = $_POST['product_quantity'];
        $block_no = $_POST['block_no'];
        $row_no = $_POST['row_no'];
        $action = 'A';
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
    <header>Add Product</header>
    <div id="msg">
        
    </div>
    <div>
        <form action="addproduct.php" method="POST" enctype="multipart/form-data">
            <div>
                <label for="p_name">Product Name: </label>
                <input type="text" name="product_name" id="p_name" required>
            </div>
            <div>
                <label for="cat">Product Category:</label>
                <select name="product_category" id="cat" required>
                    <option value="Electronics">Electronics</option>
                    <option value="Clothing">Clothing</option>
                    <option value="Grocery">Grocery</option>
                    <option value="Stationary">Stationary</option>
                    <option value="Furniture">Furniture</option>
                    <option value="Others">Others</option>
                </select>
            </div>
            <div>
                <label for="p_price">Product Price: </label>
                <input type="number" name="product_price" id="p_price" min="0" required>
            </div>
            <div>
                <label for="p_quantity">Product Quantity: </label>
                <input type="number" name="product_quantity" id="p_quantity" min="0" required>
            </div>
            <div>
                <div>
                    Product Details
                    <div>
                        <div>
                            <label for="attr1">1.</label>
                            <input type="text" name="desc_attr1" id="attr1" placeholder="Attribute 1"> : 
                            <input type="text" name="desc_val1" id="val1" placeholder="Value 1">
                        </div>
                        <div>
                            <label for="attr2">2.</label>
                            <input type="text" name="desc_attr2" id="attr2" placeholder="Attribute 2"> : 
                            <input type="text" name="desc_val2" id="val2" placeholder="Value 2">
                        </div>
                        <div>
                            <label for="attr3">3.</label>
                            <input type="text" name="desc_attr3" id="attr3" placeholder="Attribute 3"> : 
                            <input type="text" name="desc_val3" id="val3" placeholder="Value 3">
                        </div>
                        <div>
                            <label for="attr4">4.</label>
                            <input type="text" name="desc_attr4" id="attr4" placeholder="Attribute 4"> : 
                            <input type="text" name="desc_val4" id="val4" placeholder="Value 4">
                        </div>
                        <div>
                            <label for="attr5">5.</label>
                            <input type="text" name="desc_attr5" id="attr5" placeholder="Attribute 5"> : 
                            <input type="text" name="desc_val5" id="val5" placeholder="Value 5">
                        </div>
                    </div>
                </div>
                <div>
                    Additional Details
                    <div>
                        <div>
                            <label for="p_image">Product Image: </label>
                            <input type="file" name="product_image" id="p_image">
                        </div>
                        <div>
                            <label for="p_desc">Product Description: </label><br>
                            <textarea name="product_desc" id="p_desc" cols="30" rows="10"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            
            <div>
                <label for="blk_no">Block-number: </label>
                <input type="number" name="block_no" id="blk_no" min="0" required>
            </div>
            <div>
                <label for="row_no">Row-number: </label>
                <input type="number" name="row_no" id="row_no" min="0" required>
            </div>
            <div>
                <input type="submit" value="Add Product">
                <button onclick="location.href='addproducts.php'; return false;">Clear</button>
            </div>
        </form>
    </div>
    <div>
        <button onclick="location.href='products.php'; return false;">Back</button>
    </div>

</body> -->

<body class="text-center d-flex justify-content-center">
    <main class="m-auto p-5">
        <?php 
            if (isset($_SESSION['message'])) {
            echo ('<div id="msg" class="alert alert-warning alert-dismissible fade show" role="alert">'.
                    $_SESSION['message'].
                    '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>'.
                '</div>');      
            unset($_SESSION['message']);
            } 
        ?>
        <div class="container ">
            <div class="row d-flex justify-content-center">
                <div class="col-12 col-md-10">
                    <form action="addproduct.php" method="POST" enctype="multipart/form-data">
                        <h1 class="h3 mb-3 fw-normal">Add Product</h1>
                        <div class="form-floating">
                            <input type="text" class="form-control mt-2" name="product_name" id="p_name"
                                placeholder="Product Name" required>
                            <label for="p_name">Product Name</label>
                        </div>
                        <div class="form-floating">
                            <input class="form-control mt-2" list="categoryOptions" id="cat"
                                placeholder="Product Category" required>
                            <label name="product_category" for="cat" class="form-label">Product Category</label>
                            <datalist id="categoryOptions" class="w-100">
                                <option value="Electronics">
                                <option value="Clothing">
                                <option value="Grocery">
                                <option value="Stationary">
                                <option value="Furniture">
                                <option value="Others">
                            </datalist>
                        </div>
                        <div class="form-floating">
                            <input type="number" class="form-control mt-2" name="product_price" id="p_price" min="0"
                                placeholder="Product Price" required>
                            <label for="p_price">Product Price</label>
                        </div>
                        <div class="form-floating">
                            <input type="number" class="form-control mt-2" name="product_quantity" id="p_quantity"
                                min="0" placeholder="Product Quantity" required>
                            <label for="p_quantity">Product Quantity</label>
                        </div>
                        <div>
                            <div>
                                <div class="mt-4">Product Details</div>
                                <div>
                                    <div class="container px-0">
                                        <!-- <label for="attr1">1.</label> -->
                                        <div class="row g-0 mt-2">
                                            <div class="col form-floating">
                                                <input type="text" class="form-control" name="desc_attr1"
                                                    id="attr1" placeholder="Attribute 1">
                                                <label for="attr1">Attribute 1</label>
                                            </div>
                                            <div class="col form-floating">
                                                <input type="text" class="form-control" name="desc_val1" id="val1"
                                                    placeholder="Value 1">
                                                <label for="val1">Value 1</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="container px-0">
                                        <!-- <label for="attr2">2.</label> -->
                                        <div class="row g-0 mt-2">
                                            <div class="col form-floating">
                                                <input type="text" class="form-control" name="desc_attr2"
                                                    id="attr2" placeholder="Attribute 2">
                                                <label for="attr2">Attribute 2</label>
                                            </div>
                                            <div class="col form-floating">
                                                <input type="text" class="form-control" name="desc_val2" id="val2"
                                                    placeholder="Value 2">
                                                <label for="val2">Value 2</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="container px-0">
                                        <!-- <label for="attr2">2.</label> -->
                                        <div class="row g-0 mt-2">
                                            <div class="col form-floating">
                                                <input type="text" class="form-control" name="desc_attr3"
                                                    id="attr3" placeholder="Attribute 3">
                                                <label for="attr3">Attribute 3</label>
                                            </div>
                                            <div class="col form-floating">
                                                <input type="text" class="form-control" name="desc_val3"
                                                    id="val3" placeholder="Value 3">
                                                <label for="val3">Value 3</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="container px-0">
                                        <!-- <label for="attr2">2.</label> -->
                                        <div class="row g-0 mt-2">
                                            <div class="col form-floating">
                                                <input type="text" class="form-control" name="desc_attr4"
                                                    id="attr4" placeholder="Attribute 4">
                                                <label for="attr4">Attribute 4</label>
                                            </div>
                                            <div class="col form-floating">
                                                <input type="text" class="form-control" name="desc_val4"
                                                    id="val4" placeholder="Value 4">
                                                <label for="val4">Value 4</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="container px-0">
                                        <!-- <label for="attr2">2.</label> -->
                                        <div class="row g-0 mt-2">
                                            <div class="col form-floating">
                                                <input type="text" class="form-control"
                                                    name="desc_attr5" id="attr5" placeholder="Attribute 5">
                                                <label for="attr5">Attribute 5</label>
                                            </div>
                                            <div class="col form-floating">
                                                <input type="text" class="form-control"
                                                    name="desc_val5" id="val5" placeholder="Value 5">
                                                <label for="val5">Value 5</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="mt-4">Additional Details</div>
                                <div>
                                    <div>
                                        <div class="row mt-2">
                                            <label for="p_image" class="col-sm-4 col-form-label-md py-0 text-start">Product Image</label>
                                            <div class="col-sm-8">
                                                <input type="file" class="form-control" name="product_image" id="p_image" placeholder="Product Image">
                                            </div>  
                                        </div>
                                        
                                        <div class="form-floating mt-2 mb-10 h-4">
                                            <textarea class="form-control" name="product_desc"
                                                id="p_desc" rows="100" placeholder="Product
                                                Description" style="height: 200px"></textarea>
                                            <label for="p_desc" class="form-label">Product Description</label><br>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="form-floating mt-2">
                                            <input type="number" class="form-control" name="block_no"
                                                id="blk_no" min="0" placeholder="Block-number" required>
                                            <label for="blk_no" class="form-label">Block-number</label>
                                        </div>
                                        <div class="form-floating mt-2">
                                            <input type="number" class="form-control" name="row_no" id="row_no"
                                                min="0" placeholder="Row-number" required>
                                            <label for="row_no" class="form-label">Row-number</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                            

                        <input type="submit" class="w-100 btn btn-lg btn-dark mt-4"
                            value="Add Product">
                        <button class="w-100 btn btn-lg btn-dark mt-2"
                            onclick="location.href='addproduct.php'; return false;">Clear</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="pt-5">
            <button class="fixed-bottom w-100 btn btn-lg btn-dark mt-4"
                onclick="location.href='products.php'; return false;">Back</button>
        </div>
    </main>

</body>

</html>