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

if (isset($_POST['addToCart'])) {
    $util->addProductToCart($_POST['addToCart']);
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
    . $util->getProductsOnCatalog() .
    "</div>
</div>";
echo Navigation::footer();
?>
