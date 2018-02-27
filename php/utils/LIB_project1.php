<?php
/**
 * Created by PhpStorm.
 * User: Palash
 * Date: 2/22/2018
 * Time: 10:12 PM
 */

include_once $_SERVER['DOCUMENT_ROOT'] . "/php/utils/Sale.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/php/utils/Catalog.php";

class LIB_project1{

    private $sale, $catalog;

    function __construct()
    {
        $this->sale = new Sale();
        $this->catalog = new Catalog();
    }

    public function getProductsOnSale()
    {
        return $this->sale->makeProductsOnSale();
    }

    public function getProductsOnCatalog()
    {
        return $this->catalog->makeProductsOnCatalog();
    }
}