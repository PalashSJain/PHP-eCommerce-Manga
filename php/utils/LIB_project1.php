<?php
/**
 * Created by PhpStorm.
 * User: Palash
 * Date: 2/22/2018
 * Time: 10:12 PM
 */

include_once $_SERVER['DOCUMENT_ROOT'] . "/php/utils/Sale.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/php/utils/Catalog.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/php/utils/Login.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/php/db/DB.MangaStore.class.php";

class LIB_project1{

    private $sale, $catalog, $login;

    private $db;

    function __construct()
    {
        $this->db = new dbMangaStore();
        $this->sale = new Sale();
        $this->catalog = new Catalog();
        $this->login = new Login();
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

    public function addProductToCart($productId, $sid)
    {
        $this->db->addToCart($productId, $sid);
    }

    public function showAdminLoginPage()
    {
        return $this->login->showLoginPage();
    }

    public function isAdmin($userID, $pwd)
    {
        return $this->db->isAdmin($userID, $pwd);
    }

    public function onLoad()
    {
        $sid = session_id();
        if (isset($_COOKIE['SID'])) {
            if ($_COOKIE['SID'] != $sid){
                $this->db->replaceCartWithNewSessionID($_COOKIE['SID'], $sid);
            }
        }
        setcookie('SID', $sid, time()+60*60*24*30);
    }
}