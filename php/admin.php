<?php
/**
 * Created by PhpStorm.
 * User: Palash
 * Date: 2/22/2018
 * Time: 10:11 PM
 */


include $_SERVER['DOCUMENT_ROOT'] . "/php/utils/Navigation.php";
include $_SERVER['DOCUMENT_ROOT'] . "/php/utils/LIB_project1.php";
include $_SERVER['DOCUMENT_ROOT'] . "/php/utils/FormValidator.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/php/utils/Constants.php";

session_start();

$util = new LIB_project1();
$util->onLoad();

$validator = new FormValidator();
$option = "";
$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    if (isset($_POST['submit']) && $_POST['submit'] == 'Submit') {
        $name = $validator->parseName($_POST['Name']);
        $description = FormValidator::parseDescription($_POST['Description']);
        $quantity = FormValidator::parseQuantity($_POST['Quantity']);
        $price = FormValidator::parsePrice($_POST['Price']);
        $salePrice = $validator->parseSalePrice($_POST['Sale_Price']);

        if ($name['status'] && $description['status'] && $quantity['status'] && $price['status'] && $salePrice['status']) {
            if ($salePrice['status']) {
                if ($salePrice['data'] !== 0) {
                    $salePrice['error'] = "Cannot add more Products on Sale";
                }
            } else {
                $salePrice['data'] = 0;
            }

            $message = $util->addProduct($name['data'],
                $description['data'],
//                    $_POST['file'],
                $quantity['data'],
                $price['data'],
                $salePrice['data']);

        } else {
            $message = "Invalid input data";
        }
    } else if (isset($_POST['submit']) && $_POST['submit'] == 'Update') {
        $message = 'Updating!';
    } else if (isset($_POST['dropdownOptions'])) {
        $option = trim($_POST['dropdownOptions']);
        if ($option != Constants::DEFAULT_DROPDOWN_OPTION) {
            $product = $util->getProductFromName($option);
            $message = 'Let me show you something new!';
        } else {
            $product = null;
        }
    }
}


if ($_SESSION['isAdmin']) {
    echo Navigation::header("Logout");

    echo "
<div class='row py-5'>
   <div class='col-md-1'></div>
   <div class='col-md-10'>
      <div class='row'>
         <div class='col-md-6'>
            <h3>Add new product!</h3>
            <div class='card'>
               <form method='post' action=''>"
        . $util->showInputFieldVertically("Name", "text", (isset($name) ? $util->getErrorClass($name) : ""), (isset($name) ? $util->getErrorMessage($name) : ""))
        . $util->showTextFieldVertically("Description", (isset($description) ? $util->getErrorClass($description) : ""), (isset($description) ? $util->getErrorMessage($description) : ""))
        . $util->showInputFieldAsRow("Image", "file", (isset($quantity) ? $util->getErrorClass($quantity) : ""), (isset($quantity) ? $util->getErrorMessage($quantity) : ""))
        . $util->showInputFieldAsRow("Quantity", "number", (isset($quantity) ? $util->getErrorClass($quantity) : ""), (isset($quantity) ? $util->getErrorMessage($quantity) : ""))
        . $util->showInputFieldAsRow("Price", "number", (isset($price) ? $util->getErrorClass($price) : ""), (isset($price) ? $util->getErrorMessage($price) : ""), "$")
        . $util->showInputFieldAsRow("Sale Price", "number", (isset($salePrice) ? $util->getErrorClass($salePrice) : ""), (isset($salePrice) ? $util->getErrorMessage($salePrice) : ""), "$")
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
        (isset($product) && !empty($product)) ?
            "<form method='post' action=''>"
            . $util->showInputFieldVertically("Name", "text", (isset($name) ? $util->getErrorClass($name) : ""), (isset($name) ? $util->getErrorMessage($name) : ""), $product->getProductName())
            . $util->showTextFieldVertically("Description", (isset($description) ? $util->getErrorClass($description) : ""), (isset($description) ? $util->getErrorMessage($description) : ""), $product->getDescription())
            . $util->showInputFieldAsRow("Image", "file", (isset($quantity) ? $util->getErrorClass($quantity) : ""), (isset($quantity) ? $util->getErrorMessage($quantity) : ""), '', $product->getImageName())
            . $util->showInputFieldAsRow("Quantity", "number", (isset($quantity) ? $util->getErrorClass($quantity) : ""), (isset($quantity) ? $util->getErrorMessage($quantity) : ""), '', $product->getQuantity())
            . $util->showInputFieldAsRow("Price", "number", (isset($price) ? $util->getErrorClass($price) : ""), (isset($price) ? $util->getErrorMessage($price) : ""), "$", $product->getPrice())
            . $util->showInputFieldAsRow("Sale Price", "number", (isset($salePrice) ? $util->getErrorClass($salePrice) : ""), (isset($salePrice) ? $util->getErrorMessage($salePrice) : ""), "$", $product->getSalePrice())
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
    echo Navigation::header("Login");
    header("Location: login.php");
    die();
}

if (isset($message) && !empty($message)) {
    echo "<div id='snackbar'>$message</div>";
    echo "<script type='text/javascript'> toast(); </script>";
}

echo Navigation::footer();
?>
