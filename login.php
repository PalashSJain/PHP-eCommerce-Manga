<?php
/**
 * Created by PhpStorm.
 * User: Palash
 * Date: 3/13/2018
 * Time: 12:52 AM
 */

define('ROOT', dirname(__DIR__) . '/PHP-eCommerce-Manga/');
include ROOT . "utils/Navigation.php";
include ROOT . "utils/LIB_project1.php";
include ROOT . "utils/FormValidator.php";

session_start();

if (isset($_SESSION['user']) && $_SESSION['user']->isAdmin()) {
    header("Location: admin.php");
    die();
} else if (isset($_SESSION['user']) && $_SESSION['user']->isUser()) {
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
        $user = $util->getUser($userID['data'], $password['data']);
        if (gettype($user) == object && get_class($user) == User) {
            session_unset();
            session_destroy();
            session_start();
            $user->setLastSeen(time());
            $_SESSION['user'] = $user;
            if ($user->isAdmin()) {
                header("Location: admin.php");
                die();
            } else if ($user->isUser()) {
                header("Location: index.php");
                die();
            } else {
                $message = "Unauthorized access";
            }
        } else {
            $message = $user;
        }
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