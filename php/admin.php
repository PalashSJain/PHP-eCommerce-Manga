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

session_start();

$util = new LIB_project1();
$util->onLoad();

$validator = new FormValidator();

$message = "";

$name = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name = $validator->parseName($_POST['name']);
    $description = FormValidator::parseDescription($_POST['description']);
    $quantity = FormValidator::parseQuantity($_POST['quantity']);
    $price = FormValidator::parsePrice($_POST['price']);
    $salePrice = $validator->parseSalePrice($_POST['salePrice']);

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
               <form method='post' action=''>
                  <div class='form-group'>
                     <label for='name'>Name</label>
                     <input type='text' class='form-control " . (isset($name) ? $util->getErrorClass($name) : "") . "'
                      id='name' name='name' placeholder='One Piece Vol. ?, Naruto Vol. ?' required>"
                     . (isset($name) ? $util->getErrorMessage($name) : "") .
                  "</div>
                  <div class='form-group'>
                     <label for='description'>Description</label>
                     <textarea class='form-control' id='description' name='description' rows='3' required></textarea>
                     ". (isset($description) ? $util->getErrorMessage($description) : "") ."
                  </div>
                  <div class='form-group row'>
                     <label for='file' class='col-sm-4 col-form-label'>Manga Cover</label>
                     <div class='col-sm-8'>
                        <input type='file' class='form-control-file' id='file' name='file'>
                     </div>
                  </div>
                  <div class='form-group row'>
                     <label for='quantity' class='col-sm-4 col-form-label'>Quantity</label>
                     <div class='col-sm-8 input-group mb-2'>
                        <input type='number' class='form-control " . (isset($quantity) ? $util->getErrorClass($quantity) : "") . "' 
                        id='quantity' name='quantity' placeholder='Quantity' required>
                        ". (isset($quantity) ? $util->getErrorMessage($quantity) : "") ."
                     </div>
                  </div>
                  <div class='form-group row'>
                     <label for='price' class='col-sm-4 col-form-label'>Price</label>
                     <div class='col-sm-8 input-group mb-2'>
                        <div class='input-group-prepend'>
                           <div class='input-group-text'>$</div>
                        </div>
                        <input type='number' class='form-control " . (isset($price) ? $util->getErrorClass($price) : "") . "' 
                        id='price' name='price' placeholder='Price' required>
                        ". (isset($price) ? $util->getErrorMessage($price) : "") ."
                     </div>
                  </div>
                  <div class='form-group row'>
                     <label for='salePrice' class='col-sm-4 col-form-label'>Sale Price</label>
                     <div class='col-sm-8 input-group mb-2'>
                        <div class='input-group-prepend'>
                           <div class='input-group-text'>$</div>
                        </div>
                        <input type='number' class='form-control " . (isset($salePrice) ? $util->getErrorClass($salePrice) : "") . "' 
                        id='salePrice' name='salePrice' placeholder='Sale Price'>
                        ". (isset($salePrice) ? $util->getErrorMessage($salePrice) : "") ."
                     </div>
                  </div>
                  <button type='reset' class='btn btn-warning'>Reset</button>
                  <button type='submit' class='btn btn-success'>Submit</button>
               </form>
            </div>
         </div>
         <div class='col-md-6'>
            <h3>Modify an existing product!</h3>"
        . $util->showModifyProductForm() .
        "</div>
      </div>
   </div>
   <div class='col-md-1'></div>
</div>";

    if (isset($message) && !empty($message)) {
        echo "<div id='snackbar'>$message</div>";
        echo "<script type='text/javascript'> toast(); </script>";
    }

} else {
    echo Navigation::header("Login");
    header("Location: login.php");
    die();
}

echo Navigation::footer();
?>
