<?php
namespace Controllers;

use Core\View;
use Core\Controller;

class User extends Controller
{
    protected $_user;
    protected $_files;

    public function __construct()
    {
        parent::__construct();
        $this->_user = $this->loadModel('model_user');
        $this->_files = $this->loadModel('model_files');
        $this->checkCookie();
    }

    public function index()
    {
        $userID = array_keys($_GET);
        $userID = str_replace('user/', '', $userID[0]);

        $result = $this->_user->checkUserExists($userID);

        if(empty($result)) {
            header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");

            $data['title'] = '404';
            $data['error'] = "Страница не найдена.";

            View::render('Error/404');
            die();
        }

        if($_SESSION['rf_user_id'] != $userID) {
            \Helpers\Url::redirect('');
        }

        if(($_GET['useraction'] == 'delete') && ($_SESSION['rf_user_id'] == $userID)) {
            $this->delete($userID);
            $this->logout();
        }

        if($_GET['fileaction'] == 'delete' && ctype_digit($_GET['fileid'])) {
            $this->_user->deleteFile($result[0]['login'], $_GET['fileid']);
            \Helpers\Url::redirect('user/' . $userID);
        }

        $data['title'] = 'Аккаунт';
        $data['result'] = $result[0];
        $data['files'] = $this->showFiles($result[0]['login']);
        $data['token'] = $this->makeToken();

        View::renderTemplate('header', $data);
        View::render('user/index', $data);
        View::renderTemplate('footer', $data);
    }

    public function login()
    {
        if(isset($_SESSION['rf_user']) || (isset($_COOKIE['rf_user_cookie']) && isset($_COOKIE['rf_user_id_cookie']))) {
            \Helpers\Url::redirect('');
        }

        $data['title'] = 'Вход';
        $data['error'] = $error;
        $data['token'] = $this->makeToken();

        View::renderTemplate('header', $data);
        View::render('login/index', $data);
        View::renderTemplate('footer', $data);
    }

    public function forgot()
    {
        if(isset($_SESSION['rf_user']) || (isset($_COOKIE['rf_user_cookie']) && isset($_COOKIE['rf_user_id_cookie']))) {
            \Helpers\Url::redirect('');
        }

        $data['title'] = 'Восстановление пароля';
        $data['answer'] = $answer;
        $data['token'] = $this->makeToken();

        View::renderTemplate('header', $data);
        View::render('login/forgot', $data);
        View::renderTemplate('footer', $data);
    }

    public function register($answer = null)
    {
        if(isset($_SESSION['rf_user']) || (isset($_COOKIE['rf_user_cookie']) && isset($_COOKIE['rf_user_id_cookie']))) {
            \Helpers\Url::redirect('');
        }

        $data['title'] = 'Регистрация';
        $data['answer'] = $answer;
        $data['token'] = $this->makeToken();

        View::renderTemplate('header', $data);
        View::render('register/index', $data);
        View::renderTemplate('footer', $data);
    }

    public function createUser()
    {
        \Helpers\GUMP::set_field_name('username', 'Имя');
        \Helpers\GUMP::set_field_name('password', 'Пароль');
        \Helpers\GUMP::set_field_name('passconfirm', 'Подтверждение пароля');

        $validated = \Helpers\GUMP::is_valid($_POST, array(
            'username' => 'required|max_len,32|min_len,2|alpha_dash',
            'email' => 'required|valid_email',
            'password' => 'required|max_len,32|min_len,4|alpha_dash',
            'passconfirm' => 'required|max_len,32|min_len,4|alpha_dash'
            ));

        if(is_array($validated)) {
            echo $validated[0];
            die();
        }

        $checkUserName = $this->_user->checkUserName($_POST['username']);

        if(!empty($checkUserName)) {
            echo 'Пользователь с таким именем уже есть!';
            die();
        }

        $checkEmail = $this->_user->checkEmail(mb_strtolower($_POST['email']));

        if(!empty($checkEmail)) {
            echo 'Пользователь с таким email уже есть!';
            die();
        }

        if($_POST['password'] != $_POST['passconfirm']) {
            echo 'Пароли не совпадают.';
            die();
        }

        $password = \Helpers\Password::make($_POST['password']);

        $result = $this->_user->registerUser($_POST['username'], $_POST['email'], $password);

        if(ctype_digit($result)) {
            echo 'Пользователь успешно зарегистрирован! Вы можете войти используя ваш логин и пароль.';
            die();
        }
    }

    public function checkUser()
    {
        $userName = $_POST['userlogin'];
        $userPass = $_POST['userpassword'];

        if($_POST['token'] != $_COOKIE['token']) {
            echo 'Проверьте введенные данные.';
            die();
        }

        $result = $this->_user->getUserPassword($userName);

        if(empty($result)) {
            echo 'Логин и/или пароль введены неверно.';
            die();
        }

        if(\Helpers\Password::verify($userPass, $result[0]['password'])) {

            \Helpers\Session::set('user', $userName);
            \Helpers\Session::set('user_id', $result[0]['id']);

            if(isset($_POST['checkbox']) && $_POST['checkbox'] == 'on') {
                setcookie("rf_user_cookie", $userName, time()+60*60*24*7, "", "", false, true);
                setcookie("rf_user_id_cookie", $result[0]['id'], time()+60*60*24*7, "", "", false, true);
                setcookie("rf_user_pass_cookie", $userPass, time()+60*60*24*7, "", "", false, true);
            }

        } else {
            echo 'Логин и/или пароль введены неверно.';
            die();
        }
    }

    public function sendEmail()
    {
        $validated = \Helpers\GUMP::is_valid($_POST, array(
            'email' => 'required|valid_email',
            ));

        if(is_array($validated)) {
            echo $validated[0];
            die();
        }

        $answer = $this->_user->sendPassword($_POST['email']);

        echo $answer;
        die();
    }

    public function showFiles($userID)
    {
        $result = $this->_files->showUserFiles($userID);

        return $result;
    }

    public function logout()
    {
        if(isset($_SESSION['rf_user']) && isset($_SESSION['rf_user_id'])) {

            if(isset($_COOKIE['rf_user_cookie']) && isset($_COOKIE['rf_user_id_cookie'])) {
                setcookie("rf_user_cookie", "", time()-3600, "/", "", false, true);
                setcookie("rf_user_id_cookie", "", time()-3600, "/", "", false, true);
                setcookie("rf_user_pass_cookie", "", time()-3600, "/", "", false, true);
            }

            \Helpers\Session::destroy('user');
            \Helpers\Session::destroy('user_id');
            \Helpers\Url::redirect('');
        } else{
            \Helpers\Url::redirect('');
        }
    }

    public function premium()
    {
        if(!isset($_SESSION['rf_user'])) {
            \Helpers\Url::redirect('login');
        }

        $data['title'] = 'Премиум';
        $data['token'] = $this->makeToken();

        View::renderTemplate('header', $data);
        View::render('user/premium', $data);
        View::renderTemplate('footer', $data);
    }

    public function refresh()
    {
        \Helpers\GUMP::set_field_name('new-email', 'email');
        \Helpers\GUMP::set_field_name('new-password', 'Пароль');
        \Helpers\GUMP::set_field_name('confirm-new-password', 'Подтверждение пароля');

        $validated = \Helpers\GUMP::is_valid($_POST, array(
            'user-id' => 'required|integer',
            'old-email' => 'required|valid_email',
            'new-email' => 'required|valid_email',
            'new-password' => 'required|max_len,32|min_len,4',
            'confirm-new-password' => 'required|max_len,32|min_len,4'
            ));

        if(is_array($validated)) {
            echo $validated[0];
            die();
        }

        if($_POST['new-password'] != $_POST['confirm-new-password']) {
            echo 'Пароли не совпадают.';
            die();
        }

        if(mb_strtolower($_POST['old-email']) != mb_strtolower($_POST['new-email'])) {
            $checkEmail = $this->_user->checkEmail(mb_strtolower($_POST['new-email']));

            if(!empty($checkEmail)) {
                echo 'Этот email уже есть в базе.';
                die();
            }
        }

        $newPass = \Helpers\Password::make($_POST['new-password']);

        $this->_user->updateUser($_POST['new-email'], $newPass, $_POST['user-id']);
    }

    public function delete($userID)
    {
        $this->_user->deleteUser($userID);
    }
}