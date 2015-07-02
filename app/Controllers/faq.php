<?php
namespace Controllers;

use Core\View;
use Core\Controller;

class FAQ extends Controller
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
        $data['title'] = 'FAQ';
        $data['token'] = $this->makeToken();

        View::renderTemplate('header', $data);
        View::render('faq/index', $data);
        View::renderTemplate('footer', $data);
    }
}