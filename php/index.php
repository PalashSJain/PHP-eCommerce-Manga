<?php
/**
 * Created by PhpStorm.
 * User: Palash
 * Date: 2/22/2018
 * Time: 10:11 PM
 */

include $_SERVER['DOCUMENT_ROOT'] . "/php/utils/Navigation.php";
include $_SERVER['DOCUMENT_ROOT'] . "/php/utils/LIB_project1.php";

$util = new LIB_project1();

echo Navigation::header("Home");
echo "
<div class='py-5'>
    <div class='row'>
        <div class='col-lg-2 col-md-2'><h3>SALE!</h3></div>
        <div class='col-lg-10 col-md-10'>"
            . $util->getProductsOnSale() .
        "</div>
    </div>
    </div>
    <hr>
    
    <div class='row'>
        <div class='col-lg-2 col-md-2'><h3>Other Mangas!</h3></div>
        <div class='col-lg-10 col-md-10'>"
            . $util->getProductsOnCatalog() .
        "</div>
    </div>
</div>";
echo Navigation::footer();
?>
