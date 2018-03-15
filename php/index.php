<?php
/**
 * Created by PhpStorm.
 * User: Palash
 * Date: 2/22/2018
 * Time: 10:11 PM
 */

include $_SERVER['DOCUMENT_ROOT'] . "/php/utils/Navigation.php";
include $_SERVER['DOCUMENT_ROOT'] . "/php/utils/LIB_project1.php";

session_start();

$util = new LIB_project1();
$util->onLoad();

if (isset($_POST['addToCart'])) {
    $isUpdated = $util->addProductToCart($_POST['addToCart'], $_COOKIE['SID']);
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
            . $util->getProductsOnSale() .
        "</div>
             
        <div>
            <h3>Other Mangas in Catalog!</h3>"
            . $util->getPagination($page)
            . $util->getProductsOnCatalog($page)
            . $util->getPagination($page)
            . "</div>
    </div>
    <div class='col-md-1'></div>
</div>";
echo Navigation::footer();
?>
