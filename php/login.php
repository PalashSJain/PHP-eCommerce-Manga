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

if (isset($_POST['User_ID']) && isset($_POST['Password']) && $util->isAdmin($_POST['User_ID'], $_POST['Password'])) {
    session_unset();
    session_destroy();
    session_start();
    $_SESSION['isAdmin'] = true;

    header("Location: admin.php");
    die();
}

echo Navigation::header("Admin");
echo "<div class='container py-5'>
    <div class='row'>
        <div class='col-md-12'>
            <div class='row'>
                <div class='col-md-6 mx-auto'>
                    <div class='card rounded-0'>
                        <div class='card-header'>
                            <h3 class='mb-0'>Admin Login</h3>
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
        </div>
    </div>
</div>";
echo Navigation::footer();