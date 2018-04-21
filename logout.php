<?php
/**
 * Created by PhpStorm.
 * User: Palash
 * Date: 3/13/2018
 * Time: 1:13 AM
 */

session_unset();
session_destroy();
session_start();

$_SESSION['isAdmin'] = false;
$_SESSION['isUser'] = false;
header("Location: login.php");
die();