<?php
/**
 * Created by PhpStorm.
 * User: Palash
 * Date: 2/22/2018
 * Time: 10:12 PM
 */

include_once $_SERVER['DOCUMENT_ROOT'] . "/php/utils/Product.php";

class DB{

    public function getProductsOnSale()
    {
        $products = array();

        $products[] = new Product("One Piece", "Gomu Gomu no", 100, 10, "https://nerds4lifeblog.com/wp-content/uploads/2018/01/1447745856-d1263c53574b445d10257a1e0ae4b89c.jpeg", 80);
        $products[] = new Product("Bleach", "Zametsu", 200, 5, "https://nerds4lifeblog.com/wp-content/uploads/2018/01/1447745856-d1263c53574b445d10257a1e0ae4b89c.jpeg", 180);
        $products[] = new Product("One Piece", "Gomu Gomu no", 100, 10, "https://nerds4lifeblog.com/wp-content/uploads/2018/01/1447745856-d1263c53574b445d10257a1e0ae4b89c.jpeg", 80);
        $products[] = new Product("Bleach", "Zametsu", 200, 5, "https://nerds4lifeblog.com/wp-content/uploads/2018/01/1447745856-d1263c53574b445d10257a1e0ae4b89c.jpeg", 180);
        $products[] = new Product("One Piece", "Gomu Gomu no", 100, 10, "https://nerds4lifeblog.com/wp-content/uploads/2018/01/1447745856-d1263c53574b445d10257a1e0ae4b89c.jpeg", 80);
        $products[] = new Product("Bleach", "Zametsu", 200, 5, "https://nerds4lifeblog.com/wp-content/uploads/2018/01/1447745856-d1263c53574b445d10257a1e0ae4b89c.jpeg", 180);
        $products[] = new Product("One Piece", "Gomu Gomu no", 100, 10, "https://nerds4lifeblog.com/wp-content/uploads/2018/01/1447745856-d1263c53574b445d10257a1e0ae4b89c.jpeg", 80);
        $products[] = new Product("Bleach", "Zametsu", 200, 5, "https://nerds4lifeblog.com/wp-content/uploads/2018/01/1447745856-d1263c53574b445d10257a1e0ae4b89c.jpeg", 180);

        return $products;

    }
}