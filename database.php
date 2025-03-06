<?php
/*
 * Author: Joccelyn Kaufman
 * Final Capstone Project
 * CSC480
 * This is the Database file that manages all of the SQL queries to retrieve from DB and send to controller or other pages. 
 */

class DatabaseAdaptor {
    
    // The instance variable used in every one of the functions in class DatbaseAdaptor
    private $DB;
    public function __construct() {
        /*
         * LOCAL HOST ENVIRONMENT:
         $db = 'mysql:host=127.0.0.1;dbname=shoppingcart;charset=utf8';
         $user = 'root';
         $password = '';
         
         LIVE ENVIRONMENT:
         $db = 'mysql:host=localhost;dbname=u622108952_shoppingcart;charset=utf8';
         $user = 'u622108952_shoppingcart';
         $password = 'Csc480capstone!';
         */
        
        $db = 'mysql:host=localhost;dbname=u622108952_shoppingcart;charset=utf8';
        $user = 'u622108952_shoppingcart';
        $password = 'Csc480capstone!';
        
        
        try {
            $this->DB = new PDO ( $db, $user, $password );
            $this->DB->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        } catch ( PDOException $e ) {
            echo ('Error establishing Connection');
            exit ();
        }
    }
    
    public function addUser($user, $pswd, $email, $name, $last){
        $stmt = $this->DB->prepare( 'INSERT INTO users(addedDate, hashfirst, hashlast, hashuser, hashpass, hashemail, hashtries) VALUES (CURRENT_TIMESTAMP,:na, :la, :us, :pa, :em, 0);');
        $stmt->bindParam("na", $name);
        $stmt->bindParam("la", $last);
        $stmt->bindParam("us", $user);
        $stmt->bindParam("pa", $pswd);
        $stmt->bindParam("em", $email);
        $stmt->execute();
        return;
    }
    public function updateTries($tries, $user_id){
        $stmt = $this->DB->prepare('UPDATE users SET hashtries = :try, hashtrydate= :da WHERE id= :id');
        $stmt->bindParam("try", $tries);
        $stmt->bindParam("id", $user_id);
        $stmt->bindParam("da", $_SERVER['REQUEST_TIME']);
        $stmt->execute();
        return;
    }
    public function getAllUsers(){
        $stmt = $this->DB->prepare( "SELECT * FROM users");
        $stmt->execute ();
        return $stmt->fetchAll ( PDO::FETCH_ASSOC );
    }
    public function updateProfile($email, $first, $last, $address, $city, $state, $zip, $id){
        $stmt = $this->DB->prepare('UPDATE users SET hashemail=:em, hashfirst=:fi, hashlast=:la, hashaddress=:ad, hashcity=:ci, hashstate=:st, hashzip=:zi
        WHERE id= :id');
        $stmt->bindParam("em", $email);
        $stmt->bindParam("fi", $first);
        $stmt->bindParam("la", $last);
        $stmt->bindParam("ad", $address);
        $stmt->bindParam("ci", $city);
        $stmt->bindParam("st", $state);
        $stmt->bindParam("zi", $zip);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        return;
        
    }
    public function addProduct($name, $productDescription, $price, $stock, $file, $category){
        $stmt = $this->DB->prepare( 'INSERT INTO products(productName, productDescription, productPrice, productStock, productImage, productFeatured, productCategory) VALUES (:name, :desc, :price, :stock, :img, "no", :cat);');
        $stmt->bindParam("name", $name);
        $stmt->bindParam("desc", $productDescription);
        $stmt->bindParam("price", $price);
        $stmt->bindParam("stock", $stock);
        $stmt->bindParam("img", $file);
        $stmt->bindParam("cat", $category);
        $stmt->execute();
        return;
    }
    public function getAllFeaturedProducts(){
        $stmt = $this->DB->prepare( "SELECT * FROM products WHERE productFeatured='yes'");
        $stmt->execute ();
        return $stmt->fetchAll ( PDO::FETCH_ASSOC );
    }
    public function getAllProducts(){
        $stmt = $this->DB->prepare( "SELECT * FROM products");
        $stmt->execute ();
        return $stmt->fetchAll ( PDO::FETCH_ASSOC );
    }
    public function getAllSeeds(){
        $stmt = $this->DB->prepare( "SELECT * FROM products WHERE productCategory='seed'");
        $stmt->execute ();
        return $stmt->fetchAll ( PDO::FETCH_ASSOC );
    }
    public function getAllNuts(){
        $stmt = $this->DB->prepare( "SELECT * FROM products WHERE productCategory='nut'");
        $stmt->execute ();
        return $stmt->fetchAll ( PDO::FETCH_ASSOC );
    }
    public function getAllVegetables(){
        $stmt = $this->DB->prepare( "SELECT * FROM products WHERE productCategory='vegetable'");
        $stmt->execute ();
        return $stmt->fetchAll ( PDO::FETCH_ASSOC );
    }
    public function getProductById($id){
        $stmt = $this->DB->prepare( "SELECT * FROM products WHERE id=".$id);
        $stmt->execute ();
        return $stmt->fetchAll ( PDO::FETCH_ASSOC );
    }
    public function searchProductsByName($query) {
        $stmt = $this->DB->prepare("SELECT * FROM products WHERE productName LIKE :query");
        $query = '%' . $query . '%'; // Add wildcards for partial matches
        $stmt->bindParam(':query', $query, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function addToCart($productId, $qty, $userId) {
        $stmt = $this->DB->prepare('INSERT INTO cart_items(user_id, product_id, quantity) VALUES (:us, :pr, :qt)');
        $stmt->bindParam("us", $userId);
        $stmt->bindParam("pr", $productId);
        $stmt->bindParam("qt", $qty);
        $stmt->execute();
        return true;  // Make sure to return a success response
    }
    
    public function updateCart($qty, $userId, $productId) {
        $stmt = $this->DB->prepare('UPDATE cart_items SET quantity = :qt WHERE user_id = :us AND product_id = :pr');
        $stmt->bindParam("qt", $qty);
        $stmt->bindParam("us", $userId);
        $stmt->bindParam("pr", $productId);
        $stmt->execute();
        return true;  // Return success response
    }
    public function getAllCarts(){
        $stmt = $this->DB->prepare( "SELECT * FROM cart_items");
        $stmt->execute ();
        return $stmt->fetchAll ( PDO::FETCH_ASSOC );
    }
    public function getUserCart($userId){
        $stmt = $this->DB->prepare("SELECT cart_items.id AS cart_item_id, cart_items.quantity, products.productName,
        products.productPrice, products.productStock, products.productImage, products.id FROM cart_items INNER JOIN products ON cart_items.product_id = products.id
    WHERE cart_items.user_id = ".$userId);
        $stmt->execute ();
        return $stmt->fetchAll ( PDO::FETCH_ASSOC );
       
    }
    public function removeFromCart($productId, $userId){
        $stmt = $this->DB->prepare("DELETE FROM cart_items WHERE product_id = :pr AND user_id = :us");
        $stmt->bindParam("us", $userId);
        $stmt->bindParam("pr", $productId);
        $stmt->execute();
        return true;
    }
}

$theDBA = new DatabaseAdaptor ();
    ?>
    