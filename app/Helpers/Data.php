<?php
namespace Helpers;

/*
 * data Helper - common data lookup methods
 *
 * @author David Carr - dave@daveismyname.com - http://daveismyname.com
 * @version 1.0
 * @date March 28, 2015
 * @date May 18 2015
 */
class Data
{
    /**
     * print_r call wrapped in pre tags
     * @param  string or array $data
     */
    public static function pr($data)
    {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }

    /**
     * var_dump call
     * @param  string or array $data
     */
    public static function vd($data)
    {
        var_dump($data);
    }

    /**
     * strlen call - count the lengh of the string
     * @param  string $data
     * @return string return the count
     */
    public static function sl($data)
    {
        return strlen($data);
    }

    /**
     * strtoupper - convert string to uppercase
     * @param  string $data
     * @return string
     */
    public static function sup($data)
    {
        return strtoupper($data);
    }

    /**
     * strtolower - convert string to lowercase
     * @param  string $data
     * @return string
     */
    public static function slw($data)
    {
        return strtolower($data);
    }

    /**
     * ucwords - the first letter of each word to be a capital
     * @param  string $data
     * @return string
     */
    public static function ucw($data)
    {
        return ucwords($data);
    }

    public function toTranslite($str)
    {
        $rus=array('А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я','а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п','р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я',' ');
        $lat=array('a','b','v','g','d','e','e','gh','z','i','y','k','l','m','n','o','p','r','s','t','u','f','h','c','ch','sh','sch','y','y','y','e','yu','ya','a','b','v','g','d','e','e','gh','z','i','y','k','l','m','n','o','p','r','s','t','u','f','h','c','ch','sh','sch','y','y','y','e','yu','ya','_');

        return str_replace($rus, $lat, $str);
    }

    public static function html($string)
   {
        return htmlspecialchars($string, ENT_QUOTES);
   }
}
