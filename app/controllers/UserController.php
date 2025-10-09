<?php

namespace App\Controllers;

use MVC\Core\View;
use App\Repositories\UserRepository;
use App\Entities\UserEntity;

class UserController
{
    public function login(): void
    {
        $view = new View("Connexion");
        $view->addStyle("auth");
        $view->render("pages/auth", [
            'pageType' => 'login',
        ]);
    }

    public function register(): void
    {
        $view = new View("Inscription");
        $view->addStyle("auth");
        $view->render("pages/auth", [
            'pageType' => 'register',
        ]);
    }
}
