<?php

namespace App\Controllers;
use App\Core\Controller;
use App\Core\View;

class MainController extends Controller
{
    /**
     * главная страница
     */
    public function indexAction()
    {
        return View::render("index");
    }
}
