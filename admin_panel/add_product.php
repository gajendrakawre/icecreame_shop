<?php
    include '../components/connect.php';
    if(isset($_COOKIE['seller_id'])){
        $seller_id = $_COOKIE['seller_id'];
    }else{
        $seller_id = '';
        header('location:login.php');
    }

    //add product in database
    if (isset($_POST['publish'])) {
        $id = unique_id();
        $name =$_POST['name'];
        $name = filter_var($name, FILTER_SANITIZE_STRING);
        $price = $_POST['price'];
        $price = filter_var($price, FILTER_SANITIZE_STRING);
        $description = $_POST['description'];
        $description = filter_var($description, FILTER_SANITIZE_STRING);
        $stock = $_POST['stock'];
        $stock = filter_var($stock, FILTER_SANITIZE_STRING); 
        $status = 'active';
        $image = $_FILES['image']['name'];
        $image = filter_var($image, FILTER_SANITIZE_STRING);
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = '../uploaded_image/'.$image;

        $select_image = $conn->prepare("SELECT * FROM `products` WHERE image = ? AND seller_id = ?");
        $select_image->execute([$image, $seller_id]);

        if (isset($image)) {
            if ($select_image->rowCount() > 0) {
                $warning_msg[] = 'image name repeated';
            }elseif ($image_size>2000000) {
                $warning_msg[] = 'image size is too large';
            }else{
                move_uploaded_file($image_tmp_name, $image_folder);
            if ($select_image->rowCount() > 0 AND $image != '') {
                $warning_msg[] = 'please rename your image';
            }else{
            $insert_product = $conn->prepare("INSERT INTO `products` (id, seller_id, name, price, image, stock, product_detail, status) VALUES (?,?,?,?,?,?,?,?)"); 
            $insert_product->execute([$id, $seller_id, $name, $price, $image, $stock, $description, $status]);
            $success_msg[] = 'product inserted successfully';
            }
        }
    }
}
        //draft product in database
    if (isset($_POST['draft'])) {
        $id = unique_id();
        $name =$_POST['name'];
        $name = filter_var($name, FILTER_SANITIZE_STRING);
        $price = $_POST['price'];
        $price = filter_var($price, FILTER_SANITIZE_STRING);
        $description = $_POST['description'];
        $description = filter_var($description, FILTER_SANITIZE_STRING);
        $stock = $_POST['stock'];
        $stock = filter_var($stock, FILTER_SANITIZE_STRING); 
        $status = 'deactive';
        $image = $_FILES['image']['name'];
        $image = filter_var($image, FILTER_SANITIZE_STRING);
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = '../uploaded_image/'.$image;

        $select_image = $conn->prepare("SELECT * FROM `products` WHERE image = ? AND seller_id = ?");
        $select_image->execute([$image, $seller_id]);

        if (isset($image)) {
            if ($select_image->rowCount() > 0) {
                $warning_msg[] = 'image name repeated';
            }elseif ($image_size>2000000) {
                $warning_msg[] = 'image size is too large';
            }else{
                move_uploaded_file($image_tmp_name, $image_folder);
            if ($select_image->rowCount() > 0 AND $image != '') {
                $warning_msg[] = 'please rename your image';
            }else{
            $insert_product = $conn->prepare("INSERT INTO `products` (id, seller_id, name, price, image, stock, product_detail, status) VALUES (?,?,?,?,?,?,?,?)"); 
            $insert_product->execute([$id, $seller_id, $name, $price, $image, $stock, $description, $status]);
            $success_msg[] = 'product saved as draft successfully';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/admin_styles.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <title>Register page</title>
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
                    <h1>add product</h1>
                    <img src="../image/separator-img.png" alt="dash-image">
                </div>
                <div class="form-container">
                <form action="" method="post" enctype="multipart/form-data" class="register">
                    <div class="input-field">
                        <p>Product Name<span>*</span></p>
                        <input type="text" name="name" placeholder="Enter Product Name" maxlength="50" required class="box">
                    </div>
                    <div class="input-field">
                        <p>Product Price<span>*</span></p>
                        <input type="number" name="price" placeholder="Enter Product Price" maxlength="50" required class="box">
                    </div>
                    <div class="input-field">
                        <p>Product Detail<span>*</span></p>
                        <textarea name="description" required maxlength="1000" placeholder="Enter Prooduct Detail" class ="box" id=""></textarea>
                    </div>
                    <div class="input-field">
                        <p>Product stock<span>*</span></p>
                        <input type="number" name="stock" placeholder="Enter Product stock" maxlength="10" min="0"
                        max="9999999999" required class="box">
                    </div>
                    <div class="input-field">
                        <p>Your Profile<span>*</span></p>
                        <input type="file" name="image" accept="image/*" required class="box">
                    </div>
                    <br><br>
                    <div class="flex-btn">
                    <input type="submit" name="publish" value="add product" class="btn">
                    <input type="submit" name="draft" value="draft product" class="btn">
                    </div>
                </div>
            </section>
        </div>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="../js/admin_script.js"></script>
<?php include '../components/alert.php'; ?>
</html>