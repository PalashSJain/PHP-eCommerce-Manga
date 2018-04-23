<?php
/**
 * Created by PhpStorm.
 * User: Palash
 * Date: 4/23/2018
 * Time: 1:51 PM
 */

define('ROOT', dirname(__DIR__) . '/PHP-eCommerce-Manga/');
include_once ROOT . "utils/User.php";

session_start();

if (!isset($_SESSION['user'])) {
    echo("logout.php");
} else {
    $user = $_SESSION['user'];
    if (time() - $user->getLastSeen() > 15) {
        echo("logout.php");
    }
    $user->setLastSeen(time());
}