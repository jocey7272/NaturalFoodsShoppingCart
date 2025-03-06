<!DOCTYPE html>
<html>
<head>
<?php 
/*
 * Author: Joccelyn Kaufman
 * Final Capstone Project
 * CSC480
 * This is the profile page, we can update our profile info here.
 */
include 'controller.php';
if($_SESSION['username']=="'guest'"){
    header("Location: index.php");
    return;
}
?>
<title>Natural Foods: Profile</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="styles.css" type="text/css" rel="stylesheet">
<link href="styles2.css" type="text/css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<script>
    </script>
<body  <?php 
if(isset($_SESSION['username']) && $_SESSION['username']!="'guest'"){
      $str = ' onload="updateLogin()";';
      echo($str);
}
else{
    session_start();
    $_SESSION['username']= "'guest'";
}
      ?>>
<!-- Navbar -->
<nav id="navbar">
  <ul class="navbar-items flexbox-col">
    <li class="navbar-logo flexbox-left">
      <a class="navbar-item-inner flexbox">
      <i class="fa fa-bars" aria-hidden="true"></i>
      </a>
    </li>
    <li class="navbar-item flexbox-left">
      <a class="navbar-item-inner flexbox-left" href="search.php">
        <span class="link-text">Search</span>
      </a>
    </li>
    <li class="navbar-item flexbox-left">
      <a class="navbar-item-inner flexbox-left" href="index.php">
        <span class="link-text">Home</span>
      </a>
    </li>
     <?php 
     /*If user is logged in, display 2 more menu options: profile & or manage if they are admin.
      * 
      */
     if(isset($_SESSION['username']) && $_SESSION['username']!="'guest'"){
      $str = '<li class="navbar-item flexbox-left">
      <a class="navbar-item-inner flexbox-left" href="profile.php">
        <span class="link-text">Profile</span>
      </a>
    </li>';
      echo($str);
      if(isset($_SESSION['userType'])=='Admin'){
          $str = '    <li class="navbar-item flexbox-left">
      <a class="navbar-item-inner flexbox-left" href="manage.php">
      <span class="link-text">Manage</span>
      </a>
    </li>';
          echo($str);
      }
  }
  ?><li class="navbar-item flexbox-left">
      <a class="navbar-item-inner flexbox-left" href="seed.php">
        <span class="link-text">Seeds</span>
      </a>
    </li>
    <li class="navbar-item flexbox-left">
      <a class="navbar-item-inner flexbox-left" href="nut.php">
        <span class="link-text">Nuts</span>
      </a>
    </li>
    <li class="navbar-item flexbox-left">
      <a class="navbar-item-inner flexbox-left" href="vegetable.php">
        <span class="link-text">Vegetables</span>
      </a>
    </li>
    <li class="navbar-item flexbox-left">
      <a class="navbar-item-inner flexbox-left" href="cart.php">
        <span class="link-text">Cart</span>
      </a>
    </li>
  </ul>
</nav>
<div class="container"> 
 <div id="right-container" class="right-button-container">
        <a href="login.php">
            <button style="margin-right: 10px;">Login</button>
        </a>
        <a href="register.php">
            <button>Register</button>
        </a>
    </div>
<h1 class="main-heading">Natural Foods</h1>
<h2 class="sub-heading">Fresh, Organic, and Local</h2>
<p class="page-purpose">Your Profile</p>
<div class="profile-container">
      <div class="profile-image">
        <img src="products/user.png" alt="User Profile Image">
      </div>
      <?php 
      $index=(int)$_SESSION['index'];
      $users = $theDBA->getAllUsers();
      $user = openssl_decrypt($users[$index]['hashuser'], $method, $key);
      $email = openssl_decrypt($users[$index]['hashemail'], $method, $key);
      $first = openssl_decrypt($users[$index]['hashfirst'], $method, $key);
      $last = openssl_decrypt($users[$index]['hashlast'], $method, $key);
      $id = $users[$index]['id'];
      $address = openssl_decrypt($users[$index]['hashaddress'], $method, $key);
      $city = openssl_decrypt($users[$index]['hashcity'], $method, $key);
      $state = openssl_decrypt($users[$index]['hashstate'], $method, $key);
      $zip = openssl_decrypt($users[$index]['hashzip'], $method, $key);
      ?>
      <div class="profile-form">
        <form action="controller.php" method="POST">
          <div class="form-row">
            <div class="form-group">
              <label for="username" class="form-label">Username *</label>
              <input style="background-color: lightgray;" autocomplete="off" type="text" name="username" id="username" class="form-input" required value="<?php echo($user); ?>" readonly>
            </div>
            <div class="form-group">
              <label for="email" class="form-label">Email *</label>
              <input type="email" name="email" id="email" autocomplete="off" class="form-input" required value="<?php echo($email); ?>">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="first-name" class="form-label">First Name *</label>
              <input type="text" name="first_name" id="first-name" autocomplete="off" class="form-input" required value="<?php echo($first); ?>">
            </div>
            <div class="form-group">
              <label for="last-name" class="form-label">Last Name *</label>
              <input type="text" name="last_name" id="last-name" autocomplete="off" class="form-input" required value="<?php echo($last); ?>"/>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="address" class="form-label">Address</label>
              <input type="text" name="address" id="address" class="form-input" autocomplete="off" <?php if($address!=""){ echo('value="'. $address.'"') ;} ?> placeholder="Address">
            </div>
            <div class="form-group">
              <label for="city" class="form-label">City</label>
              <input type="text" name="city" id="city" class="form-input" autocomplete="off" <?php if($city!=""){ echo('value="'. $city.'"') ;} ?> placeholder="City">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="state" class="form-label">State</label>
              <input type="text" name="state" id="state" class="form-input" <?php if($state!=""){ echo('value="'. $state.'"') ;} ?> placeholder="State">
            </div>
            <div class="form-group">
              <label for="zip" class="form-label">Zip Code</label>
              <input type="text" name="zip" id="zip" class="form-input" <?php if($zip!=""){ echo('value="'. $zip.'"') ;} ?> placeholder="Zip Code">
            </div>
          </div>
          <div class="form-buttons">
          	<input type="hidden" name="profileUpdate" <?php echo('value="'. $id.'"'); ?>>
            <button type="submit" class="submit-btn">Save Changes</button>
          </div>
        </form>
      </div>
      </div></div>
</body>
<script>
function updateLogin(){
	var container = document.getElementById("right-container");
	container.innerHTML='<a href="profile.php" style="color:black; text-decoration: none ;"><i class="fa fa-user" aria-hidden="true"></i><p>Welcome Back!</p></a><a href="controller.php?logout=1" ><button style="margin-top:15px; margin-right: 10px;">Logout</button></a>';
}
</script>
</html>