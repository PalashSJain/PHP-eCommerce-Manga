<?php
/**
 * Created by PhpStorm.
 * User: Palash
 * Date: 2/22/2018
 * Time: 10:12 PM
 */

include_once ROOT . "utils/Product.php";
include_once ROOT . "utils/User.php";
include_once ROOT . "utils/Cart.php";

class dbMangaStore
{

    private $pdo;

    function __construct()
    {
        try {
            $this->pdo = new PDO("mysql:host=localhost;dbname=mangastore", 'root', '');
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            var_dump($e->getMessage());
            die();
        }
    }

    /**
     * @return array Products in the database whose sale price is not 0, that is they are on sale.
     */
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

    /**
     * @param $limit int maximum number of Products
     * @param $offset int starting point to look for Products
     * @return array of Products whose sale price is 0, that is they are not on sale
     */
    public function getProductsInCatalog($limit, $offset)
    {
        try {
            $query = "SELECT * FROM products WHERE salePrice = 0 LIMIT :limit OFFSET :offset";
            $stmt = $this->pdo->prepare($query);

            $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Product");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    /**
     * @param $userID string username
     * @return User object of the username
     */
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

    /**
     * @param $productId int ID of the product
     * @param $sid string session id
     * @return Cart object of the product with ID $productId and user session $sid
     */
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

    /**
     * @param $productId int ID of the product
     * @param $sid string session id
     * @return int number of carts updated with one more quantity
     */
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

    /**
     * @param $productId int product id
     * @param $sid string session id
     * @return string last inserted id in table carts
     */
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

    /**
     * @param $oldID string old session id
     * @param $newID string new session id
     * @return int number of rows updated in table carts
     */
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

    /**
     * @param $sessionID string session id
     * @return int number of products in cart with session id $sessionID
     */
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

    /**
     * @param $sessionID string session id
     * @return array of quantity, productname, title, price, description, saleprice and imagename
     */
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

    /**
     * @param $sessionID string session id
     * @return int number of rows updated after refilling quantities in products table from the carts table
     */
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

    /**
     * @param $sessionID string session id
     * @return int number of rows deleted from carts table with the session id as $sessionID
     */
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

    /**
     * @return int number of products whose sale price is 0
     */
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

    /**
     * @param $name string name
     * @param $description string description
     * @param $file string relative file path
     * @param $quantity int
     * @param $price int
     * @param $salePrice int
     * @return string message to display if product was successfully added to products table
     */
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

            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            die();
        }
    }

    /**
     * @param $name string product name
     * @return int number of products with the name $name
     */
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

    /**
     * @return int number of products on sale
     */
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

    /**
     * @return array of product, with just productName filled up
     */
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

    /**
     * @param $name string product name
     * @return Product all products with the name $name
     */
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

    /**
     * @param $oldProductName string old product name
     * @param $newName string
     * @param $newDescription string
     * @param $newImage string file path
     * @param $newQuantity int
     * @param $newPrice int
     * @param $newSalePrice int
     * @return int number of rows updated
     */
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

    /**
     * @param $productId int product whose quantity has to be reduced by 1 in the products table
     * @return int number of rows updated
     */
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

    /**
     * @param $username string
     * @param $password string
     * @param $role int id. (1 for Admin)
     * @return string last inserted id of user
     */
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

/*
$db = new dbMangaStore();
$dom = new DOMDocument();
$dom->load("input.xml");
$articles = $dom->getElementsByTagName("article");
$count = 1;
foreach ($articles as $article) {
    $title = trim($article->getElementsByTagName("h4")->item(0)->nodeValue);
    $description = "";
    $price = 15;
    $quantity = 100;
    $imageName = "/images/$count.jpg";
    $salePrice = 0;

    $db->addProduct($title, $description, $imageName, $price, $quantity, $salePrice);
    $count += 1;

    if ($count > 43) break;
}

$db = new dbMangaStore();
$db->addUser("root", "root", 1);
*/