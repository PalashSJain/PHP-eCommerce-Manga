<?php
/**
 * Created by PhpStorm.
 * User: Palash
 * Date: 2/26/2018
 * Time: 4:03 PM
 */

include_once $_SERVER['DOCUMENT_ROOT'] . "/php/utils/Product.php";

class Sale
{
    public function makeProductsOnSale($products = [])
    {
        $html = "";

        $html .= "<div class='card-columns'>";
        foreach ($products as $product) {
            $html .= $product->makeHTMLCode();
        }
        $html .= "</div>";

        return $html;
    }

}