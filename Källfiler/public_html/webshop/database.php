<?php

//require_once("../../scripts/variables.php");
//$dbh = new PDO("mysql:host={$dbhost}; charset=utf8; dbname={$dbname};", $dbusername, $dbpasswd);
require("product.php");
session_start();



function getProductsByCategory($category, $limit){
    require("../../scripts/variables.php");
    $dbh = new PDO("mysql:host={$dbhost}; charset=utf8; dbname={$dbname};", $dbusername, $dbpasswd);

    if($limit > 0){
        $sql = "SELECT * FROM products WHERE category='{$category}' LIMIT {$limit}"; // Om limit är 0 så visas alla produkter
    }
    else{
        $sql = "SELECT * FROM products WHERE category='{$category}'";
    }
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $productArray = array();
    while($product = $stmt->fetch()){
        $newProduct = new product($product["id"], $product["name"], $product["price"], $product["category"], $product["stock"], $product["description"], $product["imageurl"]);
        $productArray[] =  $newProduct;
    }
    return $productArray;
}

function getCategories(){
    require("../../scripts/variables.php");
    $dbh = new PDO("mysql:host={$dbhost}; charset=utf8; dbname={$dbname};", $dbusername, $dbpasswd);

    $sql = "SELECT * FROM categories";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $categories = array();
    while($category = $stmt->fetch()){
        $categories[] = $category["name"];
    }
    return $categories;
}

function getUserCart($username){
    require("../../scripts/variables.php");
    $dbh = new PDO("mysql:host={$dbhost}; charset=utf8; dbname={$dbname};", $dbusername, $dbpasswd);

    $sql = "SELECT cart FROM users WHERE username='{$username}';";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $cartstring = $stmt->fetch();
    return explode("|", $cartstring["cart"]);
}

function addProduct($productname, $price, $stock, $category, $description, $imageurl){
    require("../../scripts/variables.php");
    $dbh = new PDO("mysql:host={$dbhost}; charset=utf8; dbname={$dbname};", $dbusername, $dbpasswd);

    $sql = "INSERT INTO products (id, name, price, category, stock, description, imageurl) VALUES (NULL, '{$productname}', '{$price}', '{$category}', '{$stock}', '{$description}', '{$imageurl}');";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    return true;
}

function getProduct($productid){
    require("../../scripts/variables.php");
    $dbh = new PDO("mysql:host={$dbhost}; charset=utf8; dbname={$dbname};", $dbusername, $dbpasswd);

    $sql = "SELECT * FROM products WHERE id={$productid}";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $temp_product = $stmt->fetch();
    $product = new product($temp_product["id"], $temp_product["name"], $temp_product["price"], $temp_product["category"], $temp_product["stock"], $temp_product["description"], $temp_product["imageurl"]);
    return $product;
}

function removeProduct($productid){
    require("../../scripts/variables.php");
    $dbh = new PDO("mysql:host={$dbhost}; charset=utf8; dbname={$dbname};", $dbusername, $dbpasswd);

    $sql = "DELETE FROM products WHERE id='{$productid}'";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
}

function addToCart($productid){
    require("../../scripts/variables.php");
    $dbh = new PDO("mysql:host={$dbhost}; charset=utf8; dbname={$dbname};", $dbusername, $dbpasswd);
    $user = $_SESSION['user'];

    $sql = "SELECT cart FROM users WHERE username='{$user}'";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $usercart = $stmt->fetch();
    $newcart = $usercart["cart"] . $productid . "|";

    $sql = "UPDATE users SET cart='{$newcart}' WHERE username='{$user}'";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
}

function getCart(){
    require("../../scripts/variables.php");
    $dbh = new PDO("mysql:host={$dbhost}; charset=utf8; dbname={$dbname};", $dbusername, $dbpasswd);
    $user = $_SESSION["user"];

    $sql = "SELECT cart FROM users WHERE username='{$user}'";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $usercart = $stmt->fetch();

    $usercart = explode("|", $usercart["cart"]); // Delar upp kundvagnen till en array utav productid's

    $cart = array(); // Denna ska innehålla product-objekt

    foreach($usercart as $cartitem){
        $cart[] = getProduct($cartitem);
    }
    return $cart;
}
function removeFromCart($productid){
    require("../../scripts/variables.php");
    $dbh = new PDO("mysql:host={$dbhost}; charset=utf8; dbname={$dbname};", $dbusername, $dbpasswd);
    $user = $_SESSION["user"];

    $sql = "SELECT cart FROM users WHERE username='{$user}'";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $usercart = $stmt->fetch();

    $usercart = explode("|", $usercart["cart"]); // Delar upp kundvagnen till en array utav productid's
     // Ta bort det värdet som ska bort

    $index = array_search($productid, $usercart); // Hitta rätt index
    unset($usercart[$index]); // Ta bort innehållet
    $verycart = array();  // Skapar en "ny" array med bara innehåll
    foreach($usercart as $cartitem){
        if($cartitem != "") { // Om indexet inte är tomt
            $verycart[] = $cartitem; // så läggs datan in i en annan array
        }
    }

    $newcart = ""; // Skapa en ny string att lägga arrayen i
    foreach ($verycart as $cartitem) {
        $newcart = $newcart . $cartitem . "|";
    }

    $sql = "UPDATE users SET cart='{$newcart}' WHERE username='{$user}'"; // Lägger in den nya kundvagnen
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
}