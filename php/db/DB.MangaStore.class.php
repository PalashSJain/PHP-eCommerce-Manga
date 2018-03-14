<?php
/**
 * Created by PhpStorm.
 * User: Palash
 * Date: 2/22/2018
 * Time: 10:12 PM
 */

include_once $_SERVER['DOCUMENT_ROOT'] . "/php/utils/Product.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/php/utils/Constants.php";

class dbMangaStore
{

    private $dbh;

    function __construct()
    {
        try {
            $this->dbh = new PDO("mysql:host=localhost;dbname=mangastore", 'root', '');
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    public function getProductsOnSale()
    {
        $query = "SELECT * FROM products WHERE salePrice != 0";
        $stmt = $this->dbh->prepare($query);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, "Product");

        $products = $stmt->fetchAll();

        return $products;
    }

    public function getProductsOnCatalog($pageNumber)
    {
        $query = "SELECT * FROM products WHERE salePrice = 0 LIMIT " . Constants::PAGE_SIZE . " OFFSET " . ($pageNumber * Constants::PAGE_SIZE);
        $stmt = $this->dbh->prepare($query);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, "Product");

        $products = $stmt->fetchAll();

        return $products;
    }

    public function insert($title, $description, $price, $quantity, $imageName, $salePrice)
    {
        $query = "INSERT INTO 
                    products (productName, description, price, quantity, imageName, salePrice) 
                    VALUES (:title, :description, :price, :quantity, :imageName, :salePrice)";
        $stmt = $this->dbh->prepare($query);
        $stmt->execute(array(':title' => $title,
            ':description' => $description,
            ':price' => $price,
            ':quantity' => $quantity,
            ':imageName' => $imageName,
            ':salePrice' => $salePrice));
        echo $stmt->rowCount();
    }

    public function isAdmin($userID, $pwd)
    {
        $query = "SELECT count(*) AS no_of_users FROM users WHERE UserID = :userID AND Password = :pwd";
        $stmt = $this->dbh->prepare($query);
        $stmt->execute(array(
            ':userID' => $userID,
            ':pwd' => $pwd));
        $data = $stmt->fetch();
        return $data["no_of_users"] == 1;
    }

    public function addToCart($productId, $sid)
    {
        $query = "INSERT INTO carts (sessionID, productID) VALUES (:sessionID, :productID)";
        $stmt = $this->dbh->prepare($query);
        $stmt->execute(array(
            ':sessionID' => $sid,
            ':productID' => $productId
        ));
    }

    public function replaceCartWithNewSessionID($oldID, $newID)
    {
        $query = "UPDATE carts SET sessionID = :newID WHERE sessionID = :oldID";
        $stmt = $this->dbh->prepare($query);
        $stmt->execute(array(
            ':newID' => $newID,
            ':oldID' => $oldID
        ));
    }

    public function getNumberOfProductsInCart($sessionID)
    {
        $query = "SELECT count(*) AS no_of_products FROM carts WHERE sessionID = :sessionID";
        $stmt = $this->dbh->prepare($query);
        $stmt->execute(array(
            ':sessionID' => $sessionID));
        $data = $stmt->fetch();
        return $data["no_of_products"];
    }

    public function getProductsInCart($sessionID)
    {
        $query = "SELECT c.quantity AS quantity, p.productName AS title, p.price AS price, p.description AS description, p.salePrice AS salePrice,
 p.imageName AS imageName FROM carts c INNER JOIN products p ON c.productID = p.productID WHERE sessionID = :sessionID";
        $stmt = $this->dbh->prepare($query);
        $stmt->execute(array(
            ':sessionID' => $sessionID));
        return $stmt->fetchAll();
    }

    public function getCartTotal($sessionID)
    {
        $query = "SELECT sum(p.price) AS total FROM carts c INNER JOIN products p ON c.productID = p.productID WHERE c.sessionID = :sessionID";
        $stmt = $this->dbh->prepare($query);
        $stmt->execute(array(
            ':sessionID' => $sessionID));
        $data = $stmt->fetch();
        return $data["total"];
    }

    public function clearCart($sessionID)
    {
        $query = "DELETE FROM carts WHERE sessionID = :sessionID";
        $stmt = $this->dbh->prepare($query);
        $stmt->execute(array(
            ':sessionID' => $sessionID
        ));
    }

    public function getNumberOfProductsInCatalog()
    {
        $query = "SELECT count(*) AS no_of_products FROM products WHERE salePrice = 0";
        $stmt = $this->dbh->prepare($query);
        $stmt->execute();
        return intval($stmt->fetch()['no_of_products']);
    }

    public function addProduct($name, $description, /*$file, */
                               $quantity, $price, $salePrice)
    {
        $query = "INSERT INTO products (productName, description, imageName, quantity, price, salePrice) 
            VALUES (:name, :description, :file, :quantity, :price, :salePrice)";
        $stmt = $this->dbh->prepare($query);
        try {
            $stmt->execute(array(
                ':name' => $name,
                ':description' => $description,
                ':file' => 'https://images.sftcdn.net/images/t_optimized,f_auto/p/ce2ece60-9b32-11e6-95ab-00163ed833e7/260663710/the-test-fun-for-friends-screenshot.jpg',
                ':quantity' => $quantity,
                ':price' => $price,
                'salePrice' => $salePrice
            ));
        } catch (Exception $e) {
            echo $e->getMessage();
            return "Failed to add '$name'.";
        }
        return "'$name' added successfully.";
    }

    public function getNumberOfProductsWithName($name)
    {
        $query = "SELECT count(*) AS no_of_products FROM products WHERE productName = :name";
        $stmt = $this->dbh->prepare($query);
        $stmt->execute(array(':name' => $name));
        return $stmt->fetch()['no_of_products'];
    }

    public function getNumberOfProductsOnSale()
    {
        $query = "SELECT count(*) AS no_of_products FROM products WHERE salePrice != 0";
        $stmt = $this->dbh->prepare($query);
        $stmt->execute(array());
        return $stmt->fetch()['no_of_products'];
    }

    public function getAllProducts()
    {
        $query = "SELECT * FROM products";
        $stmt = $this->dbh->prepare($query);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, "Product");

        $products = $stmt->fetchAll();

        return $products;
    }

    public function getProductFromName($name)
    {
        $query = "SELECT * FROM products WHERE productName = :name";
        $stmt = $this->dbh->prepare($query);
        $stmt->execute(array(':name' => $name));
        $stmt->setFetchMode(PDO::FETCH_CLASS, "Product");

        $product = $stmt->fetch();
        return $product;
    }

}

//$db = new dbMangaStore();
//$dom = new DOMDocument();
//$dom->load("input.xml");
//$articles = $dom->getElementsByTagName("article");
//foreach ($articles as $article) {
//    $title = trim($article->getElementsByTagName("h4")->item(0)->nodeValue);
//    $description = "";
//    $price = 15;
//    $quantity = 1;
//    $imageName = $article->getElementsByTagName("img")->item(0)->getAttribute("src");
//    $salePrice = 0;
//
//    $db->insert($title, $description, $price, $quantity, $imageName, $salePrice);
//}