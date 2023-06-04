<?php

namespace App\Core;

class Response {

    /**
	 * редирект на другую страницу
	 * @param string $url - url куда делать редиркт
	 */

	public static function redirect($url) 
    {
		header('location: '.$url);
		exit;
	}


	/**
	 * вывод страниц ошибок 
	 * @param int $code код ошибки
	 */

	public static function errorCode($code) 
    {
		http_response_code($code);

		$path = 'application/views/errors/'.$code.'.php';
		if (file_exists($path)) {
			require $path;
		}
		exit;
	}

    public static function json($data, $code = 200)
    {
        http_response_code($code);
		header('Content-Type: application/json');
        echo json_encode($data);
    }
}