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

    public function makeProductsOnSale()
    {
        $html = "";

        $html .= "<div class='card-columns'>";
        $products = $this->db->getProductsOnSale();
        foreach ($products as $product) {
            $html .= $this->makeHTMLCodeForProduct($product);
        }
        $html .= "</div>";

        return $html;
    }

    private function makeHTMLCodeForProduct(Product $product)
    {
        $this->product = $product;
        return
            "<div class='card'>
                <img class='card-img-top' src='{$this->product->getImageName()}' alt='Card image'>
                <div class='card-body'>
                    <h4 class='card-title'>{$this->product->getProductName()}</h4>
                    <p class='card-text'>{$this->product->getDescription()}</p>
                </div>
            </div>";
    }
}