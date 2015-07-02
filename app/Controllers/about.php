<?php
namespace Controllers;

use Core\View;
use Core\Controller;

class About extends Controller
{
    protected $_user;

    public function __construct()
    {
        parent::__construct();
        $this->_user = $this->loadModel('model_user');
        $this->checkCookie();
    }

    public function index()
    {
        $data['title'] = 'Об авторе';
        $data['token'] = $this->makeToken();

        View::renderTemplate('header', $data);
        View::render('about/index', $data);
        View::renderTemplate('footer', $data);
    }
}