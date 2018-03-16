<?php
/**
 * Created by PhpStorm.
 * User: Palash
 * Date: 2/22/2018
 * Time: 10:11 PM
 */

session_start();

define('ROOT', dirname(__DIR__) . '/');
include_once "utils/Navigation.php";
include_once "utils/LIB_project1.php";

$util = new LIB_project1();
$util->onLoad();

if (isset($_POST['addToCart'])) {
    $isUpdated = $util->addProductToCart($_POST['addToCart'], session_id());
    if ($isUpdated) {
        $util->reduceQuantity($_POST['addToCart']);
    }
}

$page = 1;
if (isset($_GET['page'])) {
    $page = intval($_GET['page']);
}

echo Navigation::header("Home");
echo "
<div class='container-fluid'>
<div class='row'>
    <div class='col-md-1'></div>
    <div class='col-md-10'>
        <div class='py-5'>
                <h3>SALE!</h3>"
    // Get products currently on sale
    . $util->getProductsOnSale() .
    "</div>
             <hr>
        <div>
            <h3>Other Mangas in Catalog!</h3>"
    // Show pagination and products in catalog
    . $util->showPagination($page)
    . $util->showProductsInCatalog($page)
    . $util->showPagination($page)
    . "</div>
    </div>
    <div class='col-md-1'></div>
</div>";
echo Navigation::footer();
