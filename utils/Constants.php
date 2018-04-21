<?php
/**
 * Created by PhpStorm.
 * User: Palash
 * Date: 3/11/2018
 * Time: 2:04 PM
 */

class Constants{
    /**
     * MAXIMUM NUMBER OF PRODUCTS THAT CAN BE SHOWN IN CATALOG SECTION
     */
    const PAGE_SIZE = 5;

    /**
     * DROPDOWN OPTION TO SHOW WHEN LANDING ON ADMIN PAGE
     */
    const DEFAULT_DROPDOWN_OPTION = "Please select a product from the dropdown.";

    /**
     * ARRAY OF ACCEPTED IMAGE EXTENSIONS
     */
    const IMAGE_EXTENSIONS = array("png", "jpeg", "jpg");

    /**
     * ARRAY OF ACCEPTED IMAGE MIME TYPES
     */
    const IMAGE_TYPES = array("image/jpeg", "image/png");

    /**
     * ROLE ID for Different types of users. Currently, only ADMIN is supported
     */
    const ROLE_ADMIN = 1;
    const ROLE_USER = 2;
}
