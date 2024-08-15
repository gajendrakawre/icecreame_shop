<?php
    include '../components/connect.php';
    if(isset($_COOKIE['user_id'])){
        $user_id = $_COOKIE['user_id'];
    }else{
        $user_id = '';
    }


    if (isset($_POST['add_to_wishlist'])) {
        if ($user_id != '') {
            $id = unique_id();
            $product_id = $_POST['product_id'];
    
            // Check if the product is already in the wishlist
            $verify_wishlist = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ? AND product_id = ?");
            $verify_wishlist->execute([$user_id, $product_id]);
    
            if ($verify_wishlist->rowCount() > 0) {
                $warning_msg[] = 'Product already exists in your wishlist';
            } else {
                // Check if the product is already in the cart
                $verify_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ? AND product_id = ?");
                $verify_cart->execute([$user_id, $product_id]);
    
                if ($verify_cart->rowCount() > 0) {
                    $warning_msg[] = 'Product already exists in your cart';
                } else {
                    // Get the product price
                    $select_price = $conn->prepare("SELECT * FROM `products` WHERE id = ? LIMIT 1");
                    $select_price->execute([$product_id]);
                    $fetch_price = $select_price->fetch(PDO::FETCH_ASSOC);
    
                    // Insert the product into the wishlist
                    $insert_wishlist = $conn->prepare("INSERT INTO `wishlist` (id, user_id, product_id, price) VALUES (?, ?, ?, ?)");
                    $insert_wishlist->execute([$id, $user_id, $product_id, $fetch_price['price']]);
    
                    // Optionally, you can set a success message or redirect
                    $success_msg[] = 'Product added to your wishlist successfully';
                }
            }
        } else {
            $warning_msg[] = 'Please log in to add products to your wishlist';
        }
    }
    // Add product to cart
if (isset($_POST['add_to_cart'])) {
    if (isset($user_id) && $user_id != '') {
        $id = unique_id(); // Generate a unique ID for the cart entry
        $product_id = $_POST['product_id'];
        $qty = $_POST['qty'];
        $qty = filter_var($qty, FILTER_SANITIZE_NUMBER_INT);

        // Check if the product is already in the cart
        $verify_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ? AND product_id = ?");
        $verify_cart->execute([$user_id, $product_id]);

        // Check the maximum number of items in the cart
        $max_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
        $max_cart_items->execute([$user_id]);

        if ($verify_cart->rowCount() > 0) {
            $warning_msg[] = 'Product already exists in your cart';
        } elseif ($max_cart_items->rowCount() >= 20) {
            $warning_msg[] = 'Your cart is full';
        } else {
            // Get the product price
            $select_price = $conn->prepare("SELECT price FROM `products` WHERE id = ? LIMIT 1");
            $select_price->execute([$product_id]);
            $fetch_price = $select_price->fetch(PDO::FETCH_ASSOC);

            if ($fetch_price) {
                // Insert the product into the cart
                $insert_cart = $conn->prepare("INSERT INTO `cart` (id, user_id, product_id, qty, price) VALUES (?, ?, ?, ?, ?)");
                $insert_cart->execute([$id, $user_id, $product_id, $qty, $fetch_price['price']]);

                // Optionally, you can set a success message or redirect
                $success_msg[] = 'Product added to your cart successfully';
            } else {
                $warning_msg[] = 'Product does not exist';
            }
        }
    } else {
        $warning_msg[] = 'Please log in to add products to your cart';
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/user_styles.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <title>the Blue Summer - our shop Page</title> 
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
        <h1>our menu</h1>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Libero facilis, 
            unde dolore assumenda rem error vel,<br>similique iste molestiae, 
            distinctio deserunt commodi a nemo aliquam in ex corporis doloremque cumque.</p>
        <span> <a href="home.php">home</a><i class="bx bx-right-arrow-alt"></i>our menu </span>
    </div>
</div>
<!--------------------------------------------------------------------------------------------------------------->
<div class="products">
    <div class="heading">
        <h1>Our Latest Flavours</h1>
        <img src="../image/separator-img.png" alt="Separator Image">
    </div>
    <div class="box-container">
        <?php
        $select_products = $conn->prepare("SELECT * FROM `products` WHERE status = ?");
        $select_products->execute(['active']);

        if ($select_products->rowCount() > 0) {
            while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
        ?>
        <form action="" method="post" class="box <?php if ($fetch_products['stock'] == 0) { echo 'disabled'; } ?>">
            <img src="../uploaded_image/<?= htmlspecialchars($fetch_products['image']); ?>" alt="Product Image" class="image">
            <?php if ($fetch_products['stock'] >  9) { ?>
                <span class="stock" style="color: green;">In Stock</span>
            <?php } elseif ($fetch_products['stock'] == 0) { ?>
                <span class="stock" style="color: red;">Out of Stock</span>
            <?php } else { ?>
                <span class="stock" style="color: red;">Hurry, only <?= htmlspecialchars($fetch_products['stock']); ?> left!</span>
            <?php } ?>
            <div class="content">
                <img src="../image/shape-19.png" alt="Shape Image" class="shap">
                <div class="button">
                    <div>
                        <h3 class="name"><?= htmlspecialchars($fetch_products['name']); ?></h3>
                    </div>
                    <div>
                        <button type="submit" name="add_to_cart"><i class="bx bx-cart"></i></button>
                        <button type="submit" name="add_to_wishlist"><i class="bx bx-heart"></i></button>
                        <a href="view_page.php?pid=<?= htmlspecialchars($fetch_products['id']); ?>" class="bx bxs-show"></a>
                    </div>
                </div>
                <p class="price">Price: $<?= htmlspecialchars($fetch_products['price']); ?></p>
                <input type="hidden" name="product_id" value="<?= htmlspecialchars($fetch_products['id']); ?>">
                <div class="flex-btn">
                    <a href="checkout.php?get_id=<?= htmlspecialchars($fetch_products['id']); ?>" class="btn">Buy Now</a>
                    <input type="number" name="qty" required min="1" value="1" max="99" maxlength="2" class="qty box">
                </div>
            </div>
        </form>
        <?php
            }
        } else {
            echo '
                <div class="empty">
                    <p>No products added yet!</p>
                </div>
            ';
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