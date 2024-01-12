<?php
session_start();
$conn = mysqli_connect('localhost','root', 'PAROLA');
mysqli_select_db($conn,'bookstore');
if(isset($_POST['submit']))
{
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $cpassword = mysqli_real_escape_string($conn,$_POST['cpassword']);

    $sql2 = "SELECT * FROM bestsellers WHERE featured=1";
    $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    $select = $conn->query($sql);

    if(mysqli_num_rows($select) > 0)
    {
      $message[]='This e-mail is already in use!';
    }
    else
    {
        try{
        mysqli_query($conn, "INSERT INTO users (name, email, password) VALUES
        ('$name', '$email', '$password')");
        $message[]='You signed up successfully!';
        header('location:login.php');
        }
        catch (mysqli_sql_exception $e) { 
            var_dump($e);
            exit; 
         } 
    }

}   
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="StyleSheet1.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src = "https://ajax.com.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <link rel="icon" href="images/logo.png" type="image/icon type">
</head>
<body>

<?php

if(isset($message))
{
    foreach($message as $message)
    {
        echo '<div class="message" onclick="this.remove();">'.$message.'</div>';
    }
}

?>
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


</div>

<div class="row">
    <div class="column-middle">
  <div class="form-container">
    <form action="" method="post">
        <h3> Sign Up </h3>
        <input type="text" name="name" required placeholder="Enter username" class="box">
        <input type="email" name="email" required placeholder="Enter e-mail" class="box">
        <input type="password" name="password" required placeholder="Enter password" class="box">
        <input type="password" name="cpassword" required placeholder="Confirm password" class="box">    
        <input type="submit" name="submit" class="btn" value="Register now">
        <p>Already have an account? <a href="login.php">Sign in now</a></p>
    </form>
  </div>
  </div>
  </div>
</body>
</html>