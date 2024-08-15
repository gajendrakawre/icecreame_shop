<?php
    include '../components/connect.php';
    if(isset($_COOKIE['seller_id'])){
        $seller_id = $_COOKIE['seller_id'];
    }else{
        $seller_id = '';
        header('location:login.php');
    }

    if (isset($_POST['submit'])) {
        $select_seller = $conn->prepare("SELECT * FROM `sellers` WHERE id = ? LIMIT 1");
        $select_seller->execute([$seller_id]);
        $fetch_seller = $select_seller->fetch(PDO::FETCH_ASSOC);
        $prev_pass = $fetch_seller['password'];
        $prev_image = $fetch_seller['image'];
        $name = $_POST['name'];
        $name = filter_var($name, FILTER_SANITIZE_STRING);
        $email = $_POST['email'];
        $email = filter_var($email, FILTER_SANITIZE_STRING);
        
        // Update name
        if (!empty($name)) {
            $update_name = $conn->prepare("UPDATE sellers SET name = ? WHERE id = ?"); 
            $update_name->execute([$name, $seller_id]); 
            $success_msg[] = 'Username updated successfully';
        }
    
        // Update email
        if (!empty($email)) {
            $select_email = $conn->prepare("SELECT * FROM sellers WHERE id = ? AND email = ?"); 
            $select_email->execute([$seller_id, $email]);
    
            if ($select_email->rowCount() > 0) {
                $warning_msg[] = 'Email already exists';
            } else {
                $update_email = $conn->prepare("UPDATE sellers SET email = ? WHERE id = ?"); 
                $update_email->execute([$email, $seller_id]); 
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
            $image_folder = '../uploaded_image/' . $rename;
    
            if (!empty($image)) {
                if ($image_size > 2000000) {
                    $warning_msg[] = 'Image size is too large';
                } else {
                    $update_image = $conn->prepare("UPDATE sellers SET image = ? WHERE id = ?"); 
                    $update_image->execute([$rename, $seller_id]); 
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
                    $update_pass = $conn->prepare("UPDATE `sellers` SET password = ? WHERE id = ?"); 
                    $update_pass->execute([$cpass, $seller_id]);
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
            <div class="updated">
            <section class="form-container">
                <div class="heading">
                    <h1>update profile detail</h1>
                    <img src="../image/separator-img.png" alt="dash-image">
                </div>
                <form action="" method="post" enctype="multipart/form-data" class="register"> 
                    <div class="img-box">
                    <img src="../uploaded_image/<?= $fetch_profile['image']; ?>">
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
            </div>
        </div>


</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="../js/admin_script.js"></script>
<?php include '../components/alert.php'; ?>
</html>