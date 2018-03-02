<?php
/**
 * Created by PhpStorm.
 * User: Palash
 * Date: 3/2/2018
 * Time: 1:33 PM
 */

class Login
{

    public function showLoginPage()
    {
        return
"<div class='container py-5'>
    <div class='row'>
        <div class='col-md-12'>
            <div class='row'>
                <div class='col-md-6 mx-auto'>
                    <div class='card rounded-0'>
                        <div class='card-header'>
                            <h3 class='mb-0'>Login</h3>
                        </div>
                        <div class='card-body'>
                            <form class='form' role='form' autocomplete='off' id='adminLogin' novalidate='' method='POST'>
                                <div class='form-group'>
                                    <label for='userID'>UserID</label>
                                    <input type='text' class='form-control form-control-lg rounded-0' name='userID' id='userID' required>
                                </div>
                                <div class='form-group'>
                                    <label for='pwd'>Password</label>
                                    <input type='password' class='form-control form-control-lg rounded-0' id='pwd' required>
                                </div>
                                <button type='submit' class='btn btn-success btn-lg float-right' id='btnLogin'>Login</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>";
    }
}