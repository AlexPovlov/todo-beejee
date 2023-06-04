<?php

require __DIR__ . '/../vendor/autoload.php';

session_start();
$router = new App\Core\Router;
$router->run();