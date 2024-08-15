<?php
    include '../components/connect.php';

    if(isset($_POST['submit'])){
        
        $email = $_POST['email'];
        $email = filter_var($email, FILTER_SANITIZE_STRING);

        $pass = sha1($_POST['pass']);
        $pass = filter_var($pass, FILTER_SANITIZE_STRING);

        $select_seller = $conn->prepare("SELECT * FROM `sellers` WHERE email = ? AND password = ?");
        $select_seller->execute([$email, $pass]);
        $row = $select_seller->fetch(PDO::FETCH_ASSOC);

        if($select_seller->rowCount() > 0){
            setcookie('seller_id',$row['id'], time() + 60*60*24*30, '/');
            header('location:dashboard.php');
        }else{
                $success_msg[] = 'incorrect email or password';
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
    <div class="form-container">
        <form action="" method="post" enctype="multipart/form-data" class="login">
            <h3>Login Now</h3>
            <div class="flex">
                <div class="cols">
                    <div class="input-field">
                        <p>Your Email<span>*</span></p>
                        <input type="email" name="email" placeholder="Enter Your Email" maxlength="50" require class="box">
                    </div>
                    <div class="input-field">
                        <p>Your Password<span>*</span></p>
                        <input type="password" name="pass" placeholder="Enter Your Password" maxlength="50" require class="box">
                    </div>
                </div>
            </div>
            <p class="link">Do not have an account?<a href="../admin_panel/register.php">Register Now</a></p>
            <input type="submit" name="submit" value="login Now" class="btn">
        </form>
    </div>
    
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="../js/admin_script.js"></script>
<?php include '../components/alert.php'; ?>
</html>