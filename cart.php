<!DOCTYPE html>
<html>
<head>
<?php
/*
 * Author: Joccelyn Kaufman
 * Final Capstone Project
 * CSC480
 * This is the shopping cart for my website.
 */
include 'controller.php';
?>
<title>Natural Foods: Cart</title>
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
        $_SESSION['username']="'guest'";
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
     if(isset($_SESSION['username']) && $_SESSION['username']!="guest"){
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
<p class="page-purpose">Your Cart</p>
<div id="cart-items-container">
       <div id="cart-container" style="float:left;width:50%; display:inline;" class="cart-container">
            <?php
            if ($_SESSION['username'] === "'guest'") {
                if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
                    foreach ($_SESSION['cart'] as $productId => $quantity) {
                        
                        $item = $theDBA->getProductById($productId);
                        $productName = $item[0]['productName'];
                        $productPrice = $item[0]['productPrice'];
                        $productStock = $item[0]['productStock'];
                        $productImage = $item[0]['productImage'];
                        echo '
    <div class="product-card" style="height:325px;">
        <img  style="margin-left:auto; margin-right:auto; width:150px; max-height:120px;" onclick="window.location.href=\'product.php?productId=' . $productId . '\'" class="product-image" src="products/'.$productImage.'" alt="'.$productName.'">
        <div style="margin:0;" class="product-info">
            <h3 class="product-name">'.$productName.'</h3>
            <p class="product-price">Only $'. $productPrice . '/lb</p>
            <p class="product-stock">Stock: ' . $productStock . ' available</p>
            <div class="quantity-controls">
                <button onclick="updateCart('."'"."remove"."',".$productId. ', '."'" .$user."'" . ');"> -</button>
                <span class="qty-box">Quantity: ' . $quantity . '</span>
                <button onclick="updateCart('."'"."add"."'," . $productId. ', '."'" .$user."'" .')">+</button>
            </div>
        </div>
    </div>';
                        
                    }
                } 
                else {
                    echo '<p>Your cart is empty.</p>';
                }
            }
            else{
            // Fetch the current user's cart items
            $userId = $_SESSION['id'];
            $index=(int)$_SESSION['index'];
            $users = $theDBA->getAllUsers();
            $user = openssl_decrypt($users[$index]['hashuser'], $method, $key);
            /*
            if($_SESSION['username']=="'guest'"){
                $user = "'guest'";
                
            }*/
            $cartItems = $theDBA->getUserCart($userId);
            
            $index=(int)$_SESSION['index'];
            foreach ($cartItems as $item) {
                $productName = $item['productName'];
                $productPrice = $item['productPrice'];
                $productStock = $item['productStock'];
                $productImage = $item['productImage'];
                $productId = $item['id'];
                $quantity = $item['quantity'];
                
                echo '
    <div class="product-card" style="height:325px;">
        <img  style="margin-left:auto; margin-right:auto; width:150px; max-height:120px;" onclick="window.location.href=\'product.php?productId=' . $productId . '\'" class="product-image" src="products/'.$productImage.'" alt="'.$productName.'">
        <div style="margin:0;" class="product-info">
            <h3 class="product-name">'.$productName.'</h3>
            <p class="product-price">Only $'. $productPrice . '/lb</p>
            <p class="product-stock">Stock: ' . $productStock . ' available</p>
            <div class="quantity-controls">
                <button onclick="updateCart('."'"."remove"."',".$productId. ', '."'" .$user."'" . ');"> -</button>
                <span class="qty-box">Quantity: ' . $quantity . '</span>
                <button onclick="updateCart('."'"."add"."'," . $productId. ', '."'" .$user."'" .')">+</button>
            </div>
        </div>
    </div>';
            }
            }
            ?>
            
        </div>
        <div id="totalsContainer" style="float:right;width:50%;"class="cart-container">
        <p style="margin:0" class="page-purpose">Totals</p>
        <div class="product-card">
        <ul class="item-list">
            <?php
            $subtotal = 0;
            if ($_SESSION['username'] === "'guest'") {
                if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
                    foreach ($_SESSION['cart'] as $productId => $quantity) {
                        $item = $theDBA->getProductById($productId);
                        $productName = $item[0]['productName'];
                        $productPrice = $item[0]['productPrice'];
                        $itemTotal = $quantity * $productPrice;
                        $subtotal += $itemTotal;
                        echo '<li class="cart-item">
                        <div class="item-info">
                            <span class="item-name">' . htmlspecialchars($productName) . '</span>
                            <span class="item-quantity">Qty: ' . $quantity . '</span>
                        </div>
                        <div class="item-price">$' . number_format($itemTotal, 2) . '</div>
                      </li>';
                    }
                    $tax = $subtotal * 0.072; // 7.2% tax
                    $total = $subtotal + $tax;
                }
            }
            else{

            foreach ($cartItems as $item) {
                $itemTotal = $item['quantity'] * $item['productPrice'];
                $subtotal += $itemTotal;
                echo '<li class="cart-item">
                        <div class="item-info">
                            <span class="item-name">' . htmlspecialchars($item['productName']) . '</span>
                            <span class="item-quantity">Qty: ' . $item['quantity'] . '</span>
                        </div>
                        <div class="item-price">$' . number_format($itemTotal, 2) . '</div>
                      </li>';
            }

            $tax = $subtotal * 0.072; // 7.2% tax
            $total = $subtotal + $tax;
            }
            ?>
        </ul>
    
    <div class="summary-totals">
        <p><strong>Subtotal:</strong> $<?php echo number_format($subtotal, 2); ?></p>
        <p><strong>Tax (7.2%):</strong> $<?php echo number_format($tax, 2); ?></p>
        <p><strong>Total:</strong> $<?php echo number_format($total, 2); ?></p>
    </div>
    <button class="checkout-btn" onclick="checkout()">Checkout</button>
        
        </div>
        </div></div>
</div>
</body>
<script>
function updateLogin(){
	var container = document.getElementById("right-container");
	container.innerHTML='<a href="profile.php" style="color:black; text-decoration: none ;"><i class="fa fa-user" aria-hidden="true"></i><p>Welcome Back!</p></a><a href="controller.php?logout=1" ><button style="margin-top:15px; margin-right: 10px;">Logout</button></a>';
}
function updateCart(action, id, username) {
    console.log("Action is: " + action);
    console.log("ID is: " + id);
    console.log("Username is: " + username);
    var anObj = new XMLHttpRequest();
    anObj.open("POST", "controller.php", true);
    anObj.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    var payload = action === "add" 
        ? "addToCart=true&productId=" + id + "&username=" + username 
        : "removeFromCart=true&productId=" + id + "&username=" + username;
    anObj.send(payload);
    anObj.onreadystatechange = function () {
        if (anObj.readyState == 4 && anObj.status == 200) {
            var strsplit = JSON.parse(anObj.responseText);
         	
            if (action === "add" && strsplit === "MAX") {
                console.log("MAX REACHED!");
                return;
            }
            var parts = strsplit.split(" ");
            var qty = parts[0];
            var index = parts[1];
            if (action === "remove" && qty === "REMOVED") {
                var cards = document.querySelectorAll(".product-card");
                cards[index].remove();
                return;
            }
            console.log("Index is: " + index);
            console.log("Qty is: " + qty);
            const elements = document.querySelectorAll(".qty-box");
            elements[index].innerHTML = "Quantity: " + qty;
        }
    };
}
</script>
</html>