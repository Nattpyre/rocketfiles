<?php

namespace Core;

use Core\View;

class Router
{

    public static $fallback = true;

    public static $halts = true;

    public static $routes = array();
    public static $methods = array();
    public static $callbacks = array();
    public static $errorCallback;

    public static $patterns = array(
        ':any' => '[^/]+',
        ':num' => '[0-9]+',
        ':all' => '.*'
    );

    public static function __callstatic($method, $params)
    {

        $uri = dirname($_SERVER['PHP_SELF']).'/'.$params[0];
        $callback = $params[1];

        array_push(self::$routes, $uri);
        array_push(self::$methods, strtoupper($method));
        array_push(self::$callbacks, $callback);
    }

    public static function error($callback)
    {
        self::$errorCallback = $callback;
    }

    public static function haltOnMatch($flag = true)
    {
        self::$halts = $flag;
    }

    public static function invokeObject($callback, $matched = null, $msg = null)
    {
        $last = explode('/', $callback);
        $last = end($last);

        $segments = explode('@', $last);

        $controller = new $segments[0]($msg);

        if ($matched == null) {
            $controller->$segments[1]();
        } else {
            call_user_func_array(array($controller, $segments[1]), $matched);
        }
    }

    public static function autoDispatch()
    {
        $uri = parse_url($_SERVER['QUERY_STRING'], PHP_URL_PATH);
        $uri = trim($uri, ' /');
        $uri = ($amp = strpos($uri, '&')) !== false ? substr($uri, 0, $amp) : $uri;

        $parts = explode('/', $uri);

        $controller = array_shift($parts);
        $controller = $controller ? $controller : DEFAULT_CONTROLLER;

        $method = array_shift($parts);
        $method = $method ? $method : DEFAULT_METHOD;

        $args = !empty($parts) ? $parts : array();

        if (!file_exists("app/Controllers/$controller.php")) {
            return false;
        }

        $controller = ucwords($controller);
        $controller = '\Controllers\\' . $controller;
        $c = new $controller;

        if (method_exists($c, $method)) {
            $c->$method($args);
            return true;
        }

        return false;
    }

    public static function dispatch()
    {

        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        $searches = array_keys(static::$patterns);
        $replaces = array_values(static::$patterns);

        self::$routes = str_replace('//', '/', self::$routes);

        $found_route = false;

        $query = '';
        $q_arr = array();
        if (strpos($uri, '&') > 0) {
            $query = substr($uri, strpos($uri, '&') + 1);
            $uri = substr($uri, 0, strpos($uri, '&'));
            $q_arr = explode('&', $query);
            foreach ($q_arr as $q) {
                $qobj = explode('=', $q);
                $q_arr[] = array($qobj[0] => $qobj[1]);
                if (!isset($_GET[$qobj[0]])) {
                    $_GET[$qobj[0]] = $qobj[1];
                }
            }
        }

        if (in_array($uri, self::$routes)) {
            $route_pos = array_keys(self::$routes, $uri);

            foreach ($route_pos as $route) {
                if (self::$methods[$route] == $method || self::$methods[$route] == 'ANY') {
                    $found_route = true;

                    if (!is_object(self::$callbacks[$route])) {
                        self::invokeObject(self::$callbacks[$route]);
                        if (self::$halts) {
                            return;
                        }
                    } else {
                        call_user_func(self::$callbacks[$route]);
                        if (self::$halts) {
                            return;
                        }
                    }
                }

            }

        } else {
            $pos = 0;

            foreach (self::$routes as $route) {
                $route = str_replace('//', '/', $route);

                if (strpos($route, ':') !== false) {
                    $route = str_replace($searches, $replaces, $route);
                }

                if (preg_match('#^' . $route . '$#', $uri, $matched)) {
                    if (self::$methods[$pos] == $method || self::$methods[$pos] == 'ANY') {
                        $found_route = true;

                        array_shift($matched);

                        if (!is_object(self::$callbacks[$pos])) {
                            self::invokeObject(self::$callbacks[$pos], $matched);
                            if (self::$halts) {
                                return;
                            }
                        } else {
                            call_user_func_array(self::$callbacks[$pos], $matched);
                            if (self::$halts) {
                                return;
                            }
                        }
                    }
                }
                $pos++;
            }
        }

        if (self::$fallback) {
            $found_route = self::autoDispatch();
        }

        if (!$found_route) {
            if (!self::$errorCallback) {
                self::$errorCallback = function () {
                    header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");

                    $data['title'] = '404';
                    $data['error'] = "Страница не найдена.";

                    View::render('Error/404', $data);
                };
            }

            if (!is_object(self::$errorCallback)) {
                self::invokeObject(self::$errorCallback, null, 'Маршруты не найдены.');
                if (self::$halts) {
                    return;
                }
            } else {
                call_user_func(self::$errorCallback);
                if (self::$halts) {
                    return;
                }
            }
        }
    }
}
