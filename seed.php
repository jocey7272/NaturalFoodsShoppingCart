<!DOCTYPE html>
<html>
<head>
<?php
/*
 * Author: Joccelyn Kaufman
 * Final Capstone Project
 * CSC480
 * Here we get a list of all products in the seed category.
 */
include 'controller.php';
?>
<title>Natural Foods: Seeds</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="styles.css" type="text/css" rel="stylesheet">
<link href="styles3.css" type="text/css" rel="stylesheet">
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
      ?>><?php echo("<script>console.log('SESSION IS: '+".$_SESSION['username'].");</script>");?>
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
  ?>
  <li class="navbar-item flexbox-left">
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
<p class="page-purpose">Seeds</p>
<div class="product-grid">
        <?php
        $seedarr = $theDBA->getAllSeeds();
        $index=(int)$_SESSION['index'];
        $users = $theDBA->getAllUsers();
        $user = openssl_decrypt($users[$index]['hashuser'], $method, $key);
        if($_SESSION['username']=="'guest'"){
            $user ='guest';
        }
        // Loop through the random picks and display them
        for($i=0; $i<count($seedarr); $i++){
            $product = $seedarr[$i];
            echo '
            <div class="product-card" >
                <img onclick="window.location.href=\'product.php?productId=' . $product["id"] . '\'" class="product-image" src="products/'.$product["productImage"].'" alt="'.$product['productName'].'">
                <div class="product-info">
                    <h3 class="product-name">'.$product['productName'].'</h3>
                    <p class="product-price">Only $'. $product['productPrice'].'/lb!</p>
                    <button class="add-to-cart-btn" onclick="addToCart('.$product["id"].', '. "'". $user."'".')">Add to Cart</button>
                </div>
            </div>';
        }
        ?>
    </div>
</div>
</body>
<script>
function updateLogin(){
	var container = document.getElementById("right-container");
	container.innerHTML='<a href="profile.php" style="color:black; text-decoration: none ;"><i class="fa fa-user" aria-hidden="true"></i><p>Welcome Back!</p></a><a href="controller.php?logout=1" ><button style="margin-top:15px; margin-right: 10px;">Logout</button></a>';
}
function addToCart(id, username){
	console.log("ID is:"+id);
	console.log("username is: "+username);
	var anObj = new XMLHttpRequest();
	anObj.open("POST", "controller.php", true);
	anObj.setRequestHeader("Content-type", "application/x-www-form-urlencoded"); 
	anObj.send("addToCart=true&productId="+id+"&username="+username);
	anObj.onreadystatechange = function () {
	      if (anObj.readyState == 4 && anObj.status == 200) {
		     var msg = JSON.parse(anObj.responseText);
		     if(msg=="MAX"){
				console.log("MAX REACHED!");
				return;
		     }
		     console.log("PASSED: ");
		     return;
		     
	      }
	}
}
</script>
</html>