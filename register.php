<!DOCTYPE html>
<html>
<head>
<?php
/*
 * Author: Joccelyn Kaufman
 * Final Capstone Project
 * CSC480
 * This is where we register a new account
 */
session_start();?>
<title>Natural Foods Shopping</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="styles.css" type="text/css" rel="stylesheet">
<link href="form.css" type="text/css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body  <?php 
if(isset($_SESSION['username']) && $_SESSION['username']!="'guest'"){
      $str = ' onload="updateLogin()";';
      echo($str);
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
    </li><?php 
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
<form class="natural-grocers-form" action="controller.php" method="POST">
    <div class="form-group">
        <label for="name">First Name:</label>
        <input type="text" id="name" name="reg_name" required placeholder="Enter your First Name">
    </div>
    <div class="form-group">
        <label for="last">Last Name:</label>
        <input type="text" id="last" name="reg_last" required placeholder="Enter your Last Name">
    </div>
    <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" id="username" name="reg_usr" required placeholder="Enter your username">
    </div>
    <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="reg_pass" required placeholder="Enter your password">
    </div>
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="reg_em" required placeholder="Enter your email">
    </div>
    <button type="submit">Create Account</button>
</form>

</div>
</body>
<script>
function updateLogin(){
	var container = document.getElementById("right-container");
	container.innerHTML='<a href="profile.php" style="color:black; text-decoration: none ;"><i class="fa fa-user" aria-hidden="true"></i><p>Welcome Back!</p></a><a href="controller.php?logout=1" ><button style="margin-top:15px; margin-right: 10px;">Logout</button></a>';
}
function validateForm() {
    var username = document.getElementById("username").value;
    var password = document.getElementById("password").value;
    var email = document.getElementById("email").value;

    // Check if all fields are filled
    if (username == "" || password == "" || email == "") {
        alert("All fields are required.");
        return false;
    }

    // Validate email format
    var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    if (!email.match(emailPattern)) {
        alert("Please enter a valid email address.");
        return false;
    }

    return true;
}
</script>
</html>