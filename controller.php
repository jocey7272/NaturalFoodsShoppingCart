<?php 

/*
 * Author: Joccelyn Kaufman
 * Final Capstone Project
 * CSC480
 * This is the controller that processes data to and from the database.
 */
include 'database.php'; 
session_start();
$secret_key= "mykey";
$method = "AES-128-ECB";
$key = hash('sha256', $secret_key );
error_reporting(E_ERROR | E_PARSE);

//Login
if(isset($_POST['username'])&& isset($_POST['password'])){
    $user = strtolower($_POST['username']);
    $pswd = $_POST['password'];
    $signData = $theDBA->getAllUsers();
    $check= -1;
    $tries=0;
    for($i=0; $i<count($signData); $i++){
        if(openssl_decrypt($signData[$i]['hashuser'], $method, $key)== $user){
            $check=$i;
            break;
        }
    }
    if($check!=-1){
        if((int)$signData[$i]['hashtries']>3){
            $date = $_SERVER['REQUEST_TIME'];
            $old_date = $signData[$i]['hashtrydate'];
            $diff_minutes = round(abs($date - $old_date)/60);
            if($diff_minutes<=9){
                //has tried to login 4 times unsuccessfully, then hasnt waited at least 10min
                header("Location: login.php?time=false&lasttry=".$diff_minutes);
                return;
            }
        }
        if(openssl_decrypt($signData[$i]['hashpass'], $method, $key)== $pswd){
            $theDBA->updateTries(0, $signData[$i]['id']);
            $_SESSION['username']= openssl_decrypt($user, $method, $key);
            $_SESSION['email'] = openssl_decrypt($signData[$i]['hashemail'], $method, $key);
            $_SESSION['name'] = openssl_decrypt($signData[$i]['hashfirst'], $method, $key);
            $_SESSION['last'] = openssl_decrypt($signData[$i]['hashlast'], $method, $key);
            $_SESSION['id']=$signData[$i]['id'];
            $_SESSION['index']=$i;
            if($signData[$i]['hashusetype']=="Admin"){
                $_SESSION['userType']="Admin";
            }
            header("Location: index.php");
            return;
        }
        else{
            //incorrect pw
            $tries = $signData[$i]['hashtries']+1;
            $theDBA->updateTries($tries, $signData[$i]['id']);
            header('Location: login.php?pass=invalid');
            return;
        }
    }
    else{
        //incorrect username
        header("Location: login.php?user=invalid");
        return;
    }
}
//Logs Out
if(isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
}
//Register
if(isset($_POST['reg_usr'])&& isset($_POST['reg_pass']) && isset($_POST['reg_em'])&& isset($_POST['reg_name']) && isset($_POST['reg_last'])){
    $user = openssl_encrypt(strtolower($_POST['reg_usr']), $method, $key);
    $pswd = openssl_encrypt($_POST['reg_pass'], $method, $key);
    $email = openssl_encrypt($_POST['reg_em'], $method, $key);
    $name = openssl_encrypt($_POST['reg_name'], $method, $key);
    $last = openssl_encrypt($_POST['reg_last'], $method, $key);
    $signData = $theDBA->getAllUsers();
    for($i=0; $i<count($signData); $i++){
        if(openssl_decrypt($signData[$i]['hashuser'], $method, $key)== strtolower($_POST['reg_usr'])){
            header("Location: register.php?error=duplicate");
            return;
        }
    }
    $return = $theDBA->addUser($user, $pswd, $email, $name, $last);
    echo '
        <form id="autoLoginForm" method="POST" action="controller.php">
            <input type="hidden" name="username" value="' . htmlspecialchars($_POST['reg_usr']) . '">
            <input type="hidden" name="password" value="' . htmlspecialchars($_POST['reg_pass']) . '">
        </form>
        <script>
            document.getElementById("autoLoginForm").submit();
        </script>
    ';
    return;
}
//Updates profile
if(isset($_POST['profileUpdate'])){
    $email = openssl_encrypt($_POST['email'], $method, $key);
    $first = openssl_encrypt($_POST['first_name'], $method, $key);
    $last = openssl_encrypt($_POST['last_name'], $method, $key);
    $address = openssl_encrypt($_POST['address'], $method, $key);
    $city = openssl_encrypt($_POST['city'], $method, $key);
    $state = openssl_encrypt($_POST['state'], $method, $key);
    $zip = openssl_encrypt($_POST['zip'], $method, $key);
    $id = $_POST['profileUpdate'];
    $return = $theDBA->updateProfile($email, $first, $last, $address, $city, $state, $zip, $id);
    header("Location: profile.php");
    return;
    
}
//searches products
if (isset($_GET['query'])) {
    $query = htmlspecialchars($_GET['query']);
    $products = $theDBA->searchProductsByName($query);
    if (count($products) > 0) {
        foreach ($products as $product) {
            echo '
            <div class="product-card">
                <img onclick="window.location.href=\'product.php?productId=' . $product["id"] . '\'" class="product-image" src="products/' . $product["productImage"] . '" alt="' . $product['productName'] . '">
                <div class="product-info">
                    <h3 class="product-name">' . $product['productName'] . '</h3>
                    <p class="product-price">Only $' . $product['productPrice'] . '/lb!</p>
                    <button class="add-to-cart-btn" onclick="addToCart(' . $product["id"] . ', ' . "'user'" . ')">Add to Cart</button>
                </div>
            </div>';
        }
    } else {
        echo '<p>Sorry, no products found! :(</p>';
    }
}
//Adds products
if(isset($_POST['productName']) && isset($_POST['productDescription'])&& isset($_POST['productPrice'])&& isset($_POST['productStock']) && isset($_POST['productCategory'])){
    $name= $_POST['productName'];
    $productDescription= $_POST['productDescription'];
    $price = $_POST['productPrice'];
    $stock = $_POST['productStock'];
    $category = $_POST['productCategory'];
    $file = $_FILES['productImage']['name'];
    $folder="products/";
    $path = $folder.$file;
    move_uploaded_file($_FILES['photo_upload']['tmp_name'], $path);
    $return = $theDBA->addProduct($name, $productDescription, $price, $stock, $file, $category);
    /*
    if($return==0){
        unset($_SESSION['productError']);
        header('Location: manage.php');
        return;
    }
    $_SESSION['productError']='ERROR: Unable to add item.';
    header('Location: manage.php?error=1');
    return;
    */
    header('Location: manage.php');
    return;
}

/*
if (isset($_POST['addToCart']) || isset($_POST['removeFromCart'])) {
    //session_start(); // Ensure the session is started
    
    $productId = $_POST['productId'];
    $username = $_POST['username'];
    
    // If the user is a guest
    if ($_SESSION['username'] === "'guest'") {
        // Initialize the cart array if it doesn't exist
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        
        if (isset($_POST['addToCart'])) {
            // Check if the product already exists in the cart
            if (isset($_SESSION['cart'][$productId])) {
                $_SESSION['cart'][$productId] += 1;
            } else {
                $_SESSION['cart'][$productId] = 1;
            }
            
            echo json_encode(['success' => true, 'cart' => $_SESSION['cart']]);
            return;
        }
        
        if (isset($_POST['removeFromCart'])) {
            // Reduce quantity or remove the product from the cart
            if (isset($_SESSION['cart'][$productId])) {
                $_SESSION['cart'][$productId] -= 1;
                
                if ($_SESSION['cart'][$productId] < 1) {
                    unset($_SESSION['cart'][$productId]); // Remove the product if quantity is 0
                }
                
                echo json_encode(['success' => true, 'cart' => $_SESSION['cart']]);
                return;
            }
        }
        
        echo json_encode(['success' => false, 'message' => 'Product not in cart']);
        return;
    }
    
    // For logged-in users
    $user = openssl_encrypt($username, $method, $key);
    $userarr = $theDBA->getAllUsers();
    $cartarr = $theDBA->getAllCarts();
    $product = $theDBA->getProductById($productId);
    $userId = -1;
    
    // Find user ID
    foreach ($userarr as $userEntry) {
        if ($userEntry['hashuser'] === $user) {
            $userId = $userEntry['id'];
            break;
        }
    }
    
    if (isset($_POST['addToCart'])) {
        $qty = 1;
        foreach ($cartarr as $cartItem) {
            if ($cartItem['user_id'] === $userId && $cartItem['product_id'] === $productId) {
                $savedQty = (int)$cartItem['quantity'];
                $savedQty += 1;
                $qty = $savedQty;
                
                if ($qty > $product['productStock']) {
                    echo json_encode("MAX");
                    return;
                }
                
                $theDBA->updateCart($qty, $userId, $productId);
                echo json_encode($qty);
                return;
            }
        }
        
        $theDBA->addToCart($productId, $qty, $userId);
        echo json_encode($qty);
        return;
    }
    
    if (isset($_POST['removeFromCart'])) {
        foreach ($cartarr as $cartItem) {
            if ($cartItem['user_id'] === $userId && $cartItem['product_id'] === $productId) {
                $savedQty = (int)$cartItem['quantity'];
                $savedQty -= 1;
                
                if ($savedQty < 1) {
                    $theDBA->removeFromCart($productId, $userId);
                    echo json_encode("REMOVED");
                    return;
                }
                
                $theDBA->updateCart($savedQty, $userId, $productId);
                echo json_encode($savedQty);
                return;
            }
        }
    }
}
*/
if(isset($_POST['addToCart'])||isset($_POST['removeFromCart'])){
    $productId = $_POST['productId'];
    $user = openssl_encrypt($_POST['username'], $method, $key);
    
    if ($_SESSION['username'] === "'guest'") {
        // Initialize the cart array if it doesn't exist
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        if (isset($_POST['addToCart'])) {
            // Check if the product already exists in the cart
            if (isset($_SESSION['cart'][$productId])) {
                $_SESSION['cart'][$productId] += 1;
            } else {
                $_SESSION['cart'][$productId] = 1;
            }
            $keys = array_keys($_SESSION['cart']);
            $index = array_search($productId, $keys);
            echo json_encode($_SESSION['cart'][$productId]." ".$index);
            return;
        }
        
        if (isset($_POST['removeFromCart'])) {
            // Reduce quantity or remove the product from the cart
            if (isset($_SESSION['cart'][$productId])) {
                $_SESSION['cart'][$productId] -= 1;
                
                if ($_SESSION['cart'][$productId] < 1) {
                    unset($_SESSION['cart'][$productId]); // Remove the product if quantity is 0
                }
                $keys = array_keys($_SESSION['cart']);
                $index = array_search($productId, $keys);
                echo json_encode($_SESSION['cart'][$productId]." ".$index);
                return;
            }
        }
        
        echo json_encode(['success' => false, 'message' => 'Product not in cart']);
        return;
    }
    
    $userarr = $theDBA->getAllUsers();
    $cartarr=$theDBA->getAllCarts();
    $product = $theDBA->getProductById($productId);
    $userId=-1;
    for($i=0; $i<count($userarr); $i++){
        if($userarr[$i]['hashuser']==$user){
            $userId=$userarr[$i]['id'];
            break;
        }
    }
    //get qty of potential existing and update
    if(isset($_POST['addToCart'])){
     $qty=1;
        for($i=0; $i<count($cartarr); $i++){
            if ($cartarr[$i]['user_id']==$userId && $cartarr[$i]['product_id']==$productId){
                $savedQty=(int)$cartarr[$i]['quantity'];
                $savedQty+=1;
                $qty=$savedQty;
               if($qty>$product[0]['productStock']){
                echo(json_encode("MAX"));
                return;
               }
            $return = $theDBA->updateCart($qty, $userId, $productId);
            echo json_encode($qty." ".$i);
            return;
            }
        }
        $return = $theDBA->addToCart($productId, $qty, $userId);
        echo json_encode($return);
        return;
    }
    else{
        for($i=0; $i<count($cartarr); $i++){
            if ($cartarr[$i]['user_id']==$userId && $cartarr[$i]['product_id']==$productId){
                $savedQty=(int)$cartarr[$i]['quantity'];
                $savedQty-=1;
                $qty=$savedQty;
                if($qty<1){
                    $return = $theDBA->removeFromCart($productId, $userId);
                    echo json_encode("REMOVED". " ".$i);
                    return;
                }
                $return = $theDBA->updateCart($qty, $userId, $productId);
                echo json_encode($qty." ".$i);
                return;
            }
        }
    }
}
?>