<?php
/**
 * Created by PhpStorm.
 * User: Palash
 * Date: 3/13/2018
 * Time: 12:52 AM
 */

session_start();

include $_SERVER['DOCUMENT_ROOT'] . "/php/utils/Navigation.php";
include $_SERVER['DOCUMENT_ROOT'] . "/php/utils/LIB_project1.php";

if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin']) {
    header("Location: admin.php");
    die();
}

$util = new LIB_project1();
$util->onLoad();

if (isset($_POST['userID']) && isset($_POST['pwd']) && $util->isAdmin($_POST['userID'], $_POST['pwd'])) {
    session_unset();
    session_destroy();
    session_start();
    $_SESSION['isAdmin'] = true;

    header("Location: admin.php");
    die();
}

echo Navigation::header("Admin");
echo $util->getLoginForm();
echo Navigation::footer();