<?php
/**
 * Created by PhpStorm.
 * User: Palash
 * Date: 3/12/2018
 * Time: 5:28 PM
 */

include_once $_SERVER['DOCUMENT_ROOT'] . "/php/db/DBHelper.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/php/utils/Constants.php";

class FormValidator extends DBHelper
{

    public function parseUsername($input){
        $input = FormValidator::sanitize($input);
        $data = array();
        $data['status'] = !empty($input);
        $data['data'] = $input;
        $data['error'] = "";
        if (!$data['status']) {
            if (empty($input)) {
                $data['error'] = "User ID is blank.";
            } else {
                $data['error'] = "Invalid User ID.";
            }
        }
        return $data;
    }

    public function parsePassword($input){
        $input = FormValidator::sanitize($input);
        $data = array();
        $data['status'] = !empty($input);
        $data['data'] = $input;
        $data['error'] = "";
        if (!$data['status']) {
            if (empty($input)) {
                $data['error'] = "Password is blank.";
            } else {
                $data['error'] = "Invalid Password.";
            }
        }
        return $data;
    }

    public function parseName($input)
    {
        $input = FormValidator::sanitize($input);
        $data = array();
        $data['status'] = !empty($input);
        $data['data'] = $input;
        $data['error'] = "";
        if (!$data['status']) {
            if (empty($input)) {
                $data['error'] = "Product name is blank.";
            } else {
                $data['error'] = "Product name '$input' already exists.";
            }
        }
        return $data;
    }

    public function parseDescription($input)
    {
        $input = FormValidator::sanitize($input);
        $data = array();
        $data['status'] = true;
        $data['data'] = $input;
        $data['error'] = "";
        return $data;
    }

    public function isImage($input)
    {
        $data = array();

        if (!isset($input['name']) || empty($input['name'])) {
            $data['status'] = true;
            $data['data'] = "http://" . $_SERVER['HTTP_HOST'] . "/PHP-eCommerce-Manga/images/default.png";
            $data['error'] = "";
        } else {
            $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/images/";
            $target_file = $target_dir . basename($input['name']);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $imageSize = getimagesize($input['tmp_name']);

            $data['status'] = in_array($imageFileType, Constants::IMAGE_EXTENSIONS) &&
                $input['size'] < 5000000 &&
                in_array($imageSize['mime'], Constants::IMAGE_TYPES) &&
                move_uploaded_file($input["tmp_name"], $target_file);
            $data['data'] = "http://" . $_SERVER['HTTP_HOST'] . "/PHP-eCommerce-Manga/images/" . basename($input['name']);
            $data['error'] = "";
            if (!$data['status']) {
                if (!in_array($imageFileType, Constants::IMAGE_EXTENSIONS)) {
                    $data['error'] = "File uploaded does not have accepted extension.";
                } else if ($input['size'] > 5000000) {
                    $data['error'] = "File size is larger than 5mb.";
                } else if (!in_array($imageSize['mime'], Constants::IMAGE_TYPES)) {
                    $data['error'] = "File uploaded does not have accepted format.";
                } else {
                    $data['error'] = "Failed to upload file.";
                }
            }
        }

        return $data;
    }

    public function parseQuantity($input)
    {
        $input = FormValidator::sanitize($input);
        $data = array();
        $input = intval($input);
        $data['status'] = !empty($input) && $input >= 0;
        $data['data'] = $input;
        $data['error'] = "";
        if (!$data['status']) {
            if (empty($input)) {
                $data['error'] = "Please specify a minimum quantity.";
            } else {
                $data['error'] = "Quantity cannot be less than 0.";
            }
        }
        return $data;
    }

    public function parsePrice($input)
    {
        $input = FormValidator::sanitize($input);
        $data = array();
        $input = intval($input);
        $data['status'] = !empty($input) && $input > 0;
        $data['data'] = $input;
        $data['error'] = "";
        if (!$data['status']) {
            if (empty($input)) {
                $data['error'] = "Please specify a minimum price.";
            } else {
                $data['error'] = "Price should be more than 0.";
            }
        }
        return $data;
    }

    public function parseSalePrice($input, $isAlreadyOnSale = false)
    {
        $input = FormValidator::sanitize($input);
        $input = intval($input);
        if (empty($input)) $input = 0;
        $data = array();
        $canSaleFewerProducts = $this->canSaleFewerProducts();
        $canSaleMoreProducts = $this->canSaleMoreProducts();
        $data['status'] = ($input == 0 && ($canSaleFewerProducts || !$isAlreadyOnSale)) || ($input > 0 && ($canSaleMoreProducts || $isAlreadyOnSale));
        $data['data'] = $input;
        $data['error'] = "";
        if (!$data['status']) {
            if ($input == 0 && !$canSaleFewerProducts) {
                $data['error'] = "Number of products on sale would become less than 3.";
            } else if ($input > 0 && !$canSaleMoreProducts) {
                $data['error'] = "Number of products on sale would become more than 5.";
            } else {
                $data['error'] = "Sale Price cannot be negative.";
            }
        }
        return $data;
    }

    private static function sanitize($var)
    {
        $var = trim($var);
        $var = stripslashes($var);
        $var = htmlentities($var);
        $var = strip_tags($var);
        return $var;
    }

}