<?php
/**
 * Created by PhpStorm.
 * User: Palash
 * Date: 3/12/2018
 * Time: 5:28 PM
 */

include $_SERVER['DOCUMENT_ROOT'] . "/php/utils/DBValidator.php";

class FormValidator extends DBValidator
{

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

    public static function parseDescription($input)
    {
        $input = FormValidator::sanitize($input);
        $data = array();
        $data['status'] = !empty($input);
        $data['data'] = $input;
        $data['error'] = "";
        return $data;
    }

    public static function isImageFile($input)
    {
        $data = array();
        $data['status'] = true;
        $data['data'] = $input;
        $data['error'] = "";
        return $data;
    }

    public static function parseQuantity($input)
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

    public static function parsePrice($input)
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

    public function parseSalePrice($input)
    {
        $input = FormValidator::sanitize($input);
        $input = intval($input);
        if (empty($input)) $input = 0;
        $data = array();
        $canSaleFewerProducts = $this->canSaleFewerProducts();
        $canSaleMoreProducts = $this->canSaleMoreProducts();
        $data['status'] = (($input == 0 && $canSaleFewerProducts) || ($input > 0 && $canSaleMoreProducts));
        $data['data'] = $input;
        $data['error'] = "";
        if (!$data['status']) {
            if ($input == 0 && !$canSaleFewerProducts) {
                $data['error'] = "Number of products on sale is less than 3.";
            } else if ($input > 0 && !$canSaleMoreProducts) {
                $data['error'] = "Number of products on sale is more than 5.";
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