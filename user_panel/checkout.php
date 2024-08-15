<?php
    include '../components/connect.php';
    if(isset($_COOKIE['user_id'])){
        $user_id = $_COOKIE['user_id'];
    }else{
        $user_id = '';
        header('location:login.php');
    }
    

    if (isset($_POST['place_order'])) {
        $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        $number = filter_var($_POST['number'], FILTER_SANITIZE_STRING);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
        $address = filter_var($_POST['flat'] . ',' . $_POST['street'] . ',' . $_POST['city'] . ',' . $_POST['state'] . ',' . $_POST['country'] . ',' . $_POST['pin'], FILTER_SANITIZE_STRING);
        $address_type = filter_var($_POST['address_type'], FILTER_SANITIZE_STRING);
        $method = filter_var($_POST['method'], FILTER_SANITIZE_STRING);
    
        $verify_cart = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
        $verify_cart->execute([$user_id]);
    
        if (isset($_GET['get_id'])) {
            $get_product = $conn->prepare("SELECT * FROM products WHERE id = ? LIMIT 1");
            $get_product->execute([$_GET['get_id']]);
    
            if ($get_product->rowCount() > 0) {
                while ($fetch_p = $get_product->fetch(PDO::FETCH_ASSOC)) {
                    $seller_id = $fetch_p['seller_id'];
    
                    $insert_order = $conn->prepare("INSERT INTO orders (id, user_id, seller_id, name, number, email, address, address_type, method, product_id, price, qty) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
                    $insert_order->execute([uniqid(), $user_id, $seller_id, $name, $number, $email, $address, $address_type, $method, $fetch_p['id'], $fetch_p['price'], 1]);
    
                    header('location: order.php');
                }
            } else {
                $warning_msg[] = 'Something went wrong';
            }
        } elseif ($verify_cart->rowCount() > 0) {
            while ($f_cart = $verify_cart->fetch(PDO::FETCH_ASSOC)) {
                $s_products = $conn->prepare("SELECT * FROM products WHERE id = ? LIMIT 1");
                $s_products->execute([$f_cart['product_id']]);
                $f_product = $s_products->fetch(PDO::FETCH_ASSOC);
    
                $seller_id = $f_product['seller_id'];
    
                $insert_order = $conn->prepare("INSERT INTO orders (id, user_id, seller_id, name, number, email, address, address_type, method, product_id, price, qty) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
                $insert_order->execute([uniqid(), $user_id, $seller_id, $name, $number, $email, $address, $address_type, $method, $f_cart['product_id'], $f_cart['price'], $f_cart['qty']]);
            }
    
            if ($insert_order) {
                $delete_cart = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
                $delete_cart->execute([$user_id]);
                header('location: order.php');
            }
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
    <title>the Blue Summer - Checkout Page</title> 
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
        <h1>Checkout</h1>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Libero facilis, 
            unde dolore assumenda rem error vel,<br>similique iste molestiae, 
            distinctio deserunt commodi a nemo aliquam in ex corporis doloremque cumque.</p>
        <span> <a href="home.php">home</a><i class="bx bx-right-arrow-alt"></i> Checkout </span>
    </div>
</div>
<!--------------------------------------------------------------------------------------------------------------->
<div class="checkout">
    <div class="heading">
        <h1>Checkout Summary</h1>
        <img src="../image/separator-img.png" alt="Separator">
    </div>
    <div class="row">
        <form action="" method="post" class="register">
            <input type="hidden" name="p_id" value="<?= $gey_id; ?>">
            <h3>Billing Details</h3>
            <div class="flex">
                <div class="box">
                    <div class="input-field">
                        <p>Your Name <span>*</span></p>
                        <input type="text" name="name" placeholder="Enter your name" required maxlength="50" class="input">
                    </div>
                    <div class="input-field">
                        <p>Your Number <span>*</span></p>
                        <input type="number" name="number" placeholder="Enter your number" required maxlength="10" class="input">
                    </div>
                    <div class="input-field">
                        <p>Your Email <span>*</span></p>
                        <input type="email" name="email" placeholder="Enter your email" required maxlength="50" class="input">
                    </div>
                    <div class="input-field">
                        <p>Payment Method <span>*</span></p>
                        <select name="method" class="input">
                            <option value="cod">Cash On Delivery (COD)</option>
                            <option value="credit_debit_card">Credit/Debit Card</option>
                            <option value="upi">UPI/RuPay</option>
                            <option value="bank_transfer">Net Banking Transfer</option>
                            <option value="gpay">GPay</option>
                            <option value="phonepe">PhonePe</option>
                            <option value="paytm">Paytm</option>
                        </select>
                    </div>
                    <div class="input-field">
                        <p>Address Type <span>*</span></p>
                        <select name="address_type" class="input">
                            <option value="home">Home</option>
                            <option value="office">Office</option>
                            <option value="other">Other</option>
                        </select>                            
                        <button type="submit" class="btn" name="place_order">Place Order</button>
                    </div>
                </div>
                <div class="box">
                    <div class="input-field">
                        <p>Address Line 01 <span>*</span></p>
                        <input type="text" name="flat" placeholder="e.g. flat or building name" required maxlength="50" class="input">
                    </div>
                    <div class="input-field">
                        <p>Address Line 02 <span>*</span></p>
                        <input type="text" name="street" placeholder="e.g. street name" required maxlength="50" class="input">
                    </div>
                    <div class="input-field">
                        <p>City Name <span>*</span></p>
                        <input type="text" name="city" placeholder="e.g. city name" required maxlength="50" class="input">
                    </div>
                    <div class="input-field">
                        <p>State/Province <span>*</span></p>
                        <input type="text" name="state" placeholder="e.g. state or province name" required maxlength="50" class="input">
                    </div>
                    <div class="input-field">
                        <p>Country <span>*</span></p>
                        <input type="text" name="country" placeholder="e.g. country name" required maxlength="50" class="input">
                    </div>
                    <div class="input-field">
                        <p>Postal Code <span>*</span></p>
                        <input type="text" name="pin" placeholder="e.g. 123456" required maxlength="10" class="input">
                    </div>
                </div>
            </div>
        </form>
        <div class="summary">
            <h3>My Bag</h3>
            <div class="box-container">
                <?php
                $grand_total = 0;
                if (isset($_GET['get_id'])) {
                    $select_get = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
                    $select_get->execute([$_GET['get_id']]);
                    while ($fetch_get = $select_get->fetch(PDO::FETCH_ASSOC)) {
                        $sub_total = $fetch_get['price'];
                        $grand_total += $sub_total;
                ?>
                <div class="flex">
                    <img src="../uploaded_image/<?= $fetch_get['image']; ?>" class="image">
                    <div>
                        <h3 class="name"><?= $fetch_get['name']; ?></h3>
                        <p class="price">$<?= $fetch_get['price']; ?></p>
                    </div>
                </div>
                <?php
                    }
                } else {
                    $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
                    $select_cart->execute([$user_id]);

                    if ($select_cart->rowCount() > 0) {
                        while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                            $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
                            $select_products->execute([$fetch_cart['product_id']]);
                            $fetch_products = $select_products->fetch(PDO::FETCH_ASSOC);
                            $sub_total = $fetch_cart['qty'] * $fetch_products['price'];
                            $grand_total += $sub_total;
                ?>
                <div class="flex">
                    <img src="../uploaded_image/<?= $fetch_products['image']; ?>" class="image">
                    <div>
                        <h3 class="name"><?= $fetch_products['name']; ?></h3>
                        <p class="price">$<?= $fetch_products['price']; ?> x <?= $fetch_cart['qty']; ?></p>
                    </div>
                </div>
                <?php
                        }
                    } else {
                        echo '<p class="empty">Your cart is empty</p>';
                    }
                }
                ?>
            </div>
            <div class="grand-total">
                <p>Grand Total:</p>
                <p>$<?= $grand_total; ?></p>
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