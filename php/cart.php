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

if (isset($_POST['clearCart'])) {
    $util->clearCart();
    header("Location: cart.php");
    die();
}

echo Navigation::header("Cart");

if ($util->isCartEmpty()) {
    echo $util->showEmptyCart();
} else {
    echo "
<h1>Current cart contents</h1>"
    . $util->getCartTable()
    . $util->getBtnToClearCart();
}
echo Navigation::footer();
?>
