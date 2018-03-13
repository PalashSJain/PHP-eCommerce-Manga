<?php
/**
 * Created by PhpStorm.
 * User: Palash
 * Date: 3/12/2018
 * Time: 1:48 PM
 */

include_once $_SERVER['DOCUMENT_ROOT'] . "/php/db/DB.MangaStore.class.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/php/utils/DBValidator.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/php/utils/FormValidator.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['name']) &&
        isset($_POST['description']) &&
//    isset($_POST['file']) &&
        isset($_POST['quantity']) &&
        isset($_POST['price'])) {

        if (FormValidator::isName($_POST['name']) &&
            FormValidator::isDescription($_POST['description']) &&
//        FormValidator::isImageFile($_POST['file']) &&
            FormValidator::validateQuantity($_POST['quantity']) &&
            FormValidator::validatePrice($_POST['price'])) {

            $db = new dbMangaStore();

            if (isset($_POST['salePrice']) &&
                FormValidator::validateSalePrice($_POST['salePrice'])) {

                if (intval($_POST['salePrice']) != 0) {
                    if (DBValidator::canSaleMoreProducts()) {
                        $db->addProduct($_POST['name'],
                            $_POST['description'],
//                    $_POST['file'],
                            $_POST['quantity'],
                            $_POST['price'],
                            $_POST['salePrice']);
                    } else {
                        // message that we cannot sale more products
                    }
                    header("Location: /PHP-eCommerce-Manga/php/admin.php");
                    die();
                }
            }

            $db->addProduct($_POST['name'],
                $_POST['description'],
//                $_POST['file'],
                $_POST['quantity'],
                $_POST['price'],
                0);
            header("Location: /PHP-eCommerce-Manga/php/admin.php");
            die();
        }
    }
}

