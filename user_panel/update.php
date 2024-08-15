<?php
    include '../components/connect.php';
    if(isset($_COOKIE['user_id'])){
        $user_id = $_COOKIE['user_id'];
    }else{
        $user_id = '';
    }

    if (isset($_POST['submit'])) {
        $select_user = $conn->prepare("SELECT * FROM `users` WHERE id = ? LIMIT 1");
        $select_user->execute([$user_id]);
        $fetch_user = $select_user->fetch(PDO::FETCH_ASSOC);
        $prev_pass = $fetch_user['password'];
        $prev_image = $fetch_user['image'];
        $name = $_POST['name'];
        $name = filter_var($name, FILTER_SANITIZE_STRING);
        $email = $_POST['email'];
        $email = filter_var($email, FILTER_SANITIZE_STRING);
        
        // Update name
        if (!empty($name)) {
            $update_name = $conn->prepare("UPDATE `users` SET name = ? WHERE id = ?"); 
            $update_name->execute([$name, $user_id]); 
            $success_msg[] = 'Username updated successfully';
        }
    
        // Update email
        if (!empty($email)) {
            $select_email = $conn->prepare("SELECT * FROM `users` WHERE id = ? AND email = ?"); 
            $select_email->execute([$user_id, $email]);
    
            if ($select_email->rowCount() > 0) {
                $warning_msg[] = 'Email already exists';
            } else {
                $update_email = $conn->prepare("UPDATE `users` SET email = ? WHERE id = ?"); 
                $update_email->execute([$email, $user_id]); 
                $success_msg[] = 'Email updated successfully';
            }
        }
    
        // Update image
        if (isset($_FILES['image'])) {
            $image = $_FILES['image']['name'];
            $image = filter_var($image, FILTER_SANITIZE_STRING); 
            $ext = pathinfo($image, PATHINFO_EXTENSION); 
            $rename = unique_id() . '.' . $ext;
            $image_size = $_FILES['image']['size'];
            $image_tmp_name = $_FILES['image']['tmp_name'];
            $image_folder = '../uploaded_image/'.$rename;

    
            if (!empty($image)) {
                if ($image_size > 2000000) {
                    $warning_msg[] = 'Image size is too large';
                } else {
                    $update_image = $conn->prepare("UPDATE `users` SET image = ? WHERE id = ?"); 
                    $update_image->execute([$rename, $user_id]); 
                    move_uploaded_file($image_tmp_name, $image_folder);
                    if ($prev_image != '' && $prev_image != $rename) { 
                        unlink('../uploaded_image/' . $prev_image);
                    }
                    $success_msg[] = 'Image updated successfully';
                } 
            }
        }
    
        // Update password
        $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
        $old_pass = sha1($_POST['old_pass']);
        $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
        $new_pass = sha1($_POST['new_pass']);
        $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
        $cpass = sha1($_POST['cpass']);
        $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);
    
        if ($old_pass != $empty_pass) {
            if ($old_pass != $prev_pass) {
                $warning_msg[] = 'Old password does not match'; 
            } elseif ($new_pass != $cpass) {
                $warning_msg[] = 'Password not matched';
            } elseif ($old_pass == $new_pass) {
                $warning_msg[] = 'New password must be different from the old password';
            } else {
                if ($new_pass != $empty_pass) {
                    $update_pass = $conn->prepare("UPDATE `users` SET password = ? WHERE id = ?"); 
                    $update_pass->execute([$cpass, $user_id]);
                    $success_msg[] = 'Password updated successfully';
                } else {
                    $warning_msg[] = 'Please enter a new password';
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
    <link rel="stylesheet" type="text/css" href="../css/user_styles.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <title>the Blue Summer - update profile Page</title> 
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
        <h1>profile update</h1>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Libero facilis, 
            unde dolore assumenda rem error vel,<br>similique iste molestiae, 
            distinctio deserunt commodi a nemo aliquam in ex corporis doloremque cumque.</p>
        <span> <a href="../user_panel/home.php">home</a><i class="bx bx-right-arrow-alt"></i> update profile </span>
    </div>
</div>
<!--------------------------------------------------------------------------------------------------------------->
<section class="form-container">
                <div class="heading">
                    <h1>update profile detail</h1>
                    <img src="../image/separator-img.png" alt="dash-image">
                </div>
                <form action="" method="post" enctype="multipart/form-data" class="register"> 
                    <div class="img-box">
                    <img style="border-radius: 50%;" src="../uploaded_image/<?= $fetch_profile['image']; ?> ">
                    </div>
                <h3>update profile</h3>
                <div class="flex">
                    <div class="cols">
                    <div class="input-field">
                        <p>your name <span>*</span> </p>
                        <input type="text" name="name" placeholder="<?= $fetch_profile['name']; ?>" class="box">
                    </div>
                    <div class="input-field">
                        <p>your email <span>*</span> </p>
                        <input type="email" name="email" placeholder="<?= $fetch_profile['email']; ?>" class="box">
                    </div>
                    <div class="input-field">
                        <p>select pic <span>*</span> </p>
                        <input type="file" name="image" accept="image/*" class="box">
                    </div>
                    <div class="input-field">
                        <p>old password <span>*</span> </p>
                        <input type="password" name="old_pass" placeholder="Enter your old password" class="box">
                    </div>
                    <div class="input-field">
                        <p>new password <span>*</span> </p>
                        <input type="password" name="new_pass" placeholder="Enter your new password" class="box">
                    </div>
                    <div class="input-field">
                        <p>confirm password <span>*</span> </p>
                        <input type="password" name="cpass" placeholder="confirm your password" class="box">
                    </div>
                </div>  
                <br><br>
                <input type="submit" name="submit" value="update profile" class="btn">             
                </form>
            </section>
<!--------------------------------------------------------------------------------------------------------------->

</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="../js/user_script.js"></script>
<?php include '../components/footer.php'; ?>
<?php include '../components/alert.php'; ?>
</html>