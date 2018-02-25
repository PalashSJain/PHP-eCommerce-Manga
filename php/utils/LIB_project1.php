<?php
/**
 * Created by PhpStorm.
 * User: Palash
 * Date: 2/22/2018
 * Time: 10:12 PM
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
            . Navigation::getHead() .
            "<body>
            <nav class=\"navbar navbar-default\">
                <div class=\"container\">
                    <div class=\"navbar-header\">
                        <a class=\"navbar-brand\" href=\"#\">Manga Shop</a>
                    </div>
                    <div class=\"collapse navbar-collapse\" id=\"bs-example-navbar-collapse-1\">
                        <ul class=\"nav navbar-nav navbar-right\">
                            <li class=" . ($currentPage == "Home" ? "\"active\"" : "") . "><a href=\"/PHP-eCommerce-Manga/php/index.php\">Home</a></li>
                            <li class=" . ($currentPage == "Cart" ? "\"active\"" : "") . "><a href=\"/PHP-eCommerce-Manga/php/cart/cart.php\">Cart</a></li>
                            <li class=" . ($currentPage == "Admin" ? "\"active\"" : "") . "><a href=\"/PHP-eCommerce-Manga/php/admin/admin.php\">Admin</a></li>
                        </ul>
                    </div>
                </div>
            </nav>";
    }

    public static function footer()
    {
        return "</body></html>";
    }

    private static function getHead()
    {
        return "
            <head>
                <link rel=\"stylesheet\" href=\"/PHP-eCommerce-Manga/css/bootstrap.min.css\"
                      integrity=\"sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u\" crossorigin=\"anonymous\">
            </head>";
    }

}