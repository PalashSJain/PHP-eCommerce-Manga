<?php
/**
 * Created by PhpStorm.
 * User: Palash
 * Date: 2/26/2018
 * Time: 4:06 PM
 */

class Navigation
{
    /**
     * @param $currentPage : Can be either "Home", "Cart", "Admin", "Login". Helps set class active on header links
     * @return string: HTML UI element of the Header Navigation System
     */
    static function header($currentPage)
    {
        return "<html>"
            . Navigation::getHead()
            . "<body>"
            . Navigation::getNavbar($currentPage);
    }

    /**
     * @return string footer element showing course name, link to github repo and mailing address
     */
    public static function footer()
    {
        return "</div><div style='margin-bottom:50px'></div>
        <nav class='navbar fixed-bottom navbar-light footer'>
          <p class='mx-auto my-auto'>ISTE 756 | <a href='https://github.com/PalashSJain/PHP-eCommerce-Manga'>Palash Sanjay Jain</a> | <a href='mailTo:pxj9579@rit.edu'>pxj9579@rit.edu</a></p>
        </nav>
        </body></html>";
    }

    /**
     * @return string header with DOCTYPE, head elment, css stylesheets, js scripts and script methods
     */
    private static function getHead()
    {
        return "<!DOCTYPE html>
<head>
    <link rel='stylesheet' href='css/bootstrap.min.css'>
    <link rel='stylesheet' href='css/styles.css'>
    <script src='js/jquery-3.2.1.slim.min.js'></script>
    <script src='js/popper.min.js'></script>
    <script src='js/bootstrap.min.js'></script>
    
    <script type='text/javascript'>
        function toast() {
            var x = document.getElementById('snackbar');
            x.className = 'show';
            setTimeout(function(){ x.className = x.className.replace('show', ''); }, 3000);
        }
    </script>
</head>";

    }

    /**
     * @param $currentPage string can be either 'Home', "Cart", "Admin" or anything else
     * @return string navbar with relevant header selected
     */
    private static function getNavbar($currentPage)
    {
        return "
<nav class='navbar navbar-expand-sm navbar-light'>
  <a class='navbar-brand' href='/index.php'>Manga Store</a>
  <div class='navbar-collapse' id='navbarSupportedContent'>
    <ul class='navbar-nav ml-auto'>" .
            ((isset($_SESSION['isUser']) && $_SESSION['isUser']) ?
                "<li class='nav-item " . ($currentPage == 'Home' ? 'active' : '') . "'><a class='nav-link' href='index.php'>Home</a></li>
            <li class='nav-item " . ($currentPage == 'Cart' ? 'active' : '') . "'><a class='nav-link' href='cart.php'>Cart</a></li>
            <li class='nav-item " . ($currentPage == 'Admin' ? 'active' : '') . "'><a class='nav-link' href='logout.php'>Logout</a></li>" : "")
            . ((isset($_SESSION['isAdmin']) && $_SESSION['isAdmin']) ? "
            <li class='nav-item " . ($currentPage == 'Admin' ? 'active' : '') . "'><a class='nav-link' href='admin.php'>Admin</a></li>
            <li class='nav-item " . ($currentPage == 'Admin' ? 'active' : '') . "'><a class='nav-link' href='logout.php'>Logout</a></li>" : "")
            . "</ul>
  </div>
</nav>
</div>";
    }
}