<?php
/**
 * Created by PhpStorm.
 * User: Palash
 * Date: 2/22/2018
 * Time: 10:11 PM
 */

session_start();

define('ROOT', dirname(__DIR__) . '/PHP-eCommerce-Manga/');
include ROOT . "utils/Navigation.php";
include ROOT . "utils/LIB_project1.php";

$util = new LIB_project1();
$util->onLoad();

echo Navigation::header("");

echo "<div class='py-5'>";
echo "
<div class='container-fluid'>
    <div class='row'>
        <div class='col-md-1'></div>
        <div class='col-md-10'>"
    . $util->show404() .
    "</div>
        <div class='col-md-1'></div>
    </div>
</div>";
echo "</div>";
echo Navigation::footer();
