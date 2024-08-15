<?php
    include '../components/connect.php';
    if (isset($_COOKIE['seller_id'])) {
        $seller_id = $_COOKIE['seller_id'];
    } else {
        $seller_id = '';
        header('location:login.php');
        exit();
    }

    //update order from database
    if (isset($_POST['update_order'])) {
        $order_id = $_POST['order_id'];
        $order_id = filter_var($order_id, FILTER_SANITIZE_STRING);
        $update_payment = $_POST['update_payment'];
        $update_payment = filter_var($update_payment, FILTER_SANITIZE_STRING);
        $update_pay = $conn->prepare("UPDATE orders SET payment_status = ? WHERE id = ?"); 
        $update_pay->execute([$update_payment, $order_id]); 
        $success_msg[] = 'order payment status updated';
    }

    //delete order
    if (isset($_POST['delete_order'])) {
        $delete_id = $_POST['order_id'];
        $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING); // Corrected variable name here
    
        // Verify if the order exists
        $verify_delete = $conn->prepare("SELECT * FROM `orders` WHERE id = ?"); 
        $verify_delete->execute([$delete_id]);
    
        if ($verify_delete->rowCount() > 0) {
            // Delete the order from the database
            $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?"); 
            $delete_order->execute([$delete_id]);
    
            $success_msg[] = 'Order deleted successfully';
        } else {
            $warning_msg[] = 'Order already deleted or does not exist';
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
                <h1>Total Orders Placed</h1>
                <img src="../image/separator-img.png" alt="dash-image">
            </div>
            <div class="box-container">
            <?php
                $select_order = $conn->prepare("SELECT * FROM `orders` WHERE seller_id = ?"); 
                $select_order->execute([$seller_id]);
                if ($select_order->rowCount() > 0) {
                    while($fetch_order = $select_order->fetch(PDO::FETCH_ASSOC)){
            ?>
            <div class="box">
                <div class="status" style="color: <?php echo ($fetch_order['status'] == 'in progress') ? 'limegreen' : 'red'; ?>">
                    <?= $fetch_order['status']; ?>
                </div> 
                <div class="details">
                    <p>User name: <span><?= $fetch_order['name']; ?></span></p> 
                    <p>User ID: <span><?= $fetch_order['user_id']; ?></span></p> 
                    <p>Placed on: <span><?= $fetch_order['date']; ?></span></p> 
                    <p>User number: <span><?= $fetch_order['number']; ?></span></p> 
                    <p>User email: <span><?= $fetch_order['email']; ?></span></p> 
                    <p>Total price: <span><?= $fetch_order['price']; ?></span></p>
                    <p>Payment method: <span><?= $fetch_order['method']; ?></span></p>
                    <p>User address: <span><?= $fetch_order['address']; ?></span></p>
                </div>
                <form action="" method="post">
                    <input type="hidden" name="order_id" value="<?= $fetch_order['id']; ?>"> 
                    <select name="update_payment" class="box" style="width: 90%;">
                        <option disabled selected> <?= $fetch_order['payment_status']; ?></option> 
                        <option value="pending">Pending</option>
                        <option value="order delivered">Order Delivered</option>
                    </select>
                    <div class="flex-btn">
                        <input type="submit" name="update_order" value="Update Payment" class="btn">
                        <input type="submit" name="delete_order" value="Delete Order" class="btn" onclick="return confirm('delete this order');">
                    </div> 
                </form>
            </div>
            <?php
                }
            } else {
                echo '
                    <div class="empty">
                    <p>No orders placed yet!</p>
                    </div>
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
