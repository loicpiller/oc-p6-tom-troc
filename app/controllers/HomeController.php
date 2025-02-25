<?php

namespace App\Controllers;

use MVC\Core\View;

class HomeController
{
    public function index(): void
    {
        $view = new View("Home Page");
        $view->render("pages/home", $data = ['paragraph' => 'Welcome to our home page!']);
    }
}