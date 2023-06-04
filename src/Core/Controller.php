<?php

namespace App\Core;

/**
 * Абстрактный контроллер для настледования
 * @var array $route подгрузка с конфига данных о роутах
 * @var object|void $model обьект модели
 */
abstract class Controller
{
	public $route;
	protected $model;
	protected $request;
	protected $acl;

	/**
	 * @var array $route
	 */
	public function __construct($route)
	{
		//наполняем массив роутов
		$this->route = $route;

		if (!$this->checkAcl()) {
			if (in_array("application/json", Request::headers())) {
				Response::json(["errors" => "Вы не авторизованы"], 403);
			}
			Response::errorCode(403);
		}

		//Подгружаем модель
		$this->model = $this->loadModel($route['controller']);
	}

	public function loadModel($name)
	{
		$path = 'App\Models\\' . ucfirst($name);
		if (class_exists($path)) {
			return new $path;
		}
	}

	public function checkAcl()
	{
		$this->acl = require $_SERVER["DOCUMENT_ROOT"] . '/../src/config/acl.php';

		if (!isset($this->acl[$this->route['controller']][$this->route['action']]))
			return true;

		$cookie = Request::cookie();
		$session = Request::session();

		if (isset($cookie["token"]) && isset($session["token"])) {
			if ($session["token"] == $cookie["token"]) {
				if ($this->acl[$this->route['controller']][$this->route['action']] == 'auth') {
					return true;
				}
			} else {
				Request::truncreateSession();
			}
		}

		return false;
	}
}