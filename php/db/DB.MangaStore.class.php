<?php
/**
 * Created by PhpStorm.
 * User: Palash
 * Date: 2/22/2018
 * Time: 10:12 PM
 */

include_once $_SERVER['DOCUMENT_ROOT'] . "/php/utils/Product.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/php/utils/User.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/php/utils/Cart.php";

class dbMangaStore
{

    private $pdo;

    function __construct()
    {
        try {
            $this->pdo = new PDO("mysql:host=localhost;dbname=mangastore", 'root', '');
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die();
        }
    }

    public function getProductsOnSale()
    {
        $query = "SELECT * FROM products WHERE salePrice != 0";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, "Product");

        return $stmt->fetchAll();
    }

    public function getProductsInCatalog($pageNumber, $limit, $offset)
    {
        $query = "SELECT * FROM products WHERE salePrice = 0 LIMIT $limit OFFSET $offset";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, "Product");

        return $stmt->fetchAll();
    }

    public function insert($title, $description, $price, $quantity, $imageName, $salePrice)
    {
        $query = "INSERT INTO 
                    products (productName, description, price, quantity, imageName, salePrice) 
                    VALUES (:title, :description, :price, :quantity, :imageName, :salePrice)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(array(':title' => $title,
            ':description' => $description,
            ':price' => $price,
            ':quantity' => $quantity,
            ':imageName' => $imageName,
            ':salePrice' => $salePrice));
    }

    public function getUser($userID, $pwd)
    {
        $query = "SELECT * FROM users WHERE UserName = :userID AND Password = :pwd";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(array(
            ':userID' => $userID,
            ':pwd' => $pwd));
        $stmt->setFetchMode(PDO::FETCH_CLASS, "User");
        return $stmt->fetch();
    }

    public function getCartItem($productId, $sid)
    {
        $query = "SELECT * FROM carts WHERE productID = :productID AND sessionID = :sessionID";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(array(
            ':sessionID' => $sid,
            ':productID' => $productId
        ));
        $stmt->setFetchMode(PDO::FETCH_CLASS, "Cart");
        return $stmt->fetch();
    }

    public function updateQuantityInCart($productId, $sid)
    {
        $query = "UPDATE carts SET quantity = quantity + 1 WHERE sessionID = :sessionID AND productID = :productID";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(array(
            ':sessionID' => $sid,
            ':productID' => $productId
        ));
        return $stmt->rowCount();
    }

    public function insertItemToCart($productId, $sid)
    {
        $query = "INSERT INTO carts (sessionID, productID, quantity) VALUES (:sessionID, :productID, 1)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(array(
            ':sessionID' => $sid,
            ':productID' => $productId
        ));
        return $this->pdo->lastInsertId();
    }

    public function replaceCartWithNewSessionID($oldID, $newID)
    {
        $query = "UPDATE carts SET sessionID = :newID WHERE sessionID = :oldID";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(array(
            ':newID' => $newID,
            ':oldID' => $oldID
        ));
    }

    public function getNumberOfProductsInCart($sessionID)
    {
        $query = "SELECT count(*) AS no_of_products FROM carts WHERE sessionID = :sessionID";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(array(
            ':sessionID' => $sessionID));
        $data = $stmt->fetch();
        return $data["no_of_products"];
    }

    public function getProductsInCart($sessionID)
    {
        $query = "SELECT c.quantity AS quantity, p.productName AS title, p.price AS price, p.description AS description, p.salePrice AS salePrice,
 p.imageName AS imageName FROM carts c INNER JOIN products p ON c.productID = p.productID WHERE sessionID = :sessionID";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(array(
            ':sessionID' => $sessionID));
        return $stmt->fetchAll();
    }

    public function getCartTotal($sessionID)
    {
        $query = "SELECT sum(p.price) AS total FROM carts c INNER JOIN products p ON c.productID = p.productID WHERE c.sessionID = :sessionID";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(array(
            ':sessionID' => $sessionID));
        $data = $stmt->fetch();
        return $data["total"];
    }

    public function refillProductsQuantityFromCart($sessionID)
    {
        $query = "UPDATE products 
            JOIN carts ON carts.productID = products.productID 
            SET products.quantity = products.quantity + carts.quantity
            WHERE carts.sessionID = :sessionID";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(array(
            ':sessionID' => $sessionID
        ));

        return $stmt->rowCount();
    }

    public function removeProductsFromCart($sessionID)
    {
        $query = "DELETE FROM carts WHERE sessionID = :sessionID";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(array(
            ':sessionID' => $sessionID
        ));

        return $stmt->rowCount();
    }

    public function getNumberOfProductsInCatalog()
    {
        $query = "SELECT count(*) AS no_of_products FROM products WHERE salePrice = 0";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return intval($stmt->fetch()['no_of_products']);
    }

    public function addProduct($name, $description, $file, $quantity, $price, $salePrice)
    {
        $query = "INSERT INTO products (productName, description, imageName, quantity, price, salePrice) 
            VALUES (:name, :description, :file, :quantity, :price, :salePrice)";
        $stmt = $this->pdo->prepare($query);
        try {
            $stmt->execute(array(
                ':name' => $name,
                ':description' => $description,
                ':file' => $file,
                ':quantity' => $quantity,
                ':price' => $price,
                'salePrice' => $salePrice
            ));
        } catch (Exception $e) {
            return "Failed to add '$name'.";
        }
        return "'$name' added successfully.";
    }

    public function getNumberOfProductsWithName($name)
    {
        $query = "SELECT count(*) AS no_of_products FROM products WHERE productName = :name";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(array(':name' => $name));
        return $stmt->fetch()['no_of_products'];
    }

    public function getNumberOfProductsOnSale()
    {
        $query = "SELECT count(*) AS no_of_products FROM products WHERE salePrice != 0";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(array());
        return $stmt->fetch()['no_of_products'];
    }

    public function getAllProductNames()
    {
        $query = "SELECT productName FROM products ORDER by productID DESC";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, "Product");

        return $stmt->fetchAll();
    }

    public function getProductFromName($name)
    {
        $query = "SELECT * FROM products WHERE productName = :name";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(array(':name' => $name));
        $stmt->setFetchMode(PDO::FETCH_CLASS, "Product");

        return $stmt->fetch();
    }

    public function updateProductInformation($oldProductName, $newName, $newDescription, $newImage, $newQuantity, $newPrice, $newSalePrice)
    {
        $query = "UPDATE products SET productName = :newName, description = :newDescription, imageName=:newImage, 
              quantity = :newQuantity, price = :newPrice, salePrice = :newSalePrice WHERE productName = :oldProductName";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(array(
            ':newName' => $newName,
            ':newDescription' => $newDescription,
            ':newImage' => $newImage,
            ':newQuantity' => $newQuantity,
            ':newPrice' => $newPrice,
            ':newSalePrice' => $newSalePrice,
            ':oldProductName' => $oldProductName,
        ));

        return $stmt->rowCount() > 0;
    }

    public function reduceQuantity($productId)
    {
        $query = "UPDATE products SET quantity = quantity - 1 WHERE productID = :productId";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(array(
            ':productId' => $productId
        ));
        return $stmt->rowCount();
    }

}

//$db = new dbMangaStore();
//$dom = new DOMDocument();
//$dom->load("input.xml");
//$articles = $dom->getElementsByTagName("article");
//$count = 1;
//foreach ($articles as $article) {
//    $title = trim($article->getElementsByTagName("h4")->item(0)->nodeValue);
//    $description = "";
//    $price = 15;
//    $quantity = 100;
//    $imageName = "/PHP-eCommerce-Manga/images/$count.jpg";
//    $salePrice = 0;
//
//    $db->insert($title, $description, $price, $quantity, $imageName, $salePrice);
//    $count += 1;
//
//    if ($count > 43) break;
//}