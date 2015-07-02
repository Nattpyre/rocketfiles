<?php
namespace Core;

use Core\Controller;
use Core\View;

class Error extends Controller
{
    private $error = null;

    public function __construct($error)
    {
        parent::__construct();
        $this->error = $error;
    }

    public function index()
    {
        header("HTTP/1.0 404 Not Found");

        $data['title'] = '404';
        $data['error'] = $this->error;

        View::render('error/404', $data);
    }

    public static function display($error, $class = 'alert alert-danger')
    {
        if (is_array($error)) {
            foreach ($error as $error) {
                $row.= "<div class='$class'>$error</div>";
            }
            return $row;
        } else {
            if (isset($error)) {
                return "<div class='$class'>$error</div>";
            }
        }
    }
}
