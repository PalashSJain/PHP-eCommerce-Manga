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

unset($_SESSION['user']);
header("Location: login.php");
die();