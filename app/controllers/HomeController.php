<?php

namespace App\Controllers;

use MVC\Core\View;
use App\Repositories\BookRepository;

class HomeController
{
    public function index(): void
    {
        $bookRepo = new BookRepository();
        $books = $bookRepo->getLast(4);

        $view = new View("Accueil");
        $view->addStyle("home");
        $view->render("pages/home", [
            'books' => $books,
        ]);
    }
}
