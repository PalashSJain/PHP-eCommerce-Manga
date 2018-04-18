<?php
/**
 * Created by PhpStorm.
 * User: Palash
 * Date: 2/22/2018
 * Time: 10:12 PM
 */

include_once ROOT . "utils/Constants.php";
include_once ROOT . "utils/Product.php";
include_once ROOT . "db/DBHelper.php";
include_once ROOT . "db/DB.MangaStore.class.php";

class LIB_project1
{

    private $dbhelper, $db;

    function __construct()
    {
        $this->db = new dbMangaStore();
        $this->dbhelper = new DBHelper();
    }

    function __destruct()
    {
        $this->db = null;
    }

    /**
     * @return string HTML code for showing all products on sale as a masonry of cards
     */
    public function getProductsOnSale()
    {
        $products = $this->db->getProductsOnSale();
        $html = "";

        $html .= "<div class='card-columns'>";
        foreach ($products as $product) {
            $html .= $product->makeHTMLCode();
        }
        $html .= "</div>";

        return $html;
    }

    /**
     * @param $page int page number of the index page
     * @return string HTML code of products in catalog as a masonry of cards.
     * List of products is limited by a page offset and a limit of Costants::PAGE_SIZE.
     *
     * If page number is out of scope, then redirect the application to relevant page
     */
    public function showProductsInCatalog($page)
    {
        $no_of_products = $this->db->getNumberOfProductsInCatalog();

        if ($page < 1) {
            $page = 1;
            header("Location: index.php?page=$page");
            die();
        } else if ((($page - 1) * Constants::PAGE_SIZE) > $no_of_products) {
            $page = ceil($no_of_products / Constants::PAGE_SIZE); // 9
            header("Location: index.php?page=$page");
            die();
        }

        $products = $this->db->getProductsInCatalog(Constants::PAGE_SIZE, ($page - 1) * Constants::PAGE_SIZE);
        $html = "";

        $html .= "<div class='card-columns'>";
        foreach ($products as $product) {
            $html .= $product->makeHTMLCode();
        }
        $html .= "</div>";

        return $html;
    }

    /**
     * @param $productId int product id
     * @param $sid string session id
     * @return bool true if product was successfully added to cart, false otherwise
     */
    public function addProductToCart($productId, $sid)
    {
        return $this->dbhelper->addToCart($productId, $sid);
    }

    /**
     * @param $productId int product id
     * @return bool true if products quantity was successfully reduced by 1 in the database
     */
    public function reduceQuantity($productId)
    {
        return $this->dbhelper->reduceQuantity($productId);
    }

    /**
     * @param $userID string user name
     * @param $pwd string password
     * @return bool|string true if user is admin, error message otherwise
     */
    public function isAdmin($userID, $pwd)
    {
        return $this->dbhelper->isAdmin($userID, $pwd);
    }

    /**
     * To keep cart life alive.
     * Needs Cookies enabled by end user.
     * update $_COOKIE['SID'] and carts table with latest session id by replacing old session id with the new session id
     *
     * Life of cookie is 1 month, updated every time the page is loaded.
     */
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

    /**
     * @param $sid string session id
     * @return bool true if cart is empty for the given session id, false otherwise
     */
    public function isCartEmpty($sid)
    {
        return $this->dbhelper->isCartEmpty($sid);
    }

    /**
     * @param $sid string session id
     * @return string HTML code for displaying each product in cart as a row
     */
    public function showProductsInCart($sid)
    {
        $rows = "";
        $netSum = 0;
        $carts = $this->db->getProductsInCart($sid);

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

    /**
     * @param $sid string session id
     * @return string HTML code for displaying table headers and table rows of cart
     */
    public function showCartTable($sid)
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
            . $this->showProductsInCart($sid) .
            "</tbody>
            </table>";
    }

    /**
     * @return string HTML form for the button to clear cart
     */
    public function showBtnToClearCart()
    {
        return "
            <form method='post' onsubmit='return confirm(\"Are you sure you want to clear out the cart?\")'>
                <input type='submit' class='btn btn-warning' name='clearCart' value='Empty Cart' />
            </form>";
    }

    /**
     * @return bool true if cart items were successfully returned to products table and then deleted from the carts table
     */
    public function clearCart()
    {
        return $this->dbhelper->clearCart(session_id());
    }

    /**
     * @return string HTML code to show if the cart is empty
     */
    public function showEmptyCart()
    {
        return "
            <div class='jumbotron'>
              <h1>Your cart is empty!</h1> 
              <p>Click <a href='index.php'>here</a> to start shopping...</p> 
            </div>";
    }

    /**
     * @param $page int page number
     * @return string HTML pagination code with buttons for previous, current and next page
     */
    public function showPagination($page)
    {
        return "
            <ul class='pagination justify-content-center'>
              <li class='page-item'><a class='page-link' href='index.php?page=" . ($page - 1) . "'>Previous</a></li>
              <li class='page-item active'><a class='page-link'>$page</a></li>
              <li class='page-item'><a class='page-link' href='index.php?page=" . ($page + 1) . "'>Next</a></li>
            </ul>";
    }

    /**
     * @param $name string
     * @param $description string
     * @param $file string file path
     * @param $quantity int
     * @param $price int
     * @param $salePrice int
     * @return bool true if a new product was successfully added to the products table
     */
    public function addProduct($name, $description, $file, $quantity, $price, $salePrice)
    {
        if ($this->db->addProduct($name, $description, $file, $quantity, $price, $salePrice) != -1)
            return "'$name' added successfully.";
        else return "Failed to insert '$name'";
    }

    /**
     * @param $name array of 'status', 'data' and 'error'
     * @return string HTML code of error message if 'error' has been set, blank otherwise
     */
    public function getErrorMessage($name)
    {
        if (!empty($name['error'])) return "<div class='invalid-feedback'>{$name['error']}</div>";
        else return "";
    }

    /**
     * @param $name array of 'status', 'data' and 'error'
     * @return string CSS class 'is-invalid' if status is false
     */
    public function getErrorClass($name)
    {
        return isset($name['status']) && $name['status'] ? "" : "is-invalid";
    }

    /**
     * @param $field string field header name
     * @param $type string type of field: text, number
     * @param $obj array of 'status', 'data' and 'error'
     * @param string $prepend character to be shown as part of input. '$' for example
     * @param string $value default value
     * @return string HTML code for the entire element
     */
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
                     <label for='salePrice' class='col-sm-4 col-form-label'><strong>$field</strong></label>
                     <div class='col-sm-8 input-group mb-2'>"
            . ((isset($prepend) && empty($prepend)) ? "" : "<div class='input-group-prepend'> <div class='input-group-text'>$prepend</div></div>")
            . "<input type='$type' class='form-control $errorClass' 
                         name='$field' placeholder='$field' value='$value' required>
                        $errorMessage
                     </div>
                  </div>";
    }

    /**
     * @param $field string field header name
     * @param $type string type of field: file
     * @param $obj array of 'status', 'data' and 'error'
     * @return string HTML code for file element
     */
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
                     <label for='salePrice' class='col-sm-4 col-form-label'><strong>$field</strong></label>
                     <div class='col-sm-8 input-group mb-2'>
                        <input type='$type' class='form-control $errorClass' accept='image/*'
                         name='$field' placeholder='$field'>
                        $errorMessage
                     </div>
                  </div>";
    }

    /**
     * @param $field string field header name
     * @param $type string type of field: file
     * @param $obj array of 'status', 'data' and 'error'
     * @param string $value default value
     * @return string HTML code for input fields
     */
    public function showInputFieldVertically($field, $type, $obj = null, $value = '')
    {
        if (isset($obj)) {
            $errorClass = $this->getErrorClass($obj);
            $errorMessage = $this->getErrorMessage($obj);
        } else {
            $errorClass = "";
            $errorMessage = "";
        }
        return "<div class='form-group'>
                     <label for='salePrice' class='col-sm-12 col-form-label'><strong>$field</strong></label>
                     <div class='col-sm-12 input-group mb-2'>
                        <input type='$type' class='form-control $errorClass' 
                         name='$field' placeholder='$field' value='$value' required>
                        $errorMessage
                     </div>
                  </div>";
    }

    /**
     * @param $field string field header name
     * @param $obj array of 'status', 'data' and 'error'
     * @param string $value default value
     * @return string HTML code for textfield element vertically aligned
     */
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
                     <label for='description' class='col-sm-12 col-form-label'><strong>$field</strong></label>
                     <div class='col-sm-12 input-group mb-2'>
                        <textarea class='form-control $errorClass' id='description' name='$field' rows='3'>$value</textarea>
                        $errorMessage
                     </div>
                  </div>";
    }

    /**
     * @param $option string dropdown option either selected by user or the default one
     * @return string HTML code for options container DEFAULT_DROPDOWN_OPTION and all product names
     */
    public function getProductOptions($option)
    {
        $output = "<option value='" . Constants::DEFAULT_DROPDOWN_OPTION . "'>" . Constants::DEFAULT_DROPDOWN_OPTION . "</option>";
        $products = $this->db->getAllProductNames();
        foreach ($products as $product) {
            if ($product->getProductName() == $option) {
                $output .= "<option value='" . $product->getProductName() . "' selected>" . $product->getProductName() . "</option>";
            } else
                $output .= "<option value='" . $product->getProductName() . "'>" . $product->getProductName() . "</option>";
        }
        return $output;
    }

    /**
     * @param $name string product name
     * @return Product object matched from the given name
     */
    public function getProductFromName($name)
    {
        return $this->db->getProductFromName($name);
    }

    /**
     * @param $oldProductName string
     * @param $newName string
     * @param $newDescription string
     * @param $newImage string file path
     * @param $newQuantity int
     * @param $newPrice int
     * @param $newSalePrice int
     * @return int number of rows updated
     */
    public function updateProduct($oldProductName, $newName, $newDescription, $newImage, $newQuantity, $newPrice, $newSalePrice)
    {
        return $this->db->updateProductInformation($oldProductName, $newName, $newDescription, $newImage, $newQuantity, $newPrice, $newSalePrice);
    }

    /**
     * @param $option string dropdown option selected by the user
     * @return bool true if dropdown option matches DEFAULT_DROPDOWN_OPTION
     */
    public function isDefaultDropdownOption($option)
    {
        return $option == Constants::DEFAULT_DROPDOWN_OPTION;
    }

    /**
     * @return string HTML code to show on 404 error
     */
    public function show404()
    {
        return "
            <div class='jumbotron'>
              <h1>Sorry, the page you are looking for could not be found!</h1> 
              <p>Click <a href='index.php'>here</a> to start shopping...</p> 
            </div>";
    }

    /**
     * @return string HTML code to show on 500 error
     */
    public function show500()
    {
        return "
            <div class='jumbotron'>
              <h1>Oops! Something is wrong with the server!</h1> 
              <p>Click <a href='index.php'>here</a> to start shopping...</p> 
            </div>";
    }
}