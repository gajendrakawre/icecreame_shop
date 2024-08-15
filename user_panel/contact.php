<?php
    include '../components/connect.php';
    if(isset($_COOKIE['user_id'])){
        $user_id = $_COOKIE['user_id'];
    }else{
        $user_id = '';
    }

    if (isset($_POST['send_message'])) {
        if (!empty($user_id)) {  // Make sure $user_id is not empty
            $id = unique_id();
            $name = $_POST['name'];
            $name = filter_var($name, FILTER_SANITIZE_STRING);
    
            $email = $_POST['email'];
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error_msg[] = 'Invalid email format';
            } else {
                $subject = $_POST['subject'];
                $subject = filter_var($subject, FILTER_SANITIZE_STRING);
    
                $message = $_POST['message'];
                $message = filter_var($message, FILTER_SANITIZE_STRING);
    
                // Prepare and execute the query
                $verify_message = $conn->prepare("SELECT * FROM `message` WHERE user_id = ? AND name = ? AND email = ? AND subject = ? AND message = ?");
                $verify_message->execute([$user_id, $name, $email, $subject, $message]);
    
                if ($verify_message->rowCount() > 0) {
                    $warning_msg[] = 'Message already exists';
                } else {
                    // Insert the message if it doesn't exist
                    $insert_message = $conn->prepare("INSERT INTO `message` (id, user_id, name, email, subject, message) 
                    VALUES (?, ?, ?, ?, ?, ?)");
                    $insert_message->execute([$id, $user_id, $name, $email, $subject, $message]);
                    $success_msg[] = 'Message sent successfully';
                }
            }
        } else {
            $error_msg[] = 'User ID is required';
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
    <title>the Blue Summer - Contact Page</title> 
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
        <h1>Contact Us</h1>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Libero facilis, 
            unde dolore assumenda rem error vel,<br>similique iste molestiae, 
            distinctio deserunt commodi a nemo aliquam in ex corporis doloremque cumque.</p>
        <span> <a href="home.php">home</a><i class="bx bx-right-arrow-alt"></i> Contact Now </span>
    </div>
</div>
<!--------------------------------------------------------------------------------------------------------------->
<div class="services">
    <div class="heading">
        <h1>Our Services</h1>
        <p>Just A Few Clicks To Make The Reservation Online For Saving Your Time And Money</p>
        <img src="../image/separator-img.png" alt="Separator">
    </div>
    <div class="box-container">
        <div class="box">
            <img src="../image/0.png" alt="Service 1">
            <div>
                <h1>Free Shipping Fast</h1>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
            </div>
        </div>

        <div class="box">
            <img src="../image/1.png" alt="Service 2">
            <div>
                <h1>money back and guarantee</h1>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
            </div>
        </div>

        <div class="box">
            <img src="../image/2.png" alt="Service 3">
            <div>
                <h1>24/7 Support</h1>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
            </div>
        </div>
    </div>
</div>
<div class="form-container">
<div class="heading">
        <h1>drop us a line</h1>
        <p>Just A Few Clicks To Make The Reservation Online For Saving Your Time And Money</p>
        <img src="../image/separator-img.png" alt="Separator">
    </div>
    <form action="" method="post" class="register">
        <div class="input-field">
            <label>Name <sup>*</sup></label>
            <input type="text" name="name" required placeholder="Enter your name" class="box">
        </div>
        <div class="input-field">
            <label>Email <sup>*</sup></label>
            <input type="email" name="email" required placeholder="Enter your email" class="box">
        </div>
        <div class="input-field">
            <label>Subject <sup>*</sup></label>
            <input type="text" name="subject" required placeholder="Reason..." class="box">
        </div>
        <div class="input-field">
            <label>Comment <sup>*</sup></label>
            <textarea name="message" cols="30" rows="10" required placeholder="Your message" class="box"></textarea>
        </div>
        <button type="submit" name="send_message" class="btn">Send Message</button>
    </form>
</div>
<div class="address">
<div class="heading">
        <h1>Our Contack Details</h1>
        <p>Just A Few Clicks To Make The Reservation Online For Saving Your Time And Money</p>
        <img src="../image/separator-img.png" alt="Separator">
    </div>
<div class="box-container">
    <div class="box">
        <i class="bx bxs-map-alt"></i>
        <div>
            <h4>Address</h4>
            <p>#0012, 5th Cross, RKBR St <br> Bangalore, Karnataka, 560043</p>
        </div>
    </div>
    <div class="box">
        <i class="bx bxs-phone-incoming"></i>
        <div>
            <h4>Phone Number</h4>
            <p>83XXXXX540</p>
            <p>82XXXXX246</p>
        </div>
    </div>
    <div class="box">
        <i class="bx bxs-envelope"></i>
        <div>
            <h4>Email</h4>
            <p>Sqwhat260@gmail.com</p>
            <p>Mopur260@gmail.com</p>
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