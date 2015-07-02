<?php
namespace Core;

use Helpers\Session;

class View
{
    private static $headers = array();

    public static function render($path, $data = false, $error = false)
    {
        if (!headers_sent()) {
            foreach (self::$headers as $header) {
                header($header, true);
            }
        }
        require "app/views/$path.php";
    }

    public static function renderTemplate($path, $data = false, $custom = false)
    {
        if (!headers_sent()) {
            foreach (self::$headers as $header) {
                header($header, true);
            }
        }

        if ($custom == false) {
            require "app/templates/".TEMPLATE."/$path.php";
        } else {
            require "app/templates/$custom/$path.php";
        }
    }

    public function addHeader($header)
    {
        self::$headers[] = $header;
    }

    public function addHeaders($headers = array())
    {
        foreach ($headers as $header) {
            $this->addHeader($header);
        }
    }
}
