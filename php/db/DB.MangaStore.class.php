<?php
/**
 * Created by PhpStorm.
 * User: Palash
 * Date: 2/22/2018
 * Time: 10:12 PM
 */

include_once $_SERVER['DOCUMENT_ROOT'] . "/php/utils/Product.php";

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

    public function getProductsOnCatalog()
    {
        $query = "select * from products where salePrice = 0 limit 9";
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