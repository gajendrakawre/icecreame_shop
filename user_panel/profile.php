<?php
    include '../components/connect.php';
    if(isset($_COOKIE['user_id'])){
        $user_id = $_COOKIE['user_id'];
    }else{
        $user_id = 'location: ../user_panel.login.php';
    }

    $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
    $select_orders->execute([$user_id]);
    $total_orders = $select_orders->rowCount();

    $select_message = $conn->prepare("SELECT * FROM `message` WHERE user_id = ?");
    $select_message->execute([$user_id]);
    $total_message= $select_message->rowCount();

    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/user_styles.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <title>the Blue Summer - user profile Page</title> 
</head>
<body>
<header class="header">
    <section class="flex">
        <a href="../home.php" class="logo"><img src="image/logo.png" alt="logo-png" width="130px"></a>
        <nav class="navbar">
        <a href="../user_panel/home.php">home</a>
            <a href="../user_panel/about_us.php">about us</a>
            <a href="../user_panel/menu.php">shop</a>
            <a href="../user_panel/order.php">order</a>
            <a href="../user_panel/contact.php">contact us</a>
        </nav>
        <form action="search_product.php" method="post" class="search-form">
            <input type="text" name="search_product" placeholder="search product..." required maxlength="100">
            <button type="submit" class="bx bx-search-alt-2" id="search_product_btn"></button>
        </form>
        <div class="icons">
            <div class="bx bx-list-plus" id="menu-btn"></div>
            <div class="bx bx-search-alt-2" id="search-btn"></div>
            <?php
// Start the session if needed
// session_start(); 

// Ensure $conn and $user_id are defined before running the queries

// Count wishlist items
$count_wishlist_item = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
$count_wishlist_item->execute([$user_id]);
$total_wishlist_items = $count_wishlist_item->rowCount();

// Count cart items
$count_cart_item = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
$count_cart_item->execute([$user_id]);
$total_cart_items = $count_cart_item->rowCount();
?>

<!-- HTML output -->
<a href="wishlist.php">
    <i class="bx bx-heart"></i>
    <sup><?= $total_wishlist_items; ?></sup>
</a>

<a href="cart.php">
    <i class="bx bx-cart"></i>
    <sup><?= $total_cart_items; ?></sup>
</a>
            <div class="bx bxs-user" id="user-btn"></div>
        </div>
        <div class="profile-detail">
            <?php
                $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
                $select_profile->execute([$user_id]);
                if ($select_profile->rowCount() > 0) {
                    $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
            ?>
            <img src="../uploaded_image/<?= $fetch_profile['image']; ?>" alt="Profile Image">
            <h3 style="margin-bottom: 1rem;"><?= $fetch_profile['name']; ?></h3>
            <div class="flex-btn">
                <a href="../user_panel/profile.php" class="btn">view profile</a>
                <a href="../components/user_logout.php" onclick="return confirm('logout from this website');" class="btn">logout</a>
            </div>
            <?php } else { ?>
            <h3 style="margin-bottom: 2rem;">please login or register</h3>
            <div class="flex-btn">
                <a href="login.php" class="btn">login</a>
                <a href="register.php" class="btn">register</a>
            </div>
            <?php } ?>
        </div>
    </section>
</header>
<!--------------------------------------------------------------------------------------------------------------->
<div class="banner">
    <div class="detail">
        <h1>profile</h1>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Libero facilis, 
            unde dolore assumenda rem error vel,<br>similique iste molestiae, 
            distinctio deserunt commodi a nemo aliquam in ex corporis doloremque cumque.</p>
        <span> <a href="home.php">home</a><i class="bx bx-right-arrow-alt"></i> profile </span>
    </div>
</div>
<!--------------------------------------------------------------------------------------------------------------->
<div class="profile">
    <div class="heading">
        <h1>profile detail</h1>
        <img src="../image/separator-img.png" alt="">
    </div>
    <div class="details">
        <div class="user">
            <img src="../uploaded_image/<?= $fetch_profile['image']; ?>" alt="">
            <h3 style="margin-bottom: 1rem;"><?= $fetch_profile['name']; ?></h3>
            <p>user</p>
            <a href="../user_panel/update.php" class="btn">update profile</a>
        </div>
        <div class="box-container">
            <div class="box">
                <div class="flex">
                    <i class="bx bxs-folder-minus"></i>
                    <h3><?= $total_orders ?></h3>
                </div>
                <a href="../user_panel/order.php" class="btn">view orders</a>
            </div>
            <div class="box">
                <div class="flex">
                    <i class="bx bxs-chat"></i>
                    <h3><?= $total_message ?></h3>
                </div>
                <a href="../user_panel/message.php" class="btn">view messages</a>
            </div>
        </div>
    </div>
</div>
<!--------------------------------------------------------------------------------------------------------------->

</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="js/user_script.js"></script>
<script src="../js/user_script.js"></script>
<?php include '../components/footer.php'; ?>
<?php include '../components/alert.php'; ?>
</html>