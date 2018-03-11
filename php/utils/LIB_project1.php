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

class LIB_project1
{

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
            if ($_COOKIE['SID'] != $sid) {
                $this->db->replaceCartWithNewSessionID($_COOKIE['SID'], $sid);
            }
        }
        setcookie('SID', $sid, time() + 60 * 60 * 24 * 30, "/");
    }

    public function isCartEmpty()
    {
        return $this->db->getNumberOfProductsInCart(session_id()) == 0;
    }

    public function getProductsInCart()
    {
        $rows = "";
        $carts = $this->db->getProductsInCart(session_id());
        foreach ($carts as $product) {
            $rows .= "<tr>";
            $rows .= "
                <th scope='row'>
                    <div class='row'>
                        <div class='col-md-3 no-padding-on-right'>
                            <img class='card-img-top' src='{$product['imageName']}' alt='Card image'>
                        </div>
                        <div class='col-md-9 card-body'>
                            <h4 class='card-title'>{$product['title']}</h4>
                            <p class='card-text'>{$product['description']}</p>
                        </div>
                    </div>
                </th>";
            $rows .= "<td>{$product['quantity']}</td>";
            $rows .= "<td>{$product['price']}</td>";
            $rows .= "<td>{$product['price']}</td>";
            $rows .= "</tr>";
        }
        return $rows;
    }

    public function getCartTotal()
    {
        $total = $this->db->getCartTotal(session_id());
        return "
            <tr>
                <td colspan='3'>Total Cost:</td>
                <td>{$total}</td>
            </tr>";
    }

    public function getCartTable()
    {
        return "
            <table class='table table-striped'>
                <thead>
                    <tr>
                        <th scope='col'>Product</th>
                        <th scope='col'>Quantity</th>
                        <th scope='col'>Price per Item</th>
                        <th scope='col'>Total Price</th>
                    </tr>
                </thead>
                <tbody>"
            . $this->getProductsInCart()
            . $this->getCartTotal() .
            "</tbody>
            </table>";
    }

    public function getBtnToClearCart()
    {
        return "
    <form method='post' onsubmit='return confirm(\"Are you sure you want to clear out the cart?\")'>
        <input type='submit' name='clearCart' value='Empty Cart' />
    </form>
    ";
    }

    public function clearCart()
    {
        $this->db->clearCart(session_id());
    }

    public function showEmptyCart()
    {
        return "
<div class='jumbotron h-100'>
  <h1>Your cart is empty!</h1> 
  <p>Click <a href='/PHP-eCommerce-Manga/php/index.php'>here</a> to start shopping...</p> 
</div>";
    }
}