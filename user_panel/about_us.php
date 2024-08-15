<?php
    include '../components/connect.php';
    if(isset($_COOKIE['user_id'])){
        $user_id = $_COOKIE['user_id'];
    }else{
        $user_id = '';
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/user_styles.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <title>the Blue Summer - about us Page</title> 
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
        <h1>about us</h1>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Libero facilis, 
            unde dolore assumenda rem error vel,<br>similique iste molestiae, 
            distinctio deserunt commodi a nemo aliquam in ex corporis doloremque cumque.</p>
        <span> <a href="../user_panel/home.php">home</a><i class="bx bx-right-arrow-alt"></i>About Us </span>
    </div>
</div>
<!--------------------------------------------------------------------------------------------------------------->
<div class="chef">
    <div class="box-container">
        <div class="box">
            <div class="heading">
                <span>Alex Doe</span>
                <h1>Masterchef</h1>
                <img src="../image/separator-img.png" alt="">
            </div>
                <p>Alex’s culinary philosophy centers around seasonality and sustainability. They believe that the best dishes start with the freshest ingredients, sourced locally whenever possible. This commitment to farm-to-table dining not only supports local farmers but also ensures that every plate tells a story of the land from which it came.</p>
                <div class="flex-btn">
                    <a href="" class="btn">explore our menu</a>
                    <a href="../user_panel/menu.php" class="btn">visit our shop</a>
                </div>
         </div>
         <div class="box">
            <img src="../image/ceaf.png" class="img" alt="">
         </div>
    </div>
</div>
<!--------------------------------------------------------------------------------------------------------------->
<div class="chef" style="background-image: url('../image/about_bg.jpg');padding-left: 0">
    <div class="box-container" style=" 
    position: relative;
    background-repeat: no-repeat;
    background-position: center;
    background-size: cover;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    text-align: center;">
        <div class="box">
            <div class="heading">
                <h1 style="            margin-bottom: 0;">our story</h1>
                <img src="../image/separator-img.png" alt="">
            </div>
                <p>Alex’s culinary philosophy centers around seasonality and sustainability. <br>hey believe that the best dishes start with the freshest ingredients, sourced locally whenever possible. <br>
                This commitment to farm-to-table dining not only supports local farmers but also ensures that <br>
                every plate tells a story of the land from which it came.</p>
                    <a href="../user_panel/menu.php" class="btn">our service</a>
         </div>
    </div>
</div>
<!--------------------------------------------------------------------------------------------------------------->
<div class="container">
<div class="box-container">
<div class="img-box">
<img src="../image/about.png">
</div>
<div class="box">
<div class="heading">
<h1>Taking Ice Cream To New Heights</h1>
<img src="../image/separator-img.png">
</div>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam sit amet elementum ante. 
    Sed mattis sapien vel vestibulum lacinia. Class aptent taciti sociosqu ad litora torquent per conubia nostra, 
    per inceptos himenaeos. Fusce a fermentum leo. Integer sem nulla, pretium vel purus vel, 
    venenatis vehicula turpis.</p> <a href="" class="btn">learn more</a> </div>
</div>
</div>
<!--------------------------------------------------------------------------------------------------------------->

<div class="team">
    <div class="heading">
        <span>Our Team</span>
        <h1>Quality & Passion with Our Services</h1>
        <img src="../image/separator-img.png" alt="">
    </div>
    <div class="box-container">
        <div class="box">
            <img src="../image/team-1.jpg" class="img">
            <div class="content">
                <img src="../image/shape-19.png" alt="Shape" class="shap">
                <h2>Ralph Johnson</h2>
                <p>Coffee Chef</p>
            </div>
        </div>

        <div class="box">
            <img src="../image/team-2.jpg" class="img">
            <div class="content">
                <img src="../image/shape-19.png" alt="Shape" class="shap">
                <h2>fiona Johnson</h2>
                <p>pastry Chef</p>
            </div>
        </div>

        <div class="box">
            <img src="../image/team-3.jpg" class="img">
            <div class="content">
                <img src="../image/shape-19.png" alt="Shape" class="shap">
                <h2>tom knelltonns</h2>
                <p>Bun chef</p>
            </div>
        </div>
    </div>
</div>
<!--------------------------------------------------------------------------------------------------------------->
<div class="standers">
    <div class="detail">
        <div class="heading">
            <h1>Our Standards</h1>
            <img src="../image/separator-img.png" alt="Separator Image">
        </div>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit suspendisse</p> <i class="bx bxs-heart"></i>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit suspendisse</p> <i class="bx bxs-heart"></i>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit suspendisse</p> <i class="bx bxs-heart"></i>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit suspendisse</p> <i class="bx bxs-heart"></i>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit suspendisse</p> <i class="bx bxs-heart"></i>
    </div>
</div>
<!--------------------------------------------------------------------------------------------------------------->
<div class="testimonial">
    <div class="heading">
        <h1>Testimonial</h1>
        <img src="../image/separator-img.png" alt="Separator Image">
    </div>
    <div class="testimonial-container">
        <div class="slide-row" id="slide">
            <div class="slide-col">
                <div class="user-text">
                    <p>Zen Doan is a business analyst, entrepreneur, media proprietor, and investor. She is also known as a best-selling book author.</p>
                    <h2>Zen</h2>
                    <p>Author</p>
                </div>
                <div class="user-img">
                    <img src="../image/testimonial (1).jpg" alt="Zen Doan">
                </div>
            </div>

            <div class="slide-col">
                <div class="user-text">
                    <p>Zen Doan is a business analyst, entrepreneur, media proprietor, and investor. She is also known as a best-selling book author.</p>
                    <h2>Zen</h2>
                    <p>Author</p>
                </div>
                <div class="user-img">
                    <img src="../image/testimonial (2).jpg" alt="Zen Doan">
                </div>
            </div>

            <div class="slide-col">
                <div class="user-text">
                    <p>Zen Doan is a business analyst, entrepreneur, media proprietor, and investor. She is also known as a best-selling book author.</p>
                    <h2>Zen</h2>
                    <p>Author</p>
                </div>
                <div class="user-img">
                    <img src="../image/testimonial (3).jpg" alt="Zen Doan">
                </div>
            </div>

            <div class="slide-col">
                <div class="user-text">
                    <p>Zen Doan is a business analyst, entrepreneur, media proprietor, and investor. She is also known as a best-selling book author.</p>
                    <h2>Zen</h2>
                    <p>Author</p>
                </div>
                <div class="user-img">
                    <img src="image/testimonial (4).jpg" alt="Zen Doan">
                </div>
            </div>
        </div>
    </div>
    <div class="indicator">
        <span class="btn1 active"></span>
        <span class="btn1"></span>
        <span class="btn1"></span>
        <span class="btn1"></span>
    </div>
</div>
<!--------------------------------------------------------------------------------------------------------------->
<div class="mission">
    <div class="box-container">
       <div class="box">
       <div class="heading">
            <h1>our mission</h1>
            <img src="../image/separator-img.png" alt="">
        </div>
        <div class="detail">
            <div class="img-box">
                <img src="../image/mission.webp" alt="">
            </div>
            <div>
            <h2>mexicon chocolate</h2>
            <p>Mexican chocolate is known for its unique flavor and grainy texture, 
                often including spices like cinnamon, almonds, or vanilla. 
                It’s used in both sweet and savory dishes.</p>
            </div>
        </div>
        <div class="detail">
            <div class="img-box">
                <img src="../image/mission0.jpg" alt="">
            </div>
            <div>
            <h2>vanila with honey </h2>
            <p>Mexican chocolate is known for its unique flavor and grainy texture, 
                often including spices like cinnamon, almonds, or vanilla. 
                It’s used in both sweet and savory dishes.</p>
            </div>
        </div>
        <div class="detail">
            <div class="img-box">
                <img src="../image/mission1.webp" alt="">
            </div>
            <div>
            <h2>pappermint chip</h2>
            <p>Mexican chocolate is known for its unique flavor and grainy texture, 
                often including spices like cinnamon, almonds, or vanilla. 
                It’s used in both sweet and savory dishes.</p>
            </div>
        </div>
        <div class="detail">
            <div class="img-box">
                <img src="../image/mission2.webp" alt="">
            </div>
            <div>
            <h2>raspberry sharbat</h2>
            <p>Mexican chocolate is known for its unique flavor and grainy texture, 
                often including spices like cinnamon, almonds, or vanilla. 
                It’s used in both sweet and savory dishes.</p>
            </div>
        </div>
       </div>
       <div class="box">
        <img src="../image/form.png" class="img" alt="">
       </div>
    </div>
</div>
<!--------------------------------------------------------------------------------------------------------------->

</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="../js/user_script.js"></script>
<?php include '../components/footer.php'; ?>
</html>