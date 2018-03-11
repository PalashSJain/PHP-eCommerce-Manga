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
        $query = "select * from products where salePrice != 0";
        $stmt = $this->dbh->prepare($query);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, "Product");

        $products = $stmt->fetchAll();

        return $products;
    }

    public function getProductsOnCatalog($pageNumber)
    {
        $query = "select * from products where salePrice = 0 limit ".Constants::PAGE_SIZE." offset ". ($pageNumber * Constants::PAGE_SIZE);
        $stmt = $this->dbh->prepare($query);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, "Product");

        $products = $stmt->fetchAll();

        return $products;
    }

    public function insert($title, $description, $price, $quantity, $imageName, $salePrice)
    {
        $query = "insert into 
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
        $query = "select count(*) as no_of_users from users where UserID = :userID and Password = :pwd";
        $stmt = $this->dbh->prepare($query);
        $stmt->execute(array(
            ':userID' => $userID,
            ':pwd' => $pwd));
        $data = $stmt->fetch();
        return $data["no_of_users"] == 1;
    }

    public function addToCart($productId, $sid)
    {
        $query = "insert into carts (sessionID, productID) values (:sessionID, :productID)";
        $stmt = $this->dbh->prepare($query);
        $stmt->execute(array(
            ':sessionID' => $sid,
            ':productID' => $productId
        ));
    }

    public function replaceCartWithNewSessionID($oldID, $newID)
    {
        $query = "update carts set sessionID = :newID where sessionID = :oldID";
        $stmt = $this->dbh->prepare($query);
        $stmt->execute(array(
            ':newID' => $newID,
            ':oldID' => $oldID
        ));
    }

    public function getNumberOfProductsInCart($sessionID)
    {
        $query = "select count(*) as no_of_products from carts where sessionID = :sessionID";
        $stmt = $this->dbh->prepare($query);
        $stmt->execute(array(
            ':sessionID' => $sessionID));
        $data = $stmt->fetch();
        return $data["no_of_products"];
    }

    public function getProductsInCart($sessionID)
    {
        $query = "select c.quantity as quantity, p.productName as title, p.price as price, p.description as description, p.salePrice as salePrice,
 p.imageName as imageName from carts c INNER join products p on c.productID = p.productID where sessionID = :sessionID";
        $stmt = $this->dbh->prepare($query);
        $stmt->execute(array(
            ':sessionID' => $sessionID));
        return $stmt->fetchAll();
    }

    public function getCartTotal($sessionID)
    {
        $query = "select sum(p.price) as total from carts c inner join products p on c.productID = p.productID where c.sessionID = :sessionID";
        $stmt = $this->dbh->prepare($query);
        $stmt->execute(array(
            ':sessionID' => $sessionID));
        $data = $stmt->fetch();
        return $data["total"];
    }

    public function clearCart($sessionID)
    {
        $query = "delete from carts where sessionID = :sessionID";
        $stmt = $this->dbh->prepare($query);
        $stmt->execute(array(
            ':sessionID' => $sessionID
    ));
    }

    public function getNumberOfProductsInCatalog()
    {
        $query = "select count(*) as no_of_products from products where salePrice = 0";
        $stmt = $this->dbh->prepare($query);
        $stmt->execute();
        return intval($stmt->fetch()['no_of_products']);
    }

}

//$db = new DB();
//$dom = new DOMDocument();
//$dom->load("input.xml");
//$articles = $dom->getElementsByTagName("article");
//foreach ($articles as $article) {
//    $title = $article->getElementsByTagName("h4")->item(0)->nodeValue;
//    $description = "";
//    $price = 15;
//    $quantity = 1;
//    $imageName = $article->getElementsByTagName("img")->item(0)->getAttribute("src");
//    $salePrice = 0;
//
//    $db->insert($title, $description, $price, $quantity, $imageName, $salePrice);
//}