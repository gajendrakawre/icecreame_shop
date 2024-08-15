<?php
    include '../components/connect.php';

    if(isset($_COOKIE['user_id'])){
        $user_id = $_COOKIE['user_id'];
    }
    else{
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
    <title>the Blue Summer - Home Page</title> 
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

        <!----------------------slider section--------------------->
<div class="slider-container">
    <div class="slider">
        <div class="slideBox active">
            <div class="textBox">
                <h1>We pride ourselves on <br>exceptional flavours</h1>
                <a href="../user_panel/menu.php" class="btn">Shop now</a>
            </div>
            <div class="imgBox">
                <img src="../image/slider.jpg" alt="Slider Image 1">
            </div>
        </div>
        <div class="slideBox">
            <div class="textBox">
                <h1>Cold treats are my kind <br>of comfort food<br>Exceptional flavours</h1>
                <a href="../user_panel/menu.php" class="btn">Shop now</a>
            </div>
            <div class="imgBox">
                <img src="../image/slider0.jpg" alt="Slider Image 2">
            </div>
        </div>
    </div>
    <ul class="controls">
        <li onclick="nextSlide();" class="next"><i class="bx bx-right-arrow-alt"></i></li>
        <li onclick="prevSlide();" class="prev"><i class="bx bx-left-arrow-alt"></i></li>
    </ul>
</div>

<!---------------------------slider---------------------------------->
    <div class="service">
        <div class="box-container">
            <div class="box">
                <div class="icon">
                    <div class="icon-box">
                        <img src="../image/services.png" alt="" class="img1">
                        <img src="../image/services (1).png" alt="" class="img2">
                    </div>
                </div>
                <div class="detail">
                    <h4>delievery</h4>
                    <span>100% secure</span>
                </div>
           </div>
           <div class="box">
                    <div class="icon">
                        <div class="icon-box">
                            <img src="../image/services (2).png" alt="" class="img1">
                            <img src="../image/services (3).png" alt="" class="img2">
                        </div>
                    </div>
                    <div class="detail">
                        <h4>support</h4>
                        <span>24*7 hours</span>
                    </div>
                </div>

                <div class="box">
                    <div class="icon">
                        <div class="icon-box">
                            <img src="../image/services (5).png" alt="" class="img1">
                            <img src="../image/services (6).png" alt="" class="img2">
                        </div>
                    </div>
                    <div class="detail">
                        <h4>payment</h4>
                        <span>100% secure</span>
                    </div>
                </div>

                <div class="box">
                    <div class="icon">
                        <div class="icon-box">
                            <img src="../image/services (7).png" alt="" class="img1">
                            <img src="../image/services (8).png" alt="" class="img2">
                        </div>
                    </div>
                    <div class="detail">
                        <h4>gift service</h4>
                        <span>support gift service</span>
                    </div>
                </div>

                <div class="box">
                    <div class="icon">
                        <div class="icon-box">
                            <img src="../image/service.png" alt="" class="img1">
                            <img src="../image/service (1).png" alt="" class="img2">
                        </div>
                    </div>
                    <div class="detail">
                        <h4>returns</h4>
                        <span>24*7 free returns</span>
                    </div>
                </div>

                <div class="box">
                    <div class="icon">
                        <div class="icon-box">
                            <img src="../image/services.png" alt="" class="img1">
                            <img src="../image/services (1).png" alt="" class="img2">
                        </div>
                    </div>
                    <div class="detail">
                        <h4>deliever</h4>
                        <span>100% secure</span>
                    </div>
                </div>
            </div>
        </div>
        <!---------------------------slider end---------------------------------->

        <!---------------------------features---------------------------------->
        <div class="categories">
            <div class="heading">
                <h1>category features</h1>
                <img src="../image/separator-img.png" alt="">
            </div>
            <div class="box-container">
                <div class="box">
                    <img src="../image/categories.jpg" alt="">
                    <a href="munu.php" class="btn"> coconuts</a>
                </div>
                <div class="box">
                <img src="../image/categories0.jpg" alt="">
                    <a href="munu.php" class="btn">chocolate</a>
                </div>
                <div class="box">
                <img src="../image/categories2.jpg" alt="">
                    <a href="munu.php" class="btn">strawberry</a>
                </div>
                <div class="box">
                <img src="../image/categories1.jpg" alt="">
                    <a href="munu.php" class="btn">cone</a>
                </div>
            </div>
        </div>
        <!---------------------------features end---------------------------------->
        <!------------------------------------------------------------->
        <img src="../image/menu-banner.jpg" alt="" class="banner">
        <div class="taste">
            <div class="heading">
                <span>Taste</span>
                <h1>buy any icecream @ get one free</h1>
                <img src="../image/separator-img.png" alt="">
            </div>
            <div class="box-container">
                <div class="box">
                    <img src="../image/taste.webp" alt="">
                    <div class="detail">
                        <h2>natural sweetness</h2>
                        <h1>vanila</h1>
                    </div>
                </div>

                <div class="box">
                    <img src="../image/taste0.webp" alt="">
                    <div class="detail">
                        <h2>natural sweetness</h2>
                        <h1>matcha</h1>
                    </div>
                </div>

                <div class="box">
                    <img src="../image/taste1.webp" alt="">
                    <div class="detail">
                        <h2>natural sweetness</h2>
                        <h1>blueberry</h1>
                    </div>
                </div>
            </div>
        </div>
        <!------------------------------------------------------------->
        <div class="ice-container">
            <div class="overlay"></div>
            <div class="detail">
                <h1>Ice cream is cheaper than <br>therapy for strees</h1>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.
                    <br>Sequi explicabo architecto aperiam beatae obcaecati ducimus rem veritatis sapiente enim quis excepturi, 
                    <br>adipisci harum itaque illo nam fugiat suscipit quia praesentium!</p>
                    <a href="munu.php" class="btn">shop now</a>
            </div>
        </div>
                <!------------------------------------------------------------->

    <div class="taste2">
    <div class="t-banner">
        <div class="overlay"></div>
        <div class="detail">
            <h1>Find Your Taste of Desserts</h1>
            <p>Treat them to a delicious treat and send them some luck 'o the Irish too</p>
            <a href="menu.php" class="btn">Shop Now</a>
        </div>
    </div>
    <div class="box-container">
        <div class="box">
            <div class="box-overlay"></div>
            <img src="../image/type4.jpg" alt="Strawberry Dessert">
            <div class="box-details fadeIn-button">
                <h1>Strawberry</h1>
                <p>Find the taste of desserts</p>
                <a href="menu.php" class="btn">Explore More</a>
            </div>
        </div>

        <div class="box">
            <div class="box-overlay"></div>
            <img src="../image/type.avif" alt="Chocolate Dessert">
            <div class="box-details fadeIn-button">
                <h1>Chocolate</h1>
                <p>Find the taste of desserts</p>
                <a href="menu.php" class="btn">Explore More</a>
            </div>
        </div>

        <div class="box">
            <div class="box-overlay"></div>
            <img src="../image/type5.png" alt="Vanilla Dessert">
            <div class="box-details fadeIn-button">
                <h1>Vanilla</h1>
                <p>Find the taste of desserts</p>
                <a href="menu.php" class="btn">Explore More</a>
            </div>
        </div>

        <div class="box">
            <div class="box-overlay"></div>
            <img src="../image/type2.png" alt="Fruit Dessert">
            <div class="box-details fadeIn-button">
                <h1>Fruit</h1>
                <p>Find the taste of desserts</p>
                <a href="menu.php" class="btn">Explore More</a>
            </div>
        </div>

        <div class="box">
            <div class="box-overlay"></div>
            <img src="../image/type0.avif" alt="Caramel Dessert">
            <div class="box-details fadeIn-button">
                <h1>Caramel</h1>
                <p>Find the taste of desserts</p>
                <a href="menu.php" class="btn">Explore More</a>
            </div>
        </div>

        <div class="box">
            <div class="box-overlay"></div>
            <img src="../image/type0.jpg" alt="Strawberry Dessert">
            <div class="box-details fadeIn-button">
                <h1>Strawberry</h1>
                <p>Find the taste of desserts</p>
                <a href="menu.php" class="btn">Explore More</a>
            </div>
        </div>
    </div>
</div>

                <!------------------------------------------------------------->
<div class="flavor">
    <div class="box-container">
        <img src="../image/left-banner2.webp" alt="">
        <div class="detail">
            <h1>Hot Deal ! Sale Up To <span>20% off </span></h1>
            <p>expired</p>
            <a href="menu.php" class="btn">shop now</a>
        </div>
    </div>
</div>
                <!------------------------------------------------------------->

<div class="usage">
    <div class="heading">
        <h1>How It Works</h1>
        <img src="../image/separator-img.png" alt="Separator Image">
    </div>
    <div class="row">
        <div class="box-container">
            <div class="box">
                <img src="../image/icon.avif" alt="Scoop Ice-Cream Icon">
                <div class="detail">
                    <h3>Scoop Ice-Cream</h3>
                    <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. 
                        Rem dolor nihil dicta eveniet quam nam explicabo, 
                        natus labore quia cupiditate.</p>
                </div>
            </div>

            <div class="box">
                <img src="../image/icon0.avif" alt="Scoop Ice-Cream Icon">
                <div class="detail">
                    <h3>Scoop Ice-Cream</h3>
                    <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. 
                        Rem dolor nihil dicta eveniet quam nam explicabo, 
                        natus labore quia cupiditate.</p>
                </div>
            </div>

            <div class="box">
                <img src="../image/icon1.avif" alt="Scoop Ice-Cream Icon">
                <div class="detail">
                    <h3>Scoop Ice-Cream</h3>
                    <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. 
                        Rem dolor nihil dicta eveniet quam nam explicabo, 
                        natus labore quia cupiditate.</p>
                </div>
            </div>
        </div>

        <img src="../image/sub-banner.png" class="divider" alt="">

        <div class="box-container">
            <div class="box">
                <img src="../image/icon2.avif" alt="Scoop Ice-Cream Icon">
                <div class="detail">
                    <h3>Scoop Ice-Cream</h3>
                    <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. 
                        Rem dolor nihil dicta eveniet quam nam explicabo, 
                        natus labore quia cupiditate.</p>
                </div>
            </div>

            <div class="box">
                <img src="../image/icon3.avif" alt="Scoop Ice-Cream Icon">
                <div class="detail">
                    <h3>Scoop Ice-Cream</h3>
                    <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Rem dolor nihil dicta eveniet quam nam explicabo, 
                        natus labore quia cupiditate.</p>
                </div>
            </div>

            <div class="box">
                <img src="../image/icon4.avif" alt="Scoop Ice-Cream Icon">
                <div class="detail">
                    <h3>Scoop Ice-Cream</h3>
                    <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. 
                        Rem dolor nihil dicta eveniet quam nam explicabo, 
                        natus labore quia cupiditate.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Usage section end -->
<div class="pride">
    <div class="detail">
        <h1>We Pride Ourself On<br>Exceptional Flavor.</h1>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. <br>Mollitia quia consectetur, 
            ad temporibus natus corporis amet </p>
            <a href="menu.php" class="btn">shop now</a>
    </div>
</div> 
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="../js/user_script.js"></script>
<?php include '../components/footer.php'; ?>
</html>