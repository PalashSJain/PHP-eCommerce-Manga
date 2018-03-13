<?php
/**
 * Created by PhpStorm.
 * User: Palash
 * Date: 3/12/2018
 * Time: 5:28 PM
 */

include_once $_SERVER['DOCUMENT_ROOT'] . "/php/db/DB.MangaStore.class.php";

class DBValidator
{

    private $db;

    public function __construct()
    {
        $this->db = new dbMangaStore();
    }

    public function canSaleMoreProducts()
    {
        return false;
    }

    public function hasProductsWithName($name)
    {
        return count($this->db->getProductsWithName($name)) > 0;
    }
}