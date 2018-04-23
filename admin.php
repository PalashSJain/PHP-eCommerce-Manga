<?php
/**
 * Created by PhpStorm.
 * User: Palash
 * Date: 2/22/2018
 * Time: 10:11 PM
 */

define('ROOT', dirname(__DIR__) . '/PHP-eCommerce-Manga/');
include_once ROOT . "utils/LIB_project1.php";
include_once ROOT . "utils/Navigation.php";
include_once ROOT . "utils/FormValidator.php";

session_start();

$util = new LIB_project1();
$util->onLoad();

if (!isset($_SESSION['user']) || !$_SESSION['user']->isAdmin()) {
    header("Location: logout.php");
    die();
}

$user = $_SESSION['user'];
if (time() - $user->getLastSeen() > 15) {
    header("Location: logout.php");
    die();
}
$user->setLastSeen(time());

$validator = new FormValidator();

// $option is used for setting the dropdown option for updating a new product
$option = "";

// $message is a toast message that shows up on loading the page. $message is populated after an action is performed
$message = "";

/*
 * Initializing form variable objects with null values.
 *
 * Format of the object is
 * $variable = array(
 *  'status' => true or false,
 *  'data' => sanitized input value,
 *  'error' => message to show if the status is false
 * )
 */
$name = $description = $image = $quantity = $price = $salePrice = null;
$xname = $xdescription = $ximage = $xquantity = $xprice = $xsalePrice = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Adding a new product
    if (isset($_POST['submit']) && $_POST['submit'] == 'Submit') {
        // Parse all form inputs
        $name = $validator->parseName($_POST['Name']);
        $description = $validator->parseDescription($_POST['Description']);
        $image = $validator->isImage($_FILES['Image']);
        $quantity = $validator->parseQuantity($_POST['Quantity']);
        $price = $validator->parsePrice($_POST['Price']);
        $salePrice = $validator->parseSalePrice($_POST['Sale_Price']);

        $hasProductWithSameName = $validator->hasProductsWithName($name['data']);

        if ($name['status'] && !$hasProductWithSameName && $description['status'] && $quantity['status']
            && $price['status'] && $salePrice['status'] && $image['status']) {

            // Add product to database
            $message = $util->addProduct(
                $name['data'],
                $description['data'],
                $image['data'],
                $quantity['data'],
                $price['data'],
                $salePrice['data']);
        } else {
            // Failed for reasons other than database exception
            if ($hasProductWithSameName) {
                $message = "Failed: Product with the same name already exists.";
            } else if (!$salePrice['status']) {
                if ($_POST['Sale_Price'] > 0) {
                    $message = "Failed: Cannot put more products on sale!";
                } else {
                    $message = "Failed: Cannot put fewer products on sale!";
                }
            }
        }

    // Updating an old product
    } else if (isset($_POST['submit']) && $_POST['submit'] == 'Update') {
        $xname = $validator->parseName($_POST['Name']);
        $xdescription = $validator->parseDescription($_POST['Description']);
        $ximage = $validator->isImage($_FILES['Image']);

        // If no file has been uploaded than revert to original file.
        if ($ximage['data'] == "images/123default456.png" && empty($_FILES['Image']['name'])) {
            $ximage['data'] = $_SESSION['oldproduct']->getImageName();
        }

        $xquantity = $validator->parseQuantity($_POST['Quantity']);
        $xprice = $validator->parsePrice($_POST['Price']);
        $xsalePrice = $validator->parseSalePrice($_POST['Sale_Price'], $_SESSION['oldproduct']->getSalePrice() > 0);

        // If there is no change in information, then do not attempt to upload
        $oldproduct = $_SESSION['oldproduct'];
        if ($oldproduct->getProductName() == $xname['data'] &&
            $oldproduct->getDescription() == $xdescription['data'] &&
            $oldproduct->getImageName() == $ximage['data'] &&
            $oldproduct->getQuantity() == $xquantity['data'] &&
            $oldproduct->getPrice() == $xprice['data'] &&
            $oldproduct->getSalePrice() == $xsalePrice['data']) {

            $option = $xname['data'];
            $message = "No change detected.";

        } else if ($xname['status'] && $xdescription['status'] && $xquantity['status'] &&
            $ximage['status'] && $xprice['status'] && $xsalePrice['status']) {

            // Attempt to update product
            $rowsUpdated = $util->updateProduct($_SESSION['oldproduct']->getProductName(),
                $xname['data'],
                $xdescription['data'],
                $ximage['data'],
                $xquantity['data'],
                $xprice['data'],
                $xsalePrice['data']);

            // If there is more than one row being updated, then update $option, $message and Product object in $_SESSION
            if ($rowsUpdated > 0) {
                $option = $xname['data'];
                $message = "Updated '$option'!";
                $_SESSION['oldproduct'] = $util->getProductFromName($option);

            // Revert option to original product name
            } else {
                $option = $_SESSION['oldproduct']->getProductName();
                $message = "Failed to update old product.";
            }

        } else {
            $option = $_SESSION['oldproduct']->getProductName();
            $message = "Please verify the information you have entered.";
        }

    // Show product information chosen from the dropdown
    } else if (isset($_POST['dropdownOptions'])) {
        $option = trim($_POST['dropdownOptions']);
        if (!$util->isDefaultDropdownOption($option)) {
            $_SESSION['oldproduct'] = $util->getProductFromName($option);
            $message = "Displaying information for product '$option'!";
        }
    }
} else {
    $_SESSION['oldproduct'] = null;
}

if ($_SESSION['user']->isAdmin()) {
    echo Navigation::header("Logout");

    echo "
<div class='row py-5 no-margin-on-sides'>
   <div class='col-md-1'></div>
   <div class='col-md-10'>
      <div class='row'>
         <div class='col-md-6'>
            <h3>Add new product!</h3>
            <div class='card'>
               <form method='post' action='' enctype='multipart/form-data'>"
        . $util->showInputFieldVertically("Name", "text", $name)
        . $util->showTextFieldVertically("Description", $description)
        . "<div class='col-sm-12'><small>Supports a maximum of 1000 characters.</small></div>"
        . $util->showFileFieldAsRow("Image", "file", $image)
        . "<div class='col-sm-12'><small>Default image will be used if no image is provided.</small></div>"
        . $util->showInputFieldAsRow("Quantity", "number", $quantity)
        . $util->showInputFieldAsRow("Price", "number", $price, "$")
        . $util->showInputFieldAsRow("Sale Price", "number", $salePrice, "$")
        . "<button type='reset' class='btn btn-warning col-form-label'>Reset</button>
                  <button type='submit' class='btn btn-success col-form-label' name='submit' value='Submit'>Submit</button>
               </form>
            </div>
         </div>
         <div class='col-md-6'>
            <h3>Modify an existing product!</h3>
            <div class='card'>
                <form action='' method='post'>
                    <select class='form-control' name='dropdownOptions' onchange='this.form.submit()'>
                      " . $util->getProductOptions($option) . "
                    </select>
                </form>    
            " . (
        (isset($_SESSION['oldproduct']) && !empty($_SESSION['oldproduct'])) ?
            "<form method='post' action='' enctype='multipart/form-data'>"
            . $util->showInputFieldVertically("Name", "text", $xname, $_SESSION['oldproduct']->getProductName())
            . $util->showTextFieldVertically("Description", $xdescription, $_SESSION['oldproduct']->getDescription())
            . "<div class='col-sm-12'><small>Supports a maximum of 1000 characters.</small></div>"
            . $util->showFileFieldAsRow("Image", "file", $ximage)
            . "<div class='col-sm-12'><small>Original image will be used if no image is provided.</small></div>"
            . $util->showInputFieldAsRow("Quantity", "number", $xquantity, '', $_SESSION['oldproduct']->getQuantity())
            . $util->showInputFieldAsRow("Price", "number", $xprice, "$", $_SESSION['oldproduct']->getPrice())
            . $util->showInputFieldAsRow("Sale Price", "number", $xsalePrice, "$", $_SESSION['oldproduct']->getSalePrice())
            . "<button type='reset' class='btn btn-warning col-form-label'>Reset</button>
                    <button type='submit' class='btn btn-success col-form-label' name='submit' value='Update'>Update</button>
                </form>"
            : "")
        . "</div>
        </div>
      </div>
   </div>
   <div class='col-md-1'></div>
</div>";

} else {
    header("Location: login.php");
    die();
}

// Show a toast if $message has been populated
if (isset($message) && !empty($message)) {
    echo "<div id='snackbar'>$message</div>";
    echo "<script type='text/javascript'> toast(); </script>";
}

echo Navigation::footer();
