<?php
/**
 * Created by PhpStorm.
 * User: Palash
 * Date: 3/15/2018
 * Time: 12:47 AM
 */

class User
{
    private $UserName, $Password, $Role;

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->Role;
    }


}