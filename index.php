<?php

//Автозагрузчик
if (file_exists('vendor/autoload.php')) {
    require 'vendor/autoload.php';
} else {
    echo "<h1>Пожалуйста, установите autoload.php</h1>";
    die();
}

//Файл настроек
if (!is_readable('app/Core/Config.php')) {
    die('Не найден config.php.');
}

//Среда приложения
define('ENVIRONMENT', 'development');

if (defined('ENVIRONMENT')) {
    switch (ENVIRONMENT) {
        case 'development':
            error_reporting(E_ALL);
            break;
        case 'production':
            error_reporting(0);
            break;
        default:
            exit('Среда приложения установлена неверно.');
    }

}

//Загрузка конфига
new Core\Config();

//Создание псевдонимов для роутера
use Core\Router;

//Определение маршрутов
Router::get('', 'Controllers\Main@index');
Router::get('register', 'Controllers\User@register');
Router::post('register', 'Controllers\User@createUser');
Router::get('login', 'Controllers\User@login');
Router::post('login', 'Controllers\User@checkUser');
Router::get('login/forgot', 'Controllers\User@forgot');
Router::post('login/forgot', 'Controllers\User@sendEmail');
Router::get('user', 'Controllers\User@login');
Router::get('user/(:num)', 'Controllers\User@index');
Router::post('user/(:num)', 'Controllers\User@refresh');
Router::get('user/logout', 'Controllers\User@logout');
Router::get('user/premium', 'Controllers\User@premium');
Router::get('files', 'Controllers\Files@index');
Router::post('files', 'Controllers\Files@upload');
Router::get('files/(:num)', 'Controllers\Files@file');
Router::post('files/(:num)', 'Controllers\Files@addComment');
Router::post('files/appeal', 'Controllers\Files@sendAppeal');
Router::get('download', 'Controllers\Files@download');
Router::get('about', 'Controllers\About@index');
Router::get('privacy', 'Controllers\Privacy@index');
Router::get('terms', 'Controllers\Terms@index');
Router::get('faq', 'Controllers\FAQ@index');

//Если маршрут не найден
Router::error('Core\Error@index');

//Включить старый роутинг
Router::$fallback = false;

//Запуск найденных маршрутов
Router::dispatch();
