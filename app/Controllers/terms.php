<?php
namespace Controllers;

use Core\View;
use Core\Controller;

class Terms extends Controller
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
        $data['title'] = 'Условия пользования';
        $data['token'] = $this->makeToken();

        View::renderTemplate('header', $data);
        View::render('terms/index', $data);
        View::renderTemplate('footer', $data);
    }
}