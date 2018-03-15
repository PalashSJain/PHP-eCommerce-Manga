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
        try {
            $query = "SELECT * FROM products WHERE salePrice != 0";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Product");

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            die();
        }
    }

    public function getProductsInCatalog($limit, $offset)
    {
        try {
            $query = "SELECT * FROM products WHERE salePrice = 0 LIMIT $limit OFFSET $offset";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Product");

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            die();
        }
    }

    public function getUser($userID)
    {
        try {
            $query = "SELECT * FROM users WHERE username = :userID";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(array(
                ':userID' => $userID));
            $stmt->setFetchMode(PDO::FETCH_CLASS, "User");
            return $stmt->fetch();
        } catch (PDOException $e) {
            die();
        }
    }

    public function getCartItem($productId, $sid)
    {
        try {
            $query = "SELECT * FROM carts WHERE productID = :productID AND sessionID = :sessionID";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(array(
                ':sessionID' => $sid,
                ':productID' => $productId
            ));
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Cart");
            return $stmt->fetch();
        } catch (PDOException $e) {
            die();
        }
    }

    public function updateQuantityInCart($productId, $sid)
    {
        try {
            $query = "UPDATE carts SET quantity = quantity + 1 WHERE sessionID = :sessionID AND productID = :productID";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(array(
                ':sessionID' => $sid,
                ':productID' => $productId
            ));
            return $stmt->rowCount();
        } catch (PDOException $e) {
            die();
        }
    }

    public function insertItemToCart($productId, $sid)
    {
        try {
            $query = "INSERT INTO carts (sessionID, productID, quantity) VALUES (:sessionID, :productID, 1)";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(array(
                ':sessionID' => $sid,
                ':productID' => $productId
            ));
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            die();
        }
    }

    public function replaceCartWithNewSessionID($oldID, $newID)
    {
        try {
            $query = "UPDATE carts SET sessionID = :newID WHERE sessionID = :oldID";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(array(
                ':newID' => $newID,
                ':oldID' => $oldID
            ));

            return $stmt->rowCount();
        } catch (PDOException $e) {
            die();
        }
    }

    public function getNumberOfProductsInCart($sessionID)
    {
        try {
            $query = "SELECT count(*) AS no_of_products FROM carts WHERE sessionID = :sessionID";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(array(
                ':sessionID' => $sessionID));
            $data = $stmt->fetch();
            return $data["no_of_products"];
        } catch (PDOException $e) {
            die();
        }
    }

    public function getProductsInCart($sessionID)
    {
        try {
            $query = "SELECT c.quantity AS quantity, p.productName AS title, p.price AS price, p.description AS description, p.salePrice AS salePrice,
 p.imageName AS imageName FROM carts c INNER JOIN products p ON c.productID = p.productID WHERE sessionID = :sessionID";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(array(
                ':sessionID' => $sessionID));
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            die();
        }
    }

    public function getCartTotal($sessionID)
    {
        try {
            $query = "SELECT sum(p.price) AS total FROM carts c INNER JOIN products p ON c.productID = p.productID WHERE c.sessionID = :sessionID";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(array(
                ':sessionID' => $sessionID));
            $data = $stmt->fetch();
            return $data["total"];
        } catch (PDOException $e) {
            die();
        }
    }

    public function refillProductsQuantityFromCart($sessionID)
    {
        try {
            $query = "UPDATE products 
            JOIN carts ON carts.productID = products.productID 
            SET products.quantity = products.quantity + carts.quantity
            WHERE carts.sessionID = :sessionID";

            $stmt = $this->pdo->prepare($query);
            $stmt->execute(array(
                ':sessionID' => $sessionID
            ));

            return $stmt->rowCount();
        } catch (PDOException $e) {
            die();
        }
    }

    public function removeProductsFromCart($sessionID)
    {
        try {
            $query = "DELETE FROM carts WHERE sessionID = :sessionID";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(array(
                ':sessionID' => $sessionID
            ));

            return $stmt->rowCount();
        } catch (PDOException $e) {
            die();
        }
    }

    public function getNumberOfProductsInCatalog()
    {
        try {
            $query = "SELECT count(*) AS no_of_products FROM products WHERE salePrice = 0";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            return intval($stmt->fetch()['no_of_products']);
        } catch (PDOException $e) {
            die();
        }
    }

    public function addProduct($name, $description, $file, $quantity, $price, $salePrice)
    {
        try {
            $query = "INSERT INTO products (productName, description, imageName, quantity, price, salePrice) 
            VALUES (:name, :description, :file, :quantity, :price, :salePrice)";

            $stmt = $this->pdo->prepare($query);
            $stmt->execute(array(
                ':name' => $name,
                ':description' => $description,
                ':file' => $file,
                ':quantity' => $quantity,
                ':price' => $price,
                'salePrice' => $salePrice
            ));
            return "'$name' added successfully.";
        } catch (PDOException $e) {
            die();
        }
    }

    public function getNumberOfProductsWithName($name)
    {
        try {
            $query = "SELECT count(*) AS no_of_products FROM products WHERE productName = :name";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(array(':name' => $name));
            return $stmt->fetch()['no_of_products'];
        } catch (PDOException $e) {
            die();
        }
    }

    public function getNumberOfProductsOnSale()
    {
        try {
            $query = "SELECT count(*) AS no_of_products FROM products WHERE salePrice != 0";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(array());
            return $stmt->fetch()['no_of_products'];
        } catch (PDOException $e) {
            die();
        }
    }

    public function getAllProductNames()
    {
        try {
            $query = "SELECT productName FROM products ORDER BY productID DESC";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Product");

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            die();
        }
    }

    public function getProductFromName($name)
    {
        try {
            $query = "SELECT * FROM products WHERE productName = :name";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(array(':name' => $name));
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Product");

            return $stmt->fetch();
        } catch (PDOException $e) {
            die();
        }
    }

    public function updateProductInformation($oldProductName, $newName, $newDescription, $newImage, $newQuantity, $newPrice, $newSalePrice)
    {
        try {
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

            return $stmt->rowCount();
        } catch (PDOException $e) {
            die();
        }
    }

    public function reduceQuantity($productId)
    {
        try {
            $query = "UPDATE products SET quantity = quantity - 1 WHERE productID = :productId";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(array(
                ':productId' => $productId
            ));
            return $stmt->rowCount();
        } catch (PDOException $e) {
            die();
        }
    }

    public function addUser($username, $password, $role)
    {
        try {
            $query = "INSERT INTO users(username, password, role) VALUES (:username, :password, :role)";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(array(
                ':username' => $username,
                ':password' => password_hash($password, PASSWORD_BCRYPT),
                ':role' => $role
            ));

            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            die();
        }
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
//    $db->addProduct($title, $description, $price, $quantity, $imageName, $salePrice);
//    $count += 1;
//
//    if ($count > 43) break;
//}

//$db = new dbMangaStore();
//$db->addUser("root", "root", 1);