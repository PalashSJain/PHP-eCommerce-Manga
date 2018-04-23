<?php
/**
 * Created by PhpStorm.
 * User: Palash
 * Date: 3/12/2018
 * Time: 5:28 PM
 */

include_once ROOT . "utils/Constants.php";
include_once ROOT . "utils/User.php";
include_once ROOT . "db/DB.MangaStore.class.php";

class DBHelper
{

    private $db;

    public function __construct()
    {
        $this->db = new dbMangaStore();
    }

    /**
     * @return bool true if number of products on sale is less than 5, false otherwise
     */
    public function canSaleMoreProducts()
    {
        return $this->db->getNumberOfProductsOnSale() < 5;
    }

    /**
     * @return bool true if number of products on sale is more than 3, false otherwise
     */
    public function canSaleFewerProducts()
    {
        return $this->db->getNumberOfProductsOnSale() > 3;
    }

    /**
     * @param $name string name of the product
     * @return bool true if there is at least one product with the name $name
     */
    public function hasProductsWithName($name)
    {
        return $this->db->getNumberOfProductsWithName($name) > 0;
    }

    public function getUser($username, $password)
    {
        $user = $this->db->getUser($username);

        if (isset($user) && !empty($user)) {
            if (password_verify($password, $user->getPassword())) {
                return $user;
            } else {
                return "Incorrect password for this user.";
            }
        } else {
            return "User with this ID does not exist.";
        }
    }

    /**
     * @param $productId int
     * @param $sid string session id
     * @return bool true if at least one product in cart was successfully updated or a new entry in the cart was inserted.
     * false otherwise
     */
    public function addToCart($productId, $sid)
    {
        $item = $this->db->getCartItem($productId, $sid);
        if (isset($item) && !empty($item)) {
            return $this->db->updateQuantityInCart($productId, $sid) > 0;
        } else {
            return $this->db->insertItemToCart($productId, $sid) != -1;
        }
    }

    /**
     * @param $productId int
     * @return bool if exactly one product's quantity was reduced by 1
     */
    public function reduceQuantity($productId)
    {
        return $this->db->reduceQuantity($productId) == 1;
    }

    /**
     * @param $sessionID string session id
     * @return bool true if there is no item in cart for the given session id, false other wise
     */
    public function isCartEmpty($sessionID)
    {
        return $this->db->getNumberOfProductsInCart($sessionID) == 0;
    }

    /**
     * @param $session_id string session id
     * @return bool true if atleast one product was deleted from the cart
     */
    public function clearCart($session_id)
    {
        $this->db->refillProductsQuantityFromCart($session_id);
        return $this->db->removeProductsFromCart($session_id) > 0;
    }



}