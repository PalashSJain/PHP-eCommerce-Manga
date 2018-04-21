<?php
/**
 * Created by PhpStorm.
 * User: Palash
 * Date: 3/13/2018
 * Time: 12:52 AM
 */

session_start();

define('ROOT', dirname(__DIR__) . '/PHP-eCommerce-Manga/');
include ROOT . "utils/Navigation.php";
include ROOT . "utils/LIB_project1.php";
include ROOT . "utils/FormValidator.php";

if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin']) {
    header("Location: admin.php");
    die();
} else if (isset($_SESSION['isUser']) && $_SESSION['isUser']) {
    header("Location: index.php");
    die();
}


$util = new LIB_project1();
$validator = new FormValidator();
$util->onLoad();

// If attempting to login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $userID = $validator->parseUsername($_POST['User_ID']);
    $password = $validator->parsePassword($_POST['Password']);

    if ($userID['status'] && $password['status']) {
        $isAdmin = $util->isAdmin($userID['data'], $password['data']);
        if ($isAdmin === true) {
            // restart session if admin logged in
            session_unset();
            session_destroy();
            session_start();
            $_SESSION['isAdmin'] = $isAdmin;
            $_SESSION['isUser'] = false;

            // Go to admin.php if successfully logged in
            header("Location: admin.php");
            die();
        }
        $isUser = $util->isUser($userID['data'], $password['data']);
        if ($isUser === true) {
            session_unset();
            session_destroy();
            session_start();
            $_SESSION['isUser'] = $isUser;
            $_SESSION['isAdmin'] = false;

            // Go to admin.php if successfully logged in
            header("Location: index.php");
            die();
        }
        $message = $isUser;
    } else {
        if (!$userID['status']) {
            $message = $userID['error'];
        } else if (!$password['status']) {
            $message = $password['error'];
        }
    }
}

echo Navigation::header("Login");
echo "<div class='container py-5'>
    <div class='row'>
        <div class='col-md-6 mx-auto'>
            <div class='card rounded-0'>
                <div class='card-header'>
                    <h3 class='mb-0'>Admin/User Login</h3>
                </div>
                <div class='card-body'>
                    <form class='form' role='form' autocomplete='off' id='adminLogin' novalidate='' method='POST' action=''>"
                    . $util->showInputFieldVertically('User ID', 'text')
                    . $util->showInputFieldVertically('Password', 'password')
                    . "<button type='submit' class='btn btn-success btn-lg float-right' id='btnLogin'>Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>";

if (isset($message) && !empty($message)) {
    echo "<div id='snackbar'>$message</div>";
    echo "<script type='text/javascript'> toast(); </script>";
}

echo Navigation::footer();