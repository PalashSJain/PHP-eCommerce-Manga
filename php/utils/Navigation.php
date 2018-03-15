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
    <link rel="stylesheet" href="/PHP-eCommerce-Manga/css/bootstrap.min.css">
    <link rel="stylesheet" href="/PHP-eCommerce-Manga/css/styles.css">
    <script src="/PHP-eCommerce-Manga/js/jquery-3.2.1.slim.min.js"></script>
    <script src="/PHP-eCommerce-Manga/js/popper.min.js"></script>
    <script src="/PHP-eCommerce-Manga/js/bootstrap.min.js"></script>
    
    <script type="text/javascript">
        function toast() {
            var x = document.getElementById("snackbar")
            x.className = "show";
            setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
        }
    </script>
</head>
HEAD;

    }

    private static function getNavbar($currentPage)
    {
        return "
<nav class='navbar navbar-expand-sm navbar-light'>
  <a class='navbar-brand' href='/PHP-eCommerce-Manga/php/index.php'>Manga Store</a>
  <div class='navbar-collapse' id='navbarSupportedContent'>
    <ul class='navbar-nav ml-auto'>
      <li class='nav-item '.($currentPage == 'Home' ? 'active' : '').''><a class='nav-link' href='/PHP-eCommerce-Manga/php/index.php'>Home</a></li>
      <li class='nav-item '.($currentPage == 'Cart' ? 'active' : '').''><a class='nav-link' href='/PHP-eCommerce-Manga/php/cart.php'>Cart</a></li>
      <li class='nav-item '.($currentPage == 'Admin' ? 'active' : '').''><a class='nav-link' href='/PHP-eCommerce-Manga/php/admin.php'>Admin</a></li>"
    . (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] ?
                "<li class='nav-item '.($currentPage == 'Admin' ? 'active' : '').''><a class='nav-link' href='/PHP-eCommerce-Manga/php/logout.php'>Logout</a></li>"
                : "")
. "</ul>
  </div>
</nav>
</div>";
    }
}