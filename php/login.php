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

if ($_SESSION['isAdmin']) {
    header("Location: admin.php");
    die();
}

$util = new LIB_project1();
if (isset($_POST['userID']) && isset($_POST['pwd']) && $util->isAdmin($_POST['userID'], $_POST['pwd'])) {
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
                            <form class='form' role='form' autocomplete='off' novalidate='' method='POST' action=''>
                                <div class='form-group'>
                                    <label for='userID'>UserID</label>
                                    <input type='text' class='form-control form-control-lg rounded-0' name='userID' id='userID' required>
                                </div>
                                <div class='form-group'>
                                    <label for='pwd'>Password</label>
                                    <input type='password' class='form-control form-control-lg rounded-0' id='pwd' name='pwd' required>
                                </div>
                                <input type='submit' class='btn btn-success btn-lg float-right' id='btnLogin' />
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>";
echo Navigation::footer();