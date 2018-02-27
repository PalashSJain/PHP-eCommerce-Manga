<?php
/**
 * Created by PhpStorm.
 * User: Palash
 * Date: 2/26/2018
 * Time: 4:06 PM
 */

/**
 * @param $currentPage : Can be either "Home", "Cart", "Admin". Helps set class active on header links
 * @return string: HTML UI element of the Header Navigation System
 */
class Navigation
{
    static function header($currentPage)
    {
        return "<html>"
            . Navigation::getHead()
            . "<body>"
            . Navigation::getNavbar($currentPage);
    }

    public static function footer()
    {
        return "</div></body></html>";
    }

    private static function getHead()
    {
        return <<<HEAD
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="/PHP-eCommerce-Manga/css/styles.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</head>
HEAD;

    }

    private static function getNavbar($currentPage)
    {
        $noOfProductsInCart = isset($_SESSION["ProductsInCart"]) ? "(".count($_SESSION["ProductsInCart"]).")" : "";
        return <<<NAV
<div class='container'>
<nav class="navbar navbar-expand-sm navbar-light bg-light">
  <a class="navbar-brand" href="#">Manga Store</a>
  <div class="navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav ml-auto">
      <li class="nav-item ".($currentPage == "Home" ? "active" : "").""><a class="nav-link" href="/PHP-eCommerce-Manga/php/index.php">Home</a></li>
      <li class="nav-item ".($currentPage == "Cart" ? "active" : "").""><a class="nav-link" href="/PHP-eCommerce-Manga/php/cart/cart.php">Cart $noOfProductsInCart</a></li>
      <li class="nav-item ".($currentPage == "Admin" ? "active" : "").""><a class="nav-link" href="/PHP-eCommerce-Manga/php/admin/admin.php">Admin</a></li>
    </ul>
  </div>
</nav>
NAV;

    }

}