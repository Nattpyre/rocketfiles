<?php
namespace Core;

use Helpers\Session;

class Config
{
    public function __construct()
    {
        //Включение буферизации вывода
        ob_start();

        //Адрес сайта
        define('DIR', 'http://rocketfiles.zz.mu/');

        //Контроллера и метода по-умолчанию
        define('DEFAULT_CONTROLLER', 'main');
        define('DEFAULT_METHOD', 'index');

        //Шаблон по-умолчанию
        define('TEMPLATE', 'default');

        //Настройки базы данных
        define('DB_TYPE', 'mysql');
        define('DB_HOST', 'localhost');
        define('DB_NAME', 'rf_database');
        define('DB_USER', 'rovnatt');
        define('DB_PASS', 'qaz312wsx');
        define('PREFIX', 'rf_');

        //Префикс для сессий
        define('SESSION_PREFIX', 'rf_');

        //Название сайта
        define('SITETITLE', 'Rocket Files');

        //Email адрес сайта
        define('SITEEMAIL', 'robot@rocketfiles.zz.mu');

        //Собственный обработчик ошибок
        set_exception_handler('Core\Logger::ExceptionHandler');
        set_error_handler('Core\Logger::ErrorHandler');

        //Часовой пояс
        date_default_timezone_set('Europe/Moscow');

        //Запуск сессий
        Session::init();
    }
}
