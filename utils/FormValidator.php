<?php
/**
 * Created by PhpStorm.
 * User: Palash
 * Date: 3/12/2018
 * Time: 5:28 PM
 */

include_once ROOT . "project1/db/DBHelper.php";
include_once ROOT . "project1/utils/Constants.php";

class FormValidator extends DBHelper
{

    /**
     * @param $input string username while creating user
     * @return array of 'status' (true or false), 'data' (sanitized $input) and 'error' (message if status is false)
     */
    public function parseUsername($input)
    {
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

    /**
     * @param $input string password to be valid string
     * @return array of 'status' (true or false), 'data' (sanitized $input) and 'error' (message if status is false)
     */
    public function parsePassword($input)
    {
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

    /**
     * @param $input string product name set in add or update form
     * @return array of 'status' (true or false), 'data' (sanitized $input) and 'error' (message if status is false)
     */
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

    /**
     * @param $input string description set in add or update form
     * @return array of 'status' (true or false), 'data' (sanitized $input) and 'error' (message if status is false).
     * Data trimmed to length of 1000
     */
    public function parseDescription($input)
    {
        $input = FormValidator::sanitize($input);
        $data = array();
        $data['status'] = true;
        $data['data'] = mb_strimwidth($input, 0, 1003, "...");
        $data['error'] = "";
        return $data;
    }

    /**
     * @param $input string file path set in add or update form
     * @return array of 'status' (true or false), 'data' (sanitized $input) and 'error' (message if status is false).
     * Status is true if (1) image has accepted extension, (2) acceptable size, (3) acceptable mime typeand (4) has been moved to project folder
     */
    public function isImage($input)
    {
        $data = array();

        if (!isset($input['name']) || empty($input['name'])) {
            $data['status'] = true;
            $data['data'] = "images/123default456.png";
            $data['error'] = "";
        } else {
            $file = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', basename($input['name']));
            $file = mb_ereg_replace("([\.]{2,})", '', $file);
            $target_dir = ROOT . "project1/images/";
            $target_file = $target_dir . $file;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $imageSize = getimagesize($input['tmp_name']);

            $data['status'] = in_array($imageFileType, Constants::IMAGE_EXTENSIONS) &&
                $input['size'] < 5000000 &&
                in_array($imageSize['mime'], Constants::IMAGE_TYPES) &&
                move_uploaded_file($input["tmp_name"], $target_file);
            $data['data'] = "images/" . basename($input['name']);
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

    /**
     * @param $input int quantity set in add or update form
     * @return array of 'status' (true or false), 'data' (sanitized $input) and 'error' (message if status is false)
     */
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

    /**
     * @param $input int price set in add or update form
     * @return array of 'status' (true or false), 'data' (sanitized $input) and 'error' (message if status is false)
     */
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

    /**
     * @param $input int sale price set in add or update form. Set to 0 if empty.
     * @param bool $isAlreadyOnSale false by default
     * @return array of 'status' (true or false), 'data' (sanitized $input) and 'error' (message if status is false)
     */
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

    /**
     * @param $var string sent by user
     * @return string sanitized string
     */
    private static function sanitize($var)
    {
        $var = trim($var);
        $var = stripslashes($var);
        $var = htmlentities($var);
        $var = strip_tags($var);
        return $var;
    }

}