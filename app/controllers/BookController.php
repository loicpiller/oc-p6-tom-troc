<?php

namespace App\Controllers;

use MVC\Core\View;
use App\Repositories\BookRepository;
use App\Repositories\BookStatusRepository;

class BookController
{
    private BookRepository $bookRepo;
    private BookStatusRepository $bookStatusRepo;

    public function __construct()
    {
        $this->bookRepo = new BookRepository();
        $this->bookStatusRepo = new BookStatusRepository();
    }
    
    public function index(): void
    {
        $search = trim($_GET['search'] ?? '');
        $books = $search !== '' ? $this->bookRepo->search($search) : $this->bookRepo->getAll(1);

        $view = new View("Nos livres à l'échange");
        $view->addStyle("books");
        $view->render("pages/books", [
            'books' => $books,
            'search' => $search,
        ]);
    }

    public function bookDetails(int $id): void
    {
        if ($id < 1) {
            throw new \Exception("incorect id", 400);
        }

        $book = $this->bookRepo->findBookById($id);
        if ($book === null) {
            throw new \Exception("Book not found", 404);
        }

        if (isset($_SESSION['user']) && $_SESSION['user']->getId() === $book->getUser()->getId()) {
            header('Location: ' . action_url('livre/{id}/edition', ['id' => $id]));
            exit();
        }

        $view = new View($book->getTitle());
        $view->addStyle("book");
        $view->render("pages/book", [
            'book' => $book,
        ]);
    }

    public function delete(int $id): void
    {
        if ($id < 1) {
            throw new \Exception("incorect id", 400);
        }

        $book = $this->bookRepo->findBookById($id);
        if ($book === null) {
            throw new \Exception("Book not found", 404);
        }

        if (!isset($_SESSION['user'])) {
            header('Location: ' . action_url('connexion'));
            exit();
        }

        if ($_SESSION['user']->getId() !== $book->getUser()->getId()) {
            throw new \Exception("Access denied", 403);
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            throw new \Exception("Method not allowed", 405);
        }

        $this->bookRepo->delete($id);
        header('Location: ' . action_url('mon-compte') . '#my-books');
        exit();
    }

    public function edit(int $id): void
    {
        if ($id < 1) {
            throw new \Exception("incorect id", 400);
        }

        $book = $this->bookRepo->findBookById($id);
        if ($book === null) {
            throw new \Exception("Book not found", 404);
        }

        if (!isset($_SESSION['user'])) {
            header('Location: ' . action_url('connexion'));
            exit();
        }

        if ($_SESSION['user']->getId() !== $book->getUser()->getId()) {
            throw new \Exception("Access denied", 403);
        }

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $contentLength = (int)($_SERVER['CONTENT_LENGTH'] ?? 0);
            if ($contentLength > 0 && empty($_POST) && empty($_FILES)) {
                $error = "L'image est trop volumineuse.";
            } else {
                $allowed = ['title', 'author', 'description', 'status_id'];
                $bookData = array_intersect_key($_POST, array_flip($allowed));
                $bookData['id'] = $id;

                if (!empty($_FILES['image']['name'])) {
                    if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                        $error = "L'image est trop volumineuse.";
                    } else {
                        try {
                            $bookData['image'] = upload_image($_FILES['image'], 'upload_img', 800);
                        } catch (\Exception $e) {
                            $error = "L'image est trop volumineuse ou dans un format non supporté (JPEG/PNG uniquement).";
                        }
                    }
                }

                if ($error === null) {
                    $this->bookRepo->save($bookData);
                    header('Location: ' . action_url('livre/{id}', ['id' => $id]));
                    exit();
                }
            }
        }

        $statuses = $this->bookStatusRepo->getAll();

        $view = new View("Modifier le livre");
        $view->addStyle("book_edit");
        $view->render("pages/edit_book", [
            'book' => $book,
            'statuses' => $statuses,
            'error' => $error,
        ]);
    }
}
