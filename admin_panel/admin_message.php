<?php
    include '../components/connect.php';
    if(isset($_COOKIE['seller_id'])){
        $seller_id = $_COOKIE['seller_id'];
    }else{
        $seller_id = '';
        header('location:login.php');
        exit();
    }

    //delete message from database
if (isset($_POST['delete_msg'])) {
        $delete_id = $_POST['delete_id'];
        $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);
        $verify_delete = $conn->prepare("SELECT * FROM `message` WHERE id = ?"); 
        $verify_delete->execute([$delete_id]);
        if ($verify_delete->rowCount() > 0) {
            $delete_msg = $conn->prepare("DELETE FROM `message` WHERE id = ?"); 
            $delete_msg->execute([$delete_id]);
            $success_msg[] ='message deleted successfully';
        }else{  
            $warning_msg[] = 'message already deleted';
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
    <title>Edit Product</title>
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
            <section class="order-container">
                <div class="heading">
                    <h1>Unread massage</h1>
                    <img src="../image/separator-img.png" alt="dash-image">
                </div>
                <div class="box-container">
                <?php
                $select_message = $conn->prepare("SELECT * FROM `message`"); 
                $select_message->execute();
                if ($select_message->rowCount() > 0) { 
                    while ($fetch_message = $select_message->fetch(PDO::FETCH_ASSOC)){

                ?>
                <div class="box">
                <h3 class="name"><?= $fetch_message['name']; ?></h3> 
                <h4><?= $fetch_message['subject']; ?></h4>
                <p><?= $fetch_message['message']; ?></p>
                <form action="" method="post">
                <input type="hidden" name="delete_id" value="<?= $fetch_message['id']; ?>"> 
                <input type="submit" name="delete_msg" value="delete message" class="btn" onclick="return confirm('delete this message');">
                </form>
                </div> 
                <?php
                        }
                    }else{
                            echo'
                                <div class="empty">
                                <p>no message added yet!</p>
                                </div.
                            ';
                    }
                
                    ?>
                </div>
            </section>
        </div>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="../js/admin_script.js"></script>
<?php include '../components/alert.php'; ?>
</html>
