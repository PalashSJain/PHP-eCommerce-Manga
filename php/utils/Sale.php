<?php
/**
 * Created by PhpStorm.
 * User: Palash
 * Date: 2/26/2018
 * Time: 4:03 PM
 */

include_once $_SERVER['DOCUMENT_ROOT'] . "/php/db/DB.class.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/php/utils/Product.php";

class Sale
{
    private $db, $product;

    function __construct()
    {
        $this->db = new DB();
    }

    function __destruct()
    {
        $this->db = null;
    }

    public function makeProductsOnSale()
    {
        $html = "";

        $html .= "<div class='card-columns'>";
        $products = $this->db->getProductsOnSale();
        foreach ($products as $product) {
            $this->product = $product;
            $html .= $this->product->makeHTMLCode();
        }
        $html .= "</div>";

        return $html;
    }

}