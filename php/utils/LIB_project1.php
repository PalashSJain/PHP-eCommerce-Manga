<?php
/**
 * Created by PhpStorm.
 * User: Palash
 * Date: 2/22/2018
 * Time: 10:12 PM
 */

include_once $_SERVER['DOCUMENT_ROOT'] . "/php/utils/Sale.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/php/utils/Catalog.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/php/db/DB.class.php";

class LIB_project1{

    private $sale, $catalog;

    private $db;

    function __construct()
    {
        $this->db = new DB();
        $this->sale = new Sale();
        $this->catalog = new Catalog();
    }

    function __destruct()
    {
        $this->db = null;
    }

    public function getProductsOnSale()
    {
        $products = $this->db->getProductsOnSale();
        return $this->sale->makeProductsOnSale($products);
    }

    public function getProductsOnCatalog()
    {
        $products = $this->db->getProductsOnCatalog();
        return $this->catalog->makeProductsOnCatalog($products);
    }
}