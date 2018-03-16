<?php
/**
 * Created by PhpStorm.
 * User: Palash
 * Date: 3/15/2018
 * Time: 12:47 AM
 */

include_once ROOT. "project1/utils/Constants.php";

class User
{
    private $username, $password, $role;

    public function getRole(){
        return $this->role;
    }

    public function getPassword(){
        return $this->password;
    }
}