<?php
    include '../components/connect.php';
    if(isset($_COOKIE['user_id'])){
        $user_id = $_COOKIE['user_id'];
    }else{
        $user_id = '';
    }

    if(isset($_GET['get_id'])){
        $get_id = $_GET['get_id'];
    }else{
        $get_id = '';
        header('lication:order.php');
    }

    if (isset($_POST['cancel'])) {
        $update_order = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?"); 
        $update_order->execute(['cancled', $get_id]);
        header('location: order.php');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/user_styles.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>

    <title>the Blue Summer - order detail Page</title> 
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
        <h1>order detail</h1>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Libero facilis, 
            unde dolore assumenda rem error vel,<br>similique iste molestiae, 
            distinctio deserunt commodi a nemo aliquam in ex corporis doloremque cumque.</p>
        <span> <a href="home.php">home</a><i class="bx bx-right-arrow-alt"></i> order detail </span>
    </div>
</div>
<!--------------------------------------------------------------------------------------------------------------->
<div class="order-detail">
    <div class="heading">
        <h1>My Order Detail</h1>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Libero facilis, 
            unde dolore assumenda rem error vel,<br>similique iste molestiae, 
            distinctio deserunt commodi a nemo aliquam in ex corporis doloremque cumque.</p>
        <img src="../image/separator-img.png" alt="Separator">
    </div>

    <div class="box-container">
        <?php
        $grand_total = 0;
        $select_order = $conn->prepare("SELECT * FROM `orders` WHERE id = ? LIMIT 1");
        $select_order->execute([$get_id]);

        if ($select_order->rowCount() > 0) {
            while ($fetch_order = $select_order->fetch(PDO::FETCH_ASSOC)) {
                $select_product = $conn->prepare("SELECT * FROM `products` WHERE id = ? LIMIT 1");
                $select_product->execute([$fetch_order['product_id']]);

                if ($select_product->rowCount() > 0) {
                    while ($fetch_product = $select_product->fetch(PDO::FETCH_ASSOC)) {
                        $sub_total = $fetch_order['price'] * $fetch_order['qty'];
                        $grand_total += $sub_total;
                        ?>
                        <div class="box">
                            <div class="col">
                                <p class="title"><i class="bx bxs-calendar-alt"></i> <?= htmlspecialchars($fetch_order['date']); ?></p>
                                <img src="../uploaded_image/<?= htmlspecialchars($fetch_product['image']); ?>" class="image" alt="<?= htmlspecialchars($fetch_product['name']); ?>">
                                <p class="price">$<?= htmlspecialchars($fetch_product['price']); ?>/-</p>
                                <h3 class="name"><?= htmlspecialchars($fetch_product['name']); ?></h3>
                                <p class="grand-total">Total amount payable: <span>$<?= htmlspecialchars($grand_total); ?>/-</span></p>
                            </div>
                            <div class="col">
                                <p class="title">Billing Address</p>
                                <p class="user"><i class="bx bx-user"></i> <?= htmlspecialchars($fetch_order['name']); ?></p>
                                <p class="user"><i class="bx bx-phone"></i> <?= htmlspecialchars($fetch_order['number']); ?></p>
                                <p class="user"><i class="bx bx-envelope"></i> <?= htmlspecialchars($fetch_order['email']); ?></p>
                                <p class="user"><i class="bx bx-map"></i> <?= htmlspecialchars($fetch_order['address']); ?></p>                                <p class="status" style="color:<?php
                                    if ($fetch_order['status'] == 'delivered') {
                                        echo "green";
                                    } elseif ($fetch_order['status'] == 'cancled') {
                                        echo "red";
                                    } else {
                                        echo "orange";
                                    }
                                ?>"><?= htmlspecialchars($fetch_order['status']); ?></p>
                                <?php if ($fetch_order['status'] == 'cancled') { ?>
                                    <a href="checkout.php?get_id=<?= htmlspecialchars($fetch_product['id']); ?>" class="btn">Order Again</a>
                                <?php } else { ?>
                                    <form action="" method="post">
                                        <button type="submit" name="cancel" class="btn" onclick="return confirm('Do you want to cancel this product?');">Cancel</button>
                                    </form>
                                <?php } ?>
                            </div>
                        </div>
                        <?php
                    }
                }
            }
        } else {
            echo '<p>No order has taken place yet!</p>';
        }
        ?>
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