<?php
    include '../components/connect.php';
    if(isset($_COOKIE['seller_id'])){
        $seller_id = $_COOKIE['seller_id'];
    }else{
        $seller_id = '';
        header('location:login.php');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/admin_styles.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <title>dashboard page</title>
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
            <section class="dashboard">
                <div class="heading">
                    <h1>dashboard</h1>
                    <img src="../image/separator-img.png" alt="dash-image">
                </div>
                <div class="box-container">
                    <div class="box">
                        <h3>welcome !</h3>
                        <p><?= $fetch_profile['name']; ?></p>
                        <a href="update.php" class="btn">update profile</a>
                    </div>
                    <div class="box">
                    <?php
                        $select_message = $conn->prepare("SELECT * FROM `message`"); 
                        $select_message->execute();
                        $number_of_msg = $select_message->rowCount();
                    ?>
                        <h3><?= $number_of_msg; ?></h3>
                        <p>unread message</p>
                        <a href="admin_message.php" class="btn">see message</a>
                    </div>
                    <div class="box">
                    <?php
                        $select_products= $conn->prepare("SELECT * FROM `products` WHERE seller_id = ?");
                        $select_products->execute([$seller_id]);
                        $number_of_products = $select_products->rowCount();
                    ?>
                    <h3><?= $number_of_products; ?></h3>
                    <p>products added</p>
                    <a href="add_product.php" class="btn">add product</a>
                    </div>

                    <div class="box">
                    <?php
                        $select_active_products= $conn->prepare("SELECT * FROM `products` WHERE seller_id=? AND status = ?");
                        $select_active_products->execute([$seller_id, 'active']);
                        $number_of_active_products = $select_products->rowCount();
                    ?>
                    <h3><?= $number_of_active_products; ?></h3>
                    <p>total active products</p>
                    <a href="view_product.php" class="btn">active product</a>
                    </div>

                    <div class="box">
                    <?php
                        $select_deactive_products = $conn->prepare("SELECT * FROM `products` WHERE seller_id = ? AND status = ?");
                        $select_deactive_products->execute([$seller_id, 'deactive']);
                        $number_of_deactive_products = $select_deactive_products->rowCount();
                        ?>
                    <h3><?= $number_of_deactive_products; ?></h3>
                    <p>total deactive products</p>
                    <a href="view_product.php" class="btn">deactive product</a>
                    </div>
                    <div class="box">
                    <?php
                        $select_users = $conn->prepare("SELECT * FROM users ");
                        $select_users->execute();
                        $number_of_users = $select_users->rowCount();
                    ?>
                    <h3><?= $number_of_users; ?></h3>
                    <p>users account</p>
                    <a href="user_account.php" class="btn">see users</a>
                    </div>
                    <div class="box">
                    <?php
                    $select_sellers = $conn->prepare("SELECT * FROM `sellers` ");
                    $select_sellers->execute();
                    $number_of_sellers  = $select_sellers->rowCount();
                    ?>
                    <h3><?= $number_of_sellers; ?></h3>
                    <p>sellers account</p>
                    <a href="user_accounts.php" class="btn">see seller</a> 
                    </div>

                    <div class="box">
                    <?php
                        $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE seller_id = ?");
                        $select_orders->execute([$seller_id]);
                        $number_of_orders =  $select_orders->rowCount();
                    ?>
                    <h3><?= $number_of_orders; ?></h3>
                    <p>total orders placed</p>
                    <a href="admin_order.php" class="btn">total orders</a> </div>
                    <div class="box">
                    <?php
                        $select_confirm_orders  = $conn->prepare("SELECT * FROM `orders` WHERE seller_id = ? AND status = ?");
                        $select_confirm_orders->execute([$seller_id, 'in progress']); 
                        $number_of_confirm_orders = $select_confirm_orders->rowCount();
                        
                    ?>
                    <h3><?= $number_of_confirm_orders; ?></h3>
                    <p>total confirm orders</p>
                    <a href="admin_order.php" class="btn">confirm orders</a>
                    </div>

                    <div class="box">
                    <?php
                        $select_canceled_orders  = $conn->prepare("SELECT * FROM `orders` WHERE seller_id = ? AND status = ?");
                        $select_canceled_orders->execute([$seller_id, 'cancled']); 
                        $number_of_canceled_orders = $select_canceled_orders->rowCount();                        
                    ?>
                    <h3><?= $number_of_canceled_orders; ?></h3>
                    <p>total canceled orders</p>
                    <a href="admin_order.php" class="btn">canceled orders</a>
                    </div>
                </div>
            </section>
        </div>


</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="../js/admin_script.js"></script>
<?php include '../components/alert.php'; ?>
</html>