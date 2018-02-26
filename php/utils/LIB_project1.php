<?php
/**
 * Created by PhpStorm.
 * User: Palash
 * Date: 2/22/2018
 * Time: 10:12 PM
 */

include_once $_SERVER['DOCUMENT_ROOT'] . "/php/utils/Sale.php";

class LIB_project1{

    private $sale;

    function __construct()
    {
        $this->sale = new Sale();
    }

    public function getProductsOnSale()
    {
        return $this->sale->makeProductsOnSale();
    }
}