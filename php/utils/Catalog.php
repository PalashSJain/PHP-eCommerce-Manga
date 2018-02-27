<?php
/**
 * Created by PhpStorm.
 * User: Palash
 * Date: 2/26/2018
 * Time: 4:03 PM
 */

class Catalog
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

    public function makeProductsOnCatalog()
    {
        $html = "";

        $html .= "<div class='card-columns'>";
        $products = $this->db->getProductsOnCatalog();
        foreach ($products as $product) {
            $this->product = $product;
            $html .= $this->product->makeHTMLCode();
        }
        $html .= "</div>";

        return $html;
    }

}