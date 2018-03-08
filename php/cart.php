<?php
/**
 * Created by PhpStorm.
 * User: Palash
 * Date: 2/22/2018
 * Time: 10:11 PM
 */

session_start();

include $_SERVER['DOCUMENT_ROOT'] . "/php/utils/Navigation.php";
include $_SERVER['DOCUMENT_ROOT'] . "/php/utils/LIB_project1.php";

$util = new LIB_project1();
$util->onLoad();

echo Navigation::header("Cart");

if ($util->isCartEmpty()) {
    echo '
<div class="jumbotron h-100">
  <h1>Your cart is empty!</h1> 
  <p>Click <a href="index.php">here</a> to start shopping...</p> 
</div>';
} else {
    echo "
<h1>Current cart contents</h1>
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
        . $util->getProductsInCart()
        . $util->getCartTotal() .
        "</tbody>
</table>";
}
echo Navigation::footer();
?>
