<?php
    include '../components/connect.php';

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

        $select_seller = $conn->prepare("SELECT * FROM `sellers` WHERE email = ?");
        $select_seller->execute([$email]);

        if($select_seller->rowCount() > 0){
            $warning_msg[] = 'Email Already Exists!';
        }else{
            if($pass != $cpass){
                $warning_msg[] = 'Confirm password not matched';
            }else{
                $insert_seller = $conn->prepare("INSERT INTO `sellers` (id,name,email,password,image) VALUES(?, ?, ?, ?, ?)");
                $insert_seller->execute([$id, $name, $email, $pass, $rename]);
                move_uploaded_file($image_tmp_name, $image_folder);
                $success_msg[] = 'New seller registered! Please login now';
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
                <p class="link">Already have an account?<a href="../admin_panel/login.php">Login Now</a></p>
                <input type="submit" name="submit" value="Register Now" class="btn">
        </form>
    </div>
    
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="../js/admin_script.js"></script>
<?php include '../components/alert.php'; ?>
</html>