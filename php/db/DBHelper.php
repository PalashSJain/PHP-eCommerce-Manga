<?php
/**
 * Created by PhpStorm.
 * User: Palash
 * Date: 3/12/2018
 * Time: 5:28 PM
 */

include_once $_SERVER['DOCUMENT_ROOT'] . "/php/utils/Constants.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/php/db/DB.MangaStore.class.php";

class DBHelper
{

    private $db;

    public function __construct()
    {
        $this->db = new dbMangaStore();
    }

    public function canSaleMoreProducts()
    {
        return $this->db->getNumberOfProductsOnSale() < 5;
    }

    public function canSaleFewerProducts(){
        return $this->db->getNumberOfProductsOnSale() > 3;
    }

    public function hasProductsWithName($name)
    {
        return $this->db->getNumberOfProductsWithName($name) > 0;
    }

    public function isAdmin($username, $password){
        return $this->db->getUser($username, $password)->getRole() == Constants::ROLE_ADMIN;
    }

    public function addToCart($productId, $sid)
    {
        $item = $this->db->getCartItem($productId, $sid);
        if (isset($item) && !empty($item)) {
            return $this->db->updateQuantityInCart($productId, $sid) > 0;
        } else {
            return $this->db->insertItemToCart($productId, $sid) != -1;
        }
    }

    public function reduceQuantity($productId)
    {
        return $this->db->reduceQuantity($productId) == 1;
    }
}