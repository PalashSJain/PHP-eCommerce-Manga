<?php
/**
 * Created by PhpStorm.
 * User: Palash
 * Date: 2/22/2018
 * Time: 10:12 PM
 */

include_once $_SERVER['DOCUMENT_ROOT'] . "/php/utils/Product.php";

class DB{

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
        $query = "select * from products where salePrice = 0";
        $stmt = $this->dbh->prepare($query);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, "Product");

        $products = $stmt->fetchAll();

        return $products;

    }
}