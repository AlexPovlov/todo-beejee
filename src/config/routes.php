<?php

return [

	'api/todo' => [
		'controller' => 'todo',
		'action' => 'index',
	],

	'api/profile' => [
		'controller' => 'user',
		'action' => 'profile',
	],

	'api/login' => [
		'controller' => 'user',
		'action' => 'login',
	],

	'api/logout' => [
		'controller' => 'user',
		'action' => 'logout',
	],

	'api/todo/{id:\d+}' => [
		'controller' => 'todo',
		'action' => 'update',
	],

	'api/todo/{id:\d+}/complete' => [
		'controller' => 'todo',
		'action' => 'complete',
	],

	'api/todo/{id:\d+}/delete' => [
		'controller' => 'todo',
		'action' => 'delete',
	],

	'{url:.*}' => [
		'controller' => 'main',
		'action' => 'index',
	],
	
];