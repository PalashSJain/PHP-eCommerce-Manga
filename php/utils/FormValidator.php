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
        $data['status'] = !empty($input) && !$this->hasProductsWithName($input);
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
        $data['status'] = !empty($input) && $input > 0;
        $data['data'] = $input;
        $data['error'] = "";
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
        return $data;
    }

    public static function parseSalePrice($input)
    {
        $input = FormValidator::sanitize($input);
        $data = array();
        $input = intval($input);
        $data['status'] = !empty($input) && $input > 0;
        $data['data'] = $input;
        $data['error'] = "";
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