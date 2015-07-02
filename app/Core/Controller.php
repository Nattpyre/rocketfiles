<?php
namespace Core;

use Core\View;

abstract class Controller
{
    public $view;
    public $model;

    public function __construct()
    {
      $this->view = new View();
    }

    protected function loadModel($name){

      $modelpath = strtolower('app/models/'.$name.'.php');
    
      if(file_exists($modelpath)){

         require_once $modelpath;

         $parts = explode('/',$name);
         $modelName = ucwords(end($parts));
         $model = new $modelName();

         return $model;
      } else {
         $this->_error("Модель не существует: ".$modelpath);
         return false;
      }
   }

   protected function checkCookie()
   {
      if(isset($_COOKIE['rf_user_cookie']) && isset($_COOKIE['rf_user_id_cookie']) && isset($_COOKIE['rf_user_pass_cookie'])) {
        $result = $this->_user->checkUserCookie($_COOKIE['rf_user_cookie']);

        if(\Helpers\Password::verify($_COOKIE['rf_user_pass_cookie'], $result[0]['password'])) {

          \Helpers\Session::set('user', $_COOKIE['rf_user_cookie']);
          \Helpers\Session::set('user_id', $_COOKIE['rf_user_id_cookie']);

        }
      }
   }

   protected function makeToken()
   {
      if(!isset($_COOKIE['token'])) {
              $token = \Helpers\Csrf::makeToken();
              setcookie("token", $token, time()+60*60*24, "/", "", false, true);
          } else {
              $token = $_COOKIE['token'];
              setcookie("token", $token, time()+60*60*24, "/", "", false, true);
          }

        return $token;
     }
}
