<!DOCTYPE html>
<html>
<head>
<?php 
/*
 * Author: Joccelyn Kaufman
 * Final Capstone Project
 * CSC480
 * This is the admin dashboard where we can add items to the database.
 */
include 'controller.php';
if(!isset($_SESSION['username'])){
    header("Location: index.php");
    return;
}
else{
    if($_SESSION['userType']!="Admin"){
      header("Location: index.php");
    }
}
?>
<title>Natural Foods: Admin Panel</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="styles.css" type="text/css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<script>
    </script>
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
<p class="page-purpose">Admin Panel</p>
<form action="controller.php" method="POST" enctype="multipart/form-data" onsubmit="validateForm(event)">
        <label for="productName">Product Name <span class="error" id="nameError"></span></label>
        <input type="text" id="productName" name="productName" placeholder="Enter product name" required><br>
        <label for="productDescription">Product Description</label>
        <textarea id="productDescription" name="productDescription" rows="4" placeholder="Enter product description"></textarea><br>
        <label for="productPrice">Product Price ($) <span class="error" id="priceError"></span></label>
        <input type="number" id="productPrice" name="productPrice" step="0.01" placeholder="Enter product price" required><br>
        <label for="productStock">Product Stock <span class="error" id="stockError"></span></label>
        <input type="number" id="productStock" name="productStock" placeholder="Enter product stock" required><br>
        <label for="productCategory">Product Category</label>
    <select id="productCategory" name="productCategory" required>
        <option value="vegetable">Vegetable</option>
        <option value="seed">Seed</option>
        <option value="nut">Nut</option>
    </select><br>
        <label for="productImage">Product Image <span class="error" id="imageError"></span></label>
        <input type="file" id="productImage" name="productImage" accept=".jpeg, .jpg, .png"><br>
        <button type="submit">Create Product</button>
    </form>
</div>
</body>
<script>
function updateLogin(){
	var container = document.getElementById("right-container");
	container.innerHTML='<a href="profile.php" style="color:black; text-decoration: none ;"><i class="fa fa-user" aria-hidden="true"></i><p>Welcome Back!</p></a><a href="controller.php?logout=1" ><button style="margin-top:15px; margin-right: 10px;">Logout</button></a>';
}
function validateForm(event) {
    const productName = document.getElementById('productName').value.trim();
    const productPrice = document.getElementById('productPrice').value.trim();
    const productStock = document.getElementById('productStock').value.trim();
    const productImage = document.getElementById('productImage').value;
    const fileExtension = productImage.split('.').pop().toLowerCase();

    let isValid = true;
    const errorMessages = document.querySelectorAll('.error');
    errorMessages.forEach(msg => msg.textContent = '');

    if (!productName) {
        document.getElementById('nameError').textContent = 'Product name is required.';
        isValid = false;
    }
    if (!productPrice || isNaN(productPrice) || parseFloat(productPrice) <= 0) {
        document.getElementById('priceError').textContent = 'Valid product price is required.';
        isValid = false;
    }
    if (!productStock || isNaN(productStock) || parseInt(productStock) < 0) {
        document.getElementById('stockError').textContent = 'Valid product stock is required.';
        isValid = false;
    }
    if (productImage && !['jpeg', 'jpg', 'png'].includes(fileExtension)) {
        document.getElementById('imageError').textContent = 'Image must be a JPEG, JPG, or PNG file.';
        isValid = false;
    }

    if (!isValid) {
        event.preventDefault(); // Prevent form submission
    }
}
</script>
</html>