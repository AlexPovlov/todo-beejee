<?php

namespace App\Core;

/**
 * Класс вида. Шаблонизирует и показывает страницы
 */

class View {

	/**
	 * шаблонизатор 
	 * @param string $template путь к странице
	 * @param array $vars переменные передаваемые на страницу
	 */
	public static function render($template ,$vars = []) 
	{
		extract($vars);
		
		$path = $_SERVER["DOCUMENT_ROOT"].'/../resources/views/'.$template.'.php';

		if (file_exists($path)) {
			require $path;
		}
	}
}	