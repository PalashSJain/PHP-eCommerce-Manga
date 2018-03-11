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
    $util->addProductToCart($_POST['addToCart'], $_COOKIE['SID']);
}

$page = 0;
if (isset($_GET['page'])) {
    $page = intval($_GET['page']);
}

echo Navigation::header("Home");
echo "
<div class='py-5'>
    <div>
        <h3>SALE!</h3>"
    . $util->getProductsOnSale() .
    "</div>
    </div>
     
    <div>
        <h3>Other Mangas in Catalog!</h3>"
    . $util->getPagination($page)
    . $util->getProductsOnCatalog($page)
    . $util->getPagination($page)
    . "</div>
</div>";
echo Navigation::footer();
?>
