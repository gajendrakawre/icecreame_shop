<?php
    include '../components/connect.php';
    if(isset($_COOKIE['user_id'])){
        $user_id = $_COOKIE['user_id'];
    }else{
        $user_id = '';
    }

    if(isset($_POST['submit'])){

        $id = unique_id();
        $name = $_POST['name'];
        $name = filter_var($name, FILTER_SANITIZE_STRING);
        
        $email = $_POST['email'];
        $email = filter_var($email, FILTER_SANITIZE_STRING);

        $pass = sha1($_POST['pass']);
        $pass = filter_var($pass, FILTER_SANITIZE_STRING);

        $cpass = sha1($_POST['cpass']);
        $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

        $image = $_FILES['image']['name'];
        $image = filter_var($image, FILTER_SANITIZE_STRING);
        $ext = pathinfo($image, PATHINFO_EXTENSION);
        $rename = unique_id().'.'.$ext;
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = '../uploaded_image/'.$rename;

        $select_seller = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
        $select_seller->execute([$email]);

        if($select_seller->rowCount() > 0){
            $warning_msg[] = 'Email Already Exists!';
        }else{
            if($pass != $cpass){
                $warning_msg[] = 'Confirm password not matched';
            }else{
                $insert_seller = $conn->prepare("INSERT INTO `users` (id,name,email,password,image) VALUES(?, ?, ?, ?, ?)");
                $insert_seller->execute([$id, $name, $email, $pass, $rename]);
                move_uploaded_file($image_tmp_name, $image_folder);
                $success_msg[] = 'New user registered! Please login now';
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
    <title>the Blue Summer - user registration Page</title> 
</head>
<body>
<header class="header">
    <section class="flex">
        <a href="../user_panel/home.php" class="logo"><img src="image/logo.png" alt="logo-png" width="130px"></a>
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
                <a href="profile.php" class="btn">view profile</a>
                <a href="../components/user_logout.php" onclick="return confirm('logout from this website');" class="btn">logout</a>
            </div>
            <?php } else { ?>
            <h3 style="margin-bottom: 1rem;">please login or register</h3>
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
        <h1>Register</h1>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Libero facilis, 
            unde dolore assumenda rem error vel,<br>similique iste molestiae, 
            distinctio deserunt commodi a nemo aliquam in ex corporis doloremque cumque.</p>
        <span> <a href="home.php">home</a><i class="bx bx-right-arrow-alt"></i> Register </span>
    </div>
</div>
<!--------------------------------------------------------------------------------------------------------------->
<div class="form-container">
        <form action="" method="post" enctype="multipart/form-data" class="register">
            <h3>Register Now</h3>
            <div class="flex">
                <div class="cols">
                    <div class="input-field">
                        <p>Your Name<span>*</span></p>
                        <input type="text" name="name" placeholder="Enter Your Name" maxlength="50" require class="box">
                    </div>
                    <div class="input-field">
                        <p>Your Email<span>*</span></p>
                        <input type="email" name="email" placeholder="Enter Your Email" maxlength="50" require class="box">
                    </div>
                    <div class="input-field">
                        <p>Your Password<span>*</span></p>
                        <input type="password" name="pass" placeholder="Enter Your Password" maxlength="50" require class="box">
                    </div>
                    <div class="input-field">
                        <p>Confirm Password<span>*</span></p>
                        <input type="password" name="cpass" placeholder="Enter Your Password" maxlength="50" require class="box">
                    </div>
                </div>
            </div>
            <div class="input-field">
                <p>Your Profile<span>*</span></p>
                <input type="file" name="image" accept="image/*" require class="box">
                </div>
                <p class="link">Already have an account?<a href="../admin/login.php">Login Now</a></p>
                <input type="submit" name="submit" value="Register Now" class="btn">
        </form>
    </div>

<!--------------------------------------------------------------------------------------------------------------->

</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="../js/user_script.js"></script>
<?php include '../components/footer.php'; ?>
<?php include '../components/alert.php'; ?>
</html>