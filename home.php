<?php
//KYDETO PISHE PAROLA NAPISHI SVOQTA SI PAROLA ZA MYSQL
//INACHE NQMA DA TRYGNE LMAO
//I TOVA SE OTNASQ ZA VS FAILOVE
session_start();
$con = mysqli_connect('localhost','root', 'PAROLA'); //<--- eto tuk
mysqli_select_db($con,'bookstore');
$sql = "SELECT * FROM books WHERE id!=0";
$featured = $con->query($sql);

$sql2 = "SELECT * FROM bestsellers WHERE id!=0";
$featured2 = $con->query($sql2);

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_GET['logout'])){
   unset($user_id);
   session_destroy();
   header('location:login.php');
};

if(isset($_POST['add_to_cart'])){

   $product_name = $_POST['Name'];
   $product_price = $_POST['Price'];
   $product_image = $_POST['Image'];
   
   $select_cart = mysqli_query($con, "SELECT * FROM `cart` WHERE Name = '$product_name' AND user_id = '$user_id'") or die('query failed');

   if(mysqli_num_rows($select_cart) > 0){
      $message[] = 'product already added to cart!';
   }else{
      mysqli_query($con, "INSERT INTO cart(user_id, Name, Price, Image) VALUES('$user_id', '$product_name', '$product_price', '$product_image')") or die('query failed');
      $message[] = 'product added to cart!';
   }

};


if(isset($_GET['remove'])){
   $remove_id = $_GET['remove'];
   mysqli_query($con, "DELETE FROM `cart` WHERE id = '$remove_id'") or die('query failed');
   header('location:home.php');
}
  
if(isset($_GET['delete_all'])){
   mysqli_query($con, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
   header('location:home.php');
}

?>

<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">

<head>
    <link rel="stylesheet" href="StyleSheet1.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src = "https://ajax.com.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title> Green Bookstore </title>
    <link rel="icon" href="images/logo.png" type="image/icon type">

</head>

<body>

<?php
if(isset($message)){
   foreach($message as $message){
      echo '<div class="message" onclick="this.remove();">'.$message.'</div>';
   }
}
?>

    <!-- buton za scrollvane obratno do nachaloto na stranicata -->

    <button onclick="topFunction()" id="myBtn" title="Go to top">
        Go back to top
    </button>


    

</div>


    <!-- --------------------------------------------------------->
    <!-- Header -->
    <div class="header">

        <div class="links">
            <a class="active" href="home.php">Home</a>
            <a href="about.html">About</a>
            <a href="contact.html">Contact</a>
            
        </div>

        <div class="logo">
            <a href="home.php">
                <img id="logo" src="images/bookstore.png" alt="Logo">
            </a>
        </div>

        <div class="box">
            <form name="search">
                <input type="text" class="input" name="txt" onmouseout="this.value = ''; this.blur(); ">

            </form>
            <i class="fa fa-search"> </i>
            
        </div>

        
        <div class="icons">
            <a href="cart.php">
				<i class="fa fa-shopping-cart"></i>
			</a>
            
        </div>


        <div class="user-profile">
            <div class="icons">
                <div class="user">
                    <a href="register.php">
				        <i class="fa fa-user"></i>
			        </a>
                </div>
            </div>

            <?php
            $select_user = mysqli_query($con, "SELECT * FROM `users` WHERE id = '$user_id'") or die('query failed');
            if(mysqli_num_rows($select_user) > 0){
            $fetch_user = mysqli_fetch_assoc($select_user);
            };
            ?>
</div>
            <div class="hide" style="display:none;">
                <p> username : <span><?php echo $fetch_user['name']; ?></span> </p>
                <p> email : <span><?php echo $fetch_user['email']; ?></span> </p> 
                    <div class="flex">
                    <a href="login.php" class="btn">login</a>
                    <a href="register.php" class="option-btn">register</a>
                    <a href="home.php?logout=<?php echo $user_id; ?>" onclick="return confirm('are your sure you want to logout?');" class="delete-btn">logout</a>
                    </div>
            </div>
        </div>
        
        
        
    

    <!--------------------------------------------------------------------------------->
    <!-- Glavnoto sydyrjanie na stranicata -->

    <div class="row">

        <!-- Lqvo sydyrjanie -->

        <div class="column left">

            
            <h1 style="margin:0; padding-bottom:10px;"> Top 10 bestsellers this month: </h1> <br />
            <?php 
            while($product = mysqli_fetch_assoc($featured2)):
            ?>

            <div class="bestsellers">
                <div class="side-cover"> <img src=images\<?= $product['Image']; ?> /> </div>
                <div class="name"> <p> <?= $product['Name']; ?></p> </div>
            </div>

            <?php endwhile; ?>

            

        </div>

        <!------------------------------------------------------------------------------------->
        <!-- Centralno sydyrjanie -->

        

        
        <div class="column middle">
            <?php 
            while($product = mysqli_fetch_assoc($featured)):
            ?>

            <div class="book">
                <form method="post" action="home.php?action=add&code=<?php echo $product['id']; ?>">
			        
                <div class="cover"><img src=images\<?= $product['Image']; ?> /></div>
                <div class="title"><h1> <?= $product['Name']; ?> </h1></div>
                <div class="author"> <p> <?= $product['Author']; ?> </p></div>
                <div class="price"> <p> <?= $product['Price']; ?> </p></div>
                
                
                <input type="hidden" name="Image" value="<?php echo $product['Image']; ?>">
                <input type="hidden" name="Name" value="<?php echo $product['Name']; ?>">
                <input type="hidden" name="Price" value="<?php echo $product['Price']; ?>">
                
      
                <div class="icons">
                    <button type=submit class="btnAddAction" name="add_to_cart"><i class="fa fa-shopping-cart"></i></button>
                    </form>            
                </div>
            </div>
            <?php endwhile; ?>
        </div>

    </div>

    
    
    <!--------------------------------------------------------------------------------->
    <!--Footer-->
   
    <section class="footer">

        <h3> About Us</h3>

        <p>
            The Green Bookstore is a fictional bookstore that sells 9 books and does not exist. </p>
        <p> Follow us on social media! </p>
            <div class="icons">
                <i class="fa fa-facebook"></i>
                <i class="fa fa-twitter"></i>
                <i class="fa fa-instagram"></i>

            </div>

<p> Web Design by Anton Pantov</p>

    </section>

    <!------------------------------------------------------------>
    <!--malko javascript-->

    <script src="Script1.js"></script>
</body>
</html>