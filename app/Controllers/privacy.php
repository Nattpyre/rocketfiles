<?php
namespace Controllers;

use Core\View;
use Core\Controller;

class Privacy extends Controller
{
    protected $user;

    public function __construct()
    {
        parent::__construct();
        $this->_user = $this->loadModel('model_user');
        $this->checkCookie();
    }

    public function index()
    {
        $data['title'] = 'Политика конфиденциальности';
        $data['token'] = $this->makeToken();

        View::renderTemplate('header', $data);
        View::render('privacy/index', $data);
        View::renderTemplate('footer', $data);
    }
}