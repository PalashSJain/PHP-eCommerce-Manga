<?php
/**
 * Created by PhpStorm.
 * User: Palash
 * Date: 3/12/2018
 * Time: 1:48 PM
 */

class AddProduct
{
    public function getHTMLForm()
    {
        return "
<div class='card'>
<form method='post' action='utils/AddProduct.php'>
  
  <div class='form-group'>
    <label for='name'>Name</label>
    <input type='text' class='form-control' id='name' name='name' placeholder='One Piece Vol. ?, Naruto Vol. ?'>
  </div>
  
  <div class='form-group'>
    <label for='description'>Description</label>
    <textarea class='form-control' id='description' name='description' rows='3'></textarea>
  </div>
  
   <div class='form-group row'>
    <label for='file' class='col-sm-4 col-form-label'>Manga Cover</label>
    <div class='col-sm-8'>
        <input type='file' class='form-control-file' id='file' name='file'>
    </div>
  </div>
  
  <div class='form-group row'>
    <label for='quantity' class='col-sm-4 col-form-label'>Quantity</label>
    <div class='col-sm-8 input-group mb-2'>
      <input type='number' class='form-control' id='quantity' name='quantity' placeholder='Quantity'>
    </div>
  </div>
  
  <div class='form-group row'>
    <label for='price' class='col-sm-4 col-form-label'>Price</label>
    <div class='col-sm-8 input-group mb-2'>
        <div class='input-group-prepend'>
                <div class='input-group-text'>$</div>
        </div>
      <input type='number' class='form-control' id='price' name='price' placeholder='Price'>
    </div>
  </div>
  
  <div class='form-group row'>
        <label for='salePrice' class='col-sm-4 col-form-label'>Sale Price</label>
        <div class='col-sm-8 input-group mb-2'>
            <div class='input-group-prepend'>
                <div class='input-group-text'>$</div>
            </div>
            <input type='number' class='form-control' id='salePrice' name='salePrice' placeholder='Sale Price'>
        </div>
  </div>
  
        <button type='reset' class='btn btn-warning'>Reset</button>
        <button type='submit' class='btn btn-success'>Submit</button>
  
</form>
</div>";
    }
}