<?php
/**
 * Created by PhpStorm.
 * User: Palash
 * Date: 3/15/2018
 * Time: 12:47 AM
 */

include_once $_SERVER['DOCUMENT_ROOT'] . "/php/utils/Constants.php";

class User
{
    private $username, $password, $role;

    public function isAdmin(){
        return $this->role == Constants::ROLE_ADMIN;
    }

    public function getPassword(){
        return $this->password;
    }
}