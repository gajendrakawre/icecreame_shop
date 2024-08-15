<?php
    include '../components/connect.php';
    if(isset($_COOKIE['user_id'])){
        $user_id = $_COOKIE['user_id'];
    }else{
        $user_id = 'location: login.php';
    }

// Update quantity in cart
if (isset($_POST['update_cart'])) {
    $cart_id = $_POST['cart_id'];
    $cart_id = filter_var($cart_id, FILTER_SANITIZE_STRING);

    $qty = $_POST['qty'];
    $qty = filter_var($qty, FILTER_SANITIZE_STRING);

    // Fetch the product ID associated with the cart item
    $select_cart_item = $conn->prepare("SELECT product_id FROM cart WHERE id = ?");
    $select_cart_item->execute([$cart_id]);
    $cart_item = $select_cart_item->fetch(PDO::FETCH_ASSOC);
    $product_id = $cart_item['product_id'];

    // Check the available stock for the product
    $select_stock = $conn->prepare("SELECT stock FROM products WHERE id = ?");
    $select_stock->execute([$product_id]);
    $product = $select_stock->fetch(PDO::FETCH_ASSOC);
    $available_stock = $product['stock'];

    if ($qty > $available_stock) {
        $error_msg[] = 'Requested quantity exceeds available stock. Only ' . $available_stock . ' items left.';
    } else {
        // Update the cart if the requested quantity is within the available stock
        $update_qty = $conn->prepare("UPDATE cart SET qty = ? WHERE id = ?");
        $update_qty->execute([$qty, $cart_id]);

        $success_msg[] = 'Cart quantity updated successfully';
    }
}

if (isset($_POST['delete_item'])) {
    $cart_id = $_POST['cart_id'];
    $cart_id = filter_var($cart_id, FILTER_SANITIZE_STRING);

    // Verify if the cart item exists
    $verify_delete_item = $conn->prepare("SELECT * FROM cart WHERE id = ?");
    $verify_delete_item->execute([$cart_id]);

    if ($verify_delete_item->rowCount() > 0) {
        // Delete the cart item if it exists
        $delete_cart_id = $conn->prepare("DELETE FROM cart WHERE id = ?");
        $delete_cart_id->execute([$cart_id]);

        $success_msg[] = 'Cart item deleted successfully';
    } else {
        $warning_msg[] = 'Cart item already deleted';
    }
}

if (isset($_POST['empty_cart'])) {
    // Sanitize user_id just to be safe
    // Verify if the user's cart is not already empty
    $verify_cart = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
    $verify_cart->execute([$user_id]);

    if ($verify_cart->rowCount() > 0) {
        // If the cart has items, delete all items
        $empty_cart = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
        $empty_cart->execute([$user_id]);

        $success_msg[] = 'Cart emptied successfully';
    } else {
        $warning_msg[] = 'Your cart is already empty';
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
    <title>the Blue Summer - user cart Page</title> 
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
        <h1>My Cart</h1>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Libero facilis, 
            unde dolore assumenda rem error vel,<br>similique iste molestiae, 
            distinctio deserunt commodi a nemo aliquam in ex corporis doloremque cumque.</p>
        <span> <a href="home.php">home</a><i class="bx bx-right-arrow-alt"></i> My Cart </span>
    </div>
</div>
<!--------------------------------------------------------------------------------------------------------------->
<div class="products">
    <div class="heading">
        <h1>My Cart</h1>
        <img src="../image/separator-img.png" alt="">
    </div>
    <div class="box-container">
        <?php
        $grand_total = 0;
        // Fetch items from the cart
        $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
        $select_cart->execute([$user_id]);

        if ($select_cart->rowCount() > 0) {
            while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                // Fetch product details
                $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
                $select_products->execute([$fetch_cart['product_id']]);

                if ($select_products->rowCount() > 0) {
                    $fetch_products = $select_products->fetch(PDO::FETCH_ASSOC);
                    ?>
                    <form action="" method="post" class="box <?php if ($fetch_products['stock'] == 0) { echo 'disabled'; } ?>">
                        <input type="hidden" name="cart_id" value="<?= htmlspecialchars($fetch_cart['id']); ?>">
                        <img src="../uploaded_image/<?= htmlspecialchars($fetch_products['image']); ?>" class="image">

                        <?php if ($fetch_products['stock'] > 9) { ?>
                            <span class="stock" style="color: green;">In stock</span>
                        <?php } elseif ($fetch_products['stock'] == 0) { ?>
                            <span class="stock" style="color: red;">Out of stock</span>
                        <?php } else { ?>
                            <span class="stock" style="color: red;">Hurry, only <?= htmlspecialchars($fetch_products['stock']); ?> left</span>
                        <?php } ?>

                        <div class="content">
                            <img src="image/shape-19" class="shap" alt="">
                            <h3 class="name"><?= htmlspecialchars($fetch_products['name']); ?></h3>
                            <div class="flex-btn">
                                <p class="price">Price: $<?= htmlspecialchars($fetch_products['price']); ?>/-</p>
                                <input type="number" name="qty" required min="1" value="<?= htmlspecialchars($fetch_cart['qty']); ?>" max="99" class="qty box">
                                <button type="submit" name="update_cart" class="bx bxs-edit fa-edit box" value="Update"></button>
                            </div>
                            <div class="flex-btn">
                                <p class="sub-total">Sub total: <span><?= $sub_total = ($fetch_cart['qty'] * $fetch_products['price']); ?>/-</span></p>
                                <button type="submit" name="delete_item" onclick="return confirm('Remove from cart?');" class="btn">Delete</button>
                            </div>
                        </div>
                    </form>
                    <?php
                    $grand_total += $sub_total;
                }else{
                    echo '
                        <div class="empty"><p>no products was found</p></div>
                    ';
                }
            }
        }else{
            echo '
                <div class="empty"><p>no products added yet!</p></div>
            ';
        }
        ?>
    </div>
    <?php if ($grand_total != 0) { ?>
    <div class="cart-total">
        <p>Total amount payable: <span>$<?= $grand_total; ?>/-</span></p>
        <div class="button">
            <form action="" method="post">
                <button type="submit" name="empty_cart" class="btn" onclick="return confirm('Are you sure you want to empty your cart?');">
                    Empty Cart
                </button>
            </form>
            <a href="checkout.php" class="btn">Proceed to Checkout</a>
        </div>
    </div>
<?php } ?>
</div>
<!--------------------------------------------------------------------------------------------------------------->

</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="js/user_script.js"></script>
<script src="../js/user_script.js"></script>
<?php include '../components/footer.php'; ?>
<?php include '../components/alert.php'; ?>
</html>