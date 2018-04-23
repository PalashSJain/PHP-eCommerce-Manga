<?php
/**
 * Created by PhpStorm.
 * User: Palash
 * Date: 2/22/2018
 * Time: 10:11 PM
 */

define('ROOT', dirname(__DIR__) . '/PHP-eCommerce-Manga/');
include_once ROOT . "utils/Navigation.php";
include_once ROOT . "utils/LIB_project1.php";

session_start();

$util = new LIB_project1();
$util->onLoad();

// Clear cart if requested by the user
if (isset($_POST['clearCart'])) {
    $util->clearCart();
}

if (isset($_SESSION['isUser']) || empty($_SESSION['isUser']) || $_SESSION['isUser'] !== true) {
    header("Location: logout.php");
    die();
} else if (isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== false) {
    header("Location: logout.php");
    die();
}

echo Navigation::header("Cart");

echo "<div class='py-5'>";
echo "
<div class='container-fluid'>
    <div class='row'>
        <div class='col-md-1'></div>
        <div class='col-md-10'>";
// Show empty cart if there is no item in the cart
if ($util->isCartEmpty(session_id())) {
    echo $util->showEmptyCart();
} else {
    // Show cart table and button to clear cart if there is at least one item in the cart
    echo "
        <h1>Current cart contents</h1>"
        . $util->showCartTable(session_id())
        . $util->showBtnToClearCart();
}
echo    "</div>
        <div class='col-md-1'></div>
    </div>
</div>";
echo "</div>";
echo Navigation::footer();
