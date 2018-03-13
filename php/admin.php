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
echo Navigation::header("Logout");

if ($_SESSION['isAdmin']) {
    echo "
<div class='row py-5'>
    <div class='col-md-1'></div>
    <div class='col-md-10'>
        <div class='row'>
            <div class='col-md-6'><h3>Add new product!</h3>"
                . $util->showAddProductForm() .
            "</div>
            <div class='col-md-6'><h3>Modify an existing product!</h3>"
//                . $util->showAddProductForm()
            . "</div>" .
        "</div>" .
        "</div>
    <div class='col-md-1'></div>
</div>";
} else {
    header("Location: login.php");
    die();
}

echo Navigation::footer();
?>
