<?php
    include '../components/connect.php';
    if(isset($_COOKIE['seller_id'])){
        $seller_id = $_COOKIE['seller_id'];
    }else{
        $seller_id = '';
        header('location:login.php');
    }
    //delete product
    if(isset($_POST['delete'])){
        $p_id = $_POST['product_id'];
        $p_id = filter_var($p_id, FILTER_SANITIZE_STRING);
        $delete_product = $conn->prepare("DELETE  FROM `products` WHERE id = ?");
        $delete_product->execute([$p_id]);
        $success_msg[] = 'product deleted successfully';
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
            <section class="show-post">
                <div class="heading">
                    <h1>your products</h1>
                    <img src="../image/separator-img.png" alt="dash-image">
                </div>
                <div class="box-container">
                    <?php
                        $select_product = $conn->prepare("SELECT * FROM `products` WHERE seller_id = ?");
                        $select_product->execute([$seller_id]);
                        if($select_product->rowCount() > 0){
                            while($fetch_products = $select_product->fetch(PDO::FETCH_ASSOC)){
                            
                    ?>
                    <form action="" method="post" class="box">
                    <input type="hidden" name="product_id" value="<?=$fetch_products['id']; ?>">
                    <?php if($fetch_products['image'] != ''){ ?>
                        <img src="../uploaded_image/<?= $fetch_products['image'];?>">
                        <?php } ?>
                        <div class="status" style="color: <?php 
                        if ($fetch_products['status'] == 'active') {
                            echo 'limegreen';
                        } else {
                            echo 'coral';
                        }
                    ?>">
                    <?= $fetch_products['status']; ?>
                        </div>
                    <div class="price">$<?= $fetch_products['price']; ?>/-</div>
                    <div class="content">
                        <img src="../image/shape-19.png" alt="" class="shap">
                        <div class="title"><?= $fetch_products['name']; ?></div>
                        <div class="flex-btn">
                            <a href="edit_product.php?id=<?= $fetch_products['id']; ?>" class="btn">edit</a>
                            <button type="submit" name="delete" class="btn" onclick="return confirm('delete this product')">delete</button>
                            <a href="read_post.php?post_id=<?= $fetch_products['id']; ?>" class="btn">read product</a>
                        </div>
                    </div>

                    </form>
                    <?php
                            }
                        }else{
                            echo '
                            <div class="empty">
                            <p>no product added yet!<BR><br><a href="../admin_panel/add_product.php" 
                            class="btn" style="margin-top: 1.5rem;">add products</a></p>
                            </div>
                            ';
                        }
                
                    ?>
                </div>
            </section>
        </div>
        


</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="../js/admin_script.js"></script>
<?php include '../components/alert.php'; ?>
</html>