<?php

namespace App\Core;

use App\Core\Response;

/**
 * Класс маршрутов
 * @var array $routes
 * @var array $params
 */

class Router
{
    protected $routes = [];
    protected $params = [];

    /**
     * Подгружаем конфиг с маршрутами и наполняяем $routes маршрутами
     */
    public function __construct()
    {
        $arr = require $_SERVER["DOCUMENT_ROOT"].'/../src/config/routes.php';

        foreach ($arr as $key => $val) {
            $this->add($key, $val);
        }
    }

    /**
     * наполненеие роутера с добавлением спец знаков для поиска регулярным выражением 
     */
    private function add($route, $params)
    {
        $route = preg_replace('/{([a-z]+):([^\}]+)}/', '(?P<\1>\2)', $route);
        $route = '#^' . $route . '$#';
        $this->routes[$route] = $params;
    }

    /**
     * Поиск в uri маршрута 
     * return bool true
     */
    private function match()
    {
        $url = trim($_SERVER['REQUEST_URI'], '/');
        $url = preg_replace("/\?.+/", "", $url);
        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                foreach ($matches as $key => $match) {
                    if (is_string($key)) {
                        if (is_numeric($match)) {
                            $match = (int) $match;
                        }
                        $params[$key] = $match;
                    }
                }
                $this->params = $params;
                return true;
            }
        }

        return false;
    }

    /**
     * Главный метод который запускает контроллеры а если не находит их отправляет ошибку 404
     */
    public function run()
    {
        if ($this->match()) {
            
            $path = 'App\Controllers\\' . ucfirst($this->params['controller']) . 'Controller';
            
            if (class_exists($path)) {
                $action = $this->params['action'] . 'Action';

                if (method_exists($path, $action)) {
                    $controller = new $path($this->params);
                    $controller->$action();
                    
                } else {
                    Response::errorCode(404);
                }
            } else {
                Response::errorCode(404);
            }
        } else {
            Response::errorCode(404);
        }
    }

}