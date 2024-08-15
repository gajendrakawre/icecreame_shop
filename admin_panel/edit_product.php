<?php
    include '../components/connect.php';
    if(isset($_COOKIE['seller_id'])){
        $seller_id = $_COOKIE['seller_id'];
    }else{
        $seller_id = '';
        header('location:login.php');
        exit();
    }

    // Add product in database
    if (isset($_POST['update'])) {
        $product_id = $_POST['product_id'];
        $product_id = filter_var($product_id, FILTER_SANITIZE_STRING);

        $name = $_POST['name'];
        $name = filter_var($name, FILTER_SANITIZE_STRING);
        $price = $_POST['price'];
        $price = filter_var($price, FILTER_SANITIZE_STRING);
        $description = $_POST['description'];
        $description = filter_var($description, FILTER_SANITIZE_STRING);
        $stock = $_POST['stock'];
        $stock = filter_var($stock, FILTER_SANITIZE_STRING);
        $status = $_POST['status'];
        $status = filter_var($status, FILTER_SANITIZE_STRING);

        $update_product = $conn->prepare("UPDATE `products` SET name = ?, price = ?, product_detail = ?, stock = ?, status = ? WHERE id = ?");
        $update_product->execute([$name, $price, $description, $stock, $status, $product_id]);
        $success_msg[] = 'Product updated';

        $old_image = $_POST['old_image'];
        $image = $_FILES['image']['name'];
        $image = filter_var($image, FILTER_SANITIZE_STRING);
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = '../uploaded_image/'.$image;

        $select_image = $conn->prepare("SELECT * FROM `products` WHERE image = ? AND seller_id = ?");
        $select_image->execute([$image, $seller_id]);

        if ($image) {
            if ($select_image->rowCount() > 0) {
                $warning_msg[] = 'Please rename your image';
            } elseif ($image_size > 2000000) {
                $warning_msg[] = 'Image size is too large';
            } else {
                $update_image = $conn->prepare("UPDATE `products` SET image = ? WHERE id = ?");
                $update_image->execute([$image, $product_id]);
                move_uploaded_file($image_tmp_name, $image_folder);
                if($old_image != $image && $old_image != ''){
                    unlink('../uploaded_image/' . $old_image);
                }
                $success_msg[] = 'Image updated successfully';
            }
        }
    }

     //delete image
    if (isset($_POST['delete_image'])) {
        $empty_image = '';

        $product_id = $_POST['product_id'];
        $product_id = filter_var($product_id, FILTER_SANITIZE_STRING);

        $delete_image = $conn->prepare("SELECT * FROM products WHERE id = ?"); 
        $delete_image->execute([$product_id]);
        $fetch_delete_image = $delete_image->fetch(PDO::FETCH_ASSOC);
        if ($fetch_delete_image['image'] != '') {
            unlink('../uploaded_files/'.$fetch_delete_image['image']);
            }
        $unset_image =$conn->prepare("UPDATE `products` SET image = ? WHERE id = ?"); 
        $unset_image->execute([$empty_image, $product_id]); 
        $success_msg[] = 'image deleted successfully';
    }

         //delete product
    if (isset($_POST['delete_product'])) {
        $product_id = $_POST['product_id'];
        $product_id = filter_var($product_id, FILTER_SANITIZE_STRING);
        $delete_image = $conn->prepare("SELECT * FROM `products` WHERE id = ?"); 
        $delete_image->execute([$product_id]);
        $fetch_delete_image =$delete_image->fetch(PDO::FETCH_ASSOC);
        if ($fetch_delete_image['image'] != '') {
            unlink('../uploaded_files/'.$fetch_delete_image['image']);
            }
        $delete_product=$conn->prepare("DELETE FROM products WHERE id = ?"); 
        $delete_product->execute([$product_id]);
        $success_msg[] = 'product deleted successfully!';         
        header('location:view_product.php');
        }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/admin_styles.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <title>Edit Product</title>
</head>
<body>
       <div class="main-container">
       <header>
    <div class="logo">
        <img src="../image/logo.png" alt="logo" width="200">
    </div>
    <div class="right">
        <div class="bx bxs-user" id="user-btn"></div>
        <div class="toggle-btn"><i class="bx bx-menu"></i></div>
    </div>
    <div class="profile-detail">
        <?php
            $select_profile = $conn->prepare("SELECT * FROM `sellers` WHERE id = ?");
            $select_profile->execute([$seller_id]);

            if($select_profile->rowCount() > 0){
                $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
            }    
        ?>
        <div class="profile">
            <img src="../uploaded_image/<?= $fetch_profile['image']; ?>" alt="profile-image" width="100" class="logo-img">
            <p><?= $fetch_profile['name']; ?></p>
            <div class="flex-btn">
                <a href="profile.php" class="btn">Profile</a>
                <a href="../components/admin_logout.php" onclick="return confirm('Logout from this website?');" class="btn">Logout</a>
            </div>
        </div>
    </div>
</header>
<div class="sidebar-container">
    <div class="sidebar">
        <?php
            $select_profile = $conn->prepare("SELECT * FROM `sellers` WHERE id = ?");
            $select_profile->execute([$seller_id]);

            if($select_profile->rowCount() > 0){
                $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
            }    
        ?>
        <div class="profile">
            <img src="../uploaded_image/<?= $fetch_profile['image']; ?>" alt="profile-image" width="100" class="logo-img">
            <p><?= $fetch_profile['name']; ?></p>
        </div>
        <h5>Menu</h5>
        <div class="navbar">
            <ul>
                <li><a href="../admin_panel/dashboard.php"><i class="bx bxs-home-smile"></i> Dashboard</a></li>
                <li><a href="../admin_panel/add_product.php"><i class="bx bxs-shopping-bags"></i> Add Product</a></li>
                <li><a href="../admin_panel/view_product.php"><i class="bx bxs-food-menu"></i> View Product</a></li>
                <li><a href="../admin_panel/user_account.php"><i class="bx bxs-user-detail"></i> User Account</a></li>
                <li><a href="../components/admin_logout.php" onclick="return confirm('Logout from this website?');"><i class="bx bx-log-out"></i> Logout</a></li>
            </ul>
        </div>
        <h5>Find Us</h5>
        <div class="social-links">
            <i class="bx bxl-facebook"></i>
            <i class="bx bxl-instagram-alt"></i>
            <i class="bx bxl-linkedin"></i>
            <i class="bx bxl-twitter"></i>
            <i class="bx bxl-pinterest-alt"></i>
        </div>
    </div>
</div>
            <section class="post-editor">
                <div class="heading">
                    <h1>Edit Product</h1>
                    <img src="../image/separator-img.png" alt="dash-image">
                </div>
                <?php
                    if (isset($_GET['id'])) {
                        $product_id = $_GET['id'];
                        $select_product = $conn->prepare("SELECT * FROM `products` WHERE id = ? AND seller_id = ?");
                        $select_product->execute([$product_id, $seller_id]);
                        if($select_product->rowCount() > 0){
                            while($fetch_product = $select_product->fetch(PDO::FETCH_ASSOC)){
                ?>
                <div class="form-container">
                    <form action="" method="post" enctype="multipart/form-data" class="register">
                        <input type="hidden" name="old_image" value="<?= $fetch_product['image']; ?>">
                        <input type="hidden" name="product_id" value="<?= $fetch_product['id']; ?>">
                        <div class="input-field">
                            <p>Product Status<span>*</span></p>
                            <select name="status" class="box">
                                <option value="<?= $fetch_product['status']; ?>" selected><?= $fetch_product['status']; ?></option>
                                <option value="active">Active</option>
                                <option value="deactive">Deactive</option>
                            </select>
                        </div>
                        <div class="input-field">
                            <p>Product Name<span>*</span></p>
                            <input type="text" name="name" value="<?= $fetch_product['name']; ?>" class="box">
                        </div>
                        <div class="input-field">
                            <p>Product Price<span>*</span></p>
                            <input type="number" name="price" value="<?= $fetch_product['price']; ?>" class="box">
                        </div>
                        <div class="input-field">
                            <p>Product Description<span>*</span></p>
                            <textarea name="description" class="box"><?= $fetch_product['product_detail']; ?></textarea>
                        </div>
                        <div class="input-field">
                            <p>Product Stock<span>*</span></p>
                            <input type="number" name="stock" value="<?= $fetch_product['stock']; ?>" class="box">
                        </div>
                        <div class="input-field">
                            <p>Product Image<span>*</span></p>
                            <input type="file" name="image" accept="image/*" class="box">
                            <?php if($fetch_product['image'] != ''){ ?>
                                <img src="../uploaded_image/<?= $fetch_product['image']; ?>" class="image">
                                <div class="flex-btn">
                                    <input type="submit" name="delete_image" value="Delete Image" class="btn">
                                    <a href="../admin_panel/view_product.php" class="btn">Go Back!</a>
                                </div>
                                <br><br>
                            <?php } ?>
                        </div>
                        <div class="flex-btn">
                            <input type="submit" value="Update Product" name="update" class="btn">
                            <input type="submit" value="Delete Product" name="delete_product" class="btn">
                        </div>
                    </form>
                </div>
                <?php
                            }
                        } else {
                            echo '
                            <div class="empty">
                                <p>No product added yet!</p>
                            </div>
                            ';
                        }
                    } else {
                        echo '
                        <div class="empty">
                            <p>No product selected!</p>
                        </div>
                        ';
                    }
                ?>
                <br><br>
                <div class="flex-btn">
                    <a href="../admin_panel/view_product.php" class="btn">View Products</a>
                    <a href="../admin_panel/add_product.php" class="btn">Add Product</a>
                </div>
            </section>
        </div>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="../js/admin_script.js"></script>
<?php include '../components/alert.php'; ?>
</html>
