<?php
/**
 * Created by PhpStorm.
 * User: Palash
 * Date: 2/22/2018
 * Time: 10:12 PM
 */

include_once $_SERVER['DOCUMENT_ROOT'] . "/php/utils/Constants.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/php/utils/Sale.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/php/utils/Catalog.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/php/db/DB.MangaStore.class.php";

class LIB_project1
{

    private $sale, $catalog;

    private $db;

    function __construct()
    {
        $this->db = new dbMangaStore();
        $this->sale = new Sale();
        $this->catalog = new Catalog();
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

    public function getProductsOnCatalog($page)
    {
        $no_of_products = $this->db->getNumberOfProductsInCatalog();
        if ($page < 1) {
            $page = 1;
            header("Location: /PHP-eCommerce-Manga/php/index.php?page=$page");
            die();
        } else if (($page * (Constants::PAGE_SIZE - 1)) > $no_of_products) {
            $page = intval($no_of_products / (Constants::PAGE_SIZE - 1));
            header("Location: /PHP-eCommerce-Manga/php/index.php?page=$page");
            die();
        }
        $products = $this->db->getProductsOnCatalog($page);
        return $this->catalog->makeProductsOnCatalog($products);
    }

    public function addProductToCart($productId, $sid)
    {
        $this->db->addToCart($productId, $sid);
    }

    public function reduceQuantity($productId)
    {
        $this->db->reduceQuantity($productId);
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
        $netSum = 0;
        $carts = $this->db->getProductsInCart(session_id());

        foreach ($carts as $product) {
            $rows .= "<tr>";
            $rows .= "
                <th scope='row'>
                    <div class='row'>
                        <div class='col-md-3 no-padding'>
                            <img class='card-img-top' src='{$product['imageName']}' alt='Card image'>
                        </div>
                        <div class='col-md-9 card-body'>
                            <h4 class='card-title'>{$product['title']}</h4>
                            <p class='card-text'>{$product['description']}</p>
                        </div>
                    </div>
                </th>";
            $rows .= "<td>{$product['quantity']}</td>";
            if (intval($product['salePrice']) != 0) {
                $rows .= "<td>{$product['salePrice']}</td>";
                $total = intval($product['quantity']) * intval($product['salePrice']);
            } else {
                $rows .= "<td>{$product['price']}</td>";
                $total = intval($product['quantity']) * intval($product['price']);
            }

            $rows .= "<td>{$total}</td>";
            $rows .= "</tr>";

            $netSum = $netSum + $total;
        }

        $rows .= "<tr>
                <td colspan='3'>Total Cost:</td>
                <td>{$netSum}</td>
            </tr>";
        return $rows;
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
            . $this->getProductsInCart() .
            "</tbody>
            </table>";
    }

    public function getBtnToClearCart()
    {
        return "
            <form method='post' onsubmit='return confirm(\"Are you sure you want to clear out the cart?\")'>
                <input type='submit' class='btn btn-warning' name='clearCart' value='Empty Cart' />
            </form>";
    }

    public function clearCart()
    {
        $this->db->clearCart(session_id());
    }

    public function showEmptyCart()
    {
        return "
            <div class='jumbotron'>
              <h1>Your cart is empty!</h1> 
              <p>Click <a href='/PHP-eCommerce-Manga/php/index.php'>here</a> to start shopping...</p> 
            </div>";
    }

    public function getPagination($page)
    {
        return "
            <ul class='pagination justify-content-center'>
              <li class='page-item'><a class='page-link' href='/PHP-eCommerce-Manga/php/index.php?page=" . ($page - 1) . "'>Previous</a></li>
              <li class='page-item active'><a class='page-link'>$page</a></li>
              <li class='page-item'><a class='page-link' href='/PHP-eCommerce-Manga/php/index.php?page=" . ($page + 1) . "'>Next</a></li>
            </ul>";
    }

    public function getDropdownOfAllProducts()
    {
        return "
<div class='dropdown'>
  <button class='btn btn-secondary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
    Dropdown button
  </button>
  <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
    <a class='dropdown-item' href='#'>Action</a>
    <a class='dropdown-item' href='#'>Another action</a>
    <a class='dropdown-item' href='#'>Something else here</a>
  </div>
</div>";
    }

    public function addProduct($name, $description, $file,
                               $quantity, $price, $salePrice)
    {
        return $this->db->addProduct($name, $description, $file,
            $quantity, $price, $salePrice);
    }

    public function showInputFieldAsRow($field, $type, $obj, $prepend = '', $value = '')
    {
        if (isset($obj)) {
            $errorClass = $this->getErrorClass($obj);
            $errorMessage = $this->getErrorMessage($obj);
        } else {
            $errorClass = "";
            $errorMessage = "";
        }
        return "<div class='form-group row'>
                     <label for='salePrice' class='col-sm-4 col-form-label'>$field</label>
                     <div class='col-sm-8 input-group mb-2'>"
            . ((isset($prepend) && empty($prepend)) ? "" : "<div class='input-group-prepend'> <div class='input-group-text'>$prepend</div></div>")
            . "<input type='$type' class='form-control $errorClass' 
                        id='salePrice' name='$field' placeholder='$field' value='$value' required>
                        $errorMessage
                     </div>
                  </div>";
    }

    public function showFileFieldAsRow($field, $type, $obj)
    {
        if (isset($obj)) {
            $errorClass = $this->getErrorClass($obj);
            $errorMessage = $this->getErrorMessage($obj);
        } else {
            $errorClass = "";
            $errorMessage = "";
        }
        return "<div class='form-group row'>
                     <label for='salePrice' class='col-sm-4 col-form-label'>$field</label>
                     <div class='col-sm-8 input-group mb-2'>
                        <input type='$type' class='form-control $errorClass' accept='image/*'
                        id='salePrice' name='$field' placeholder='$field'>
                        $errorMessage
                     </div>
                     <div class='col-sm-12'><small>A default image will be used if no image is provided.</small></div>
                  </div>";
    }

    public function getErrorMessage($name)
    {
        if (!empty($name['error'])) return "<div class='invalid-feedback'>{$name['error']}</div>";
        else return "";
    }

    public function getErrorClass($name)
    {
        return isset($name['status']) && $name['status'] ? "" : "is-invalid";
    }

    public function showInputFieldVertically($field, $type, $obj, $value = '')
    {
        if (isset($obj)) {
            $errorClass = $this->getErrorClass($obj);
            $errorMessage = $this->getErrorMessage($obj);
        } else {
            $errorClass = "";
            $errorMessage = "";
        }
        return "<div class='form-group'>
                     <label for='salePrice' class='col-sm-12 col-form-label'>$field</label>
                     <div class='col-sm-12 input-group mb-2'>
                        <input type='$type' class='form-control $errorClass' 
                        id='salePrice' name='$field' placeholder='$field' value='$value' required>
                        $errorMessage
                     </div>
                  </div>";
    }

    public function showTextFieldVertically($field, $obj, $value = '')
    {
        if (isset($obj)) {
            $errorClass = $this->getErrorClass($obj);
            $errorMessage = $this->getErrorMessage($obj);
        } else {
            $errorClass = "";
            $errorMessage = "";
        }
        return "<div class='form-group'>
                     <label for='description' class='col-sm-12 col-form-label'>$field</label>
                     <div class='col-sm-12 input-group mb-2'>
                        <textarea class='form-control $errorClass' id='description' name='$field' rows='3'>$value</textarea>
                        $errorMessage
                     </div>
                  </div>";
    }

    public function getProductOptions($option)
    {
        $output = "<option value='" . Constants::DEFAULT_DROPDOWN_OPTION . "'>" . Constants::DEFAULT_DROPDOWN_OPTION . "</option>";
        $products = $this->db->getAllProducts();
        foreach ($products as $product) {
            if ($product->getProductName() == $option) {
                $output .= "<option value='" . $product->getProductName() . "' selected>" . $product->getProductName() . "</option>";
            } else
                $output .= "<option value='" . $product->getProductName() . "'>" . $product->getProductName() . "</option>";
        }
        return $output;
    }

    public function getProductFromName($name)
    {
        return $this->db->getProductFromName($name);
    }

    public function updateProduct($oldProductName, $newName, $newDescription, $newImage, $newQuantity, $newPrice, $newSalePrice)
    {
        return $this->db->updateProductInformation($oldProductName, $newName, $newDescription, $newImage, $newQuantity, $newPrice, $newSalePrice);
    }

    public function isDefaultDropdownOption($option)
    {
        return $option == Constants::DEFAULT_DROPDOWN_OPTION;
    }

}