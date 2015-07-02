<?php
namespace Controllers;

use Core\View;
use Core\Controller;

class Main extends Controller
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
        $data['title'] = 'Главная';
        $data['token'] = $this->makeToken();

        View::renderTemplate('header', $data);
        View::render('main/index', $data);
        View::renderTemplate('footer', $data);
    }
}
