<?php

namespace App\Controllers;

use App\Repositories\BookRepository;
use MVC\Core\View;
use App\Repositories\UserRepository;

class UserController
{

    private UserRepository $userRepo;
    
    public function __construct()
    {
        $this->userRepo = new UserRepository;
    }
    
    public function profile(): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . action_url('connexion'));
            exit();
        }

        $user = $this->userRepo->findUserById((int) $_SESSION['user']->getId());
        if ($user === null) {
            throw new \Exception("User not found", 404);
        }

        $bookRepo = new BookRepository();
        $books = $bookRepo->findByUser($user->getId());

        $view = new View("Mon compte");
        $view->addStyle("profile");
        $view->addJS("profile");
        $view->render("pages/profile", [
            'user' => $user,
            'error' => null,
            'books' => $books,
        ]);
    }

    public function publicProfile(int $id): void
    {
        $user = $this->userRepo->findUserById($id);
        if ($user === null) {
            throw new \Exception("User not found", 404);
        }
        
        $bookRepo = new BookRepository();
        $books = $bookRepo->findByUser($id);

        $view = new View("Profile de {$user->getUsername()}");
        $view->addStyle("public_profile");
        $view->render("pages/public_profile", [
            'user' => $user,
           'books' => $books, 
        ]);
    }

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $userRepository = new UserRepository();
            $user = $userRepository->findUserByEmail($email);

            if ($user && password_verify($password, $user->getPassword())) {
                $user->setPassword('********');
                $_SESSION['user'] = $user;
                header('Location: ' . action_url('mon-compte'));
                exit();
            } else {
                $error = "Email ou mot de passe incorrect.";
            }
        }

        $view = new View("Connexion");
        $view->addStyle("auth");
        $view->render("pages/auth", [
            'pageType' => 'login',
            'error' => $error ?? null,
        ]);
    }

    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $userRepository = new UserRepository();
            if ($userRepository->findUserByEmail($email)) {
                $error = "Un compte avec cet email existe déjà.";
            } else {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $newUserData = [
                    'username'   => $username,
                    'email'      => $email,
                    'password'   => $hashedPassword,
                    'created_at' => (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
                ];
                $userRepository->save($newUserData);
                header('Location: ' . action_url('connexion'));
                exit();
            }
        }

        $view = new View("Inscription");
        $view->addStyle("auth");
        $view->render("pages/auth", [
            'pageType' => 'register',
            'error' => $error ?? null,
        ]);
    }

    public function logout(): void
    {
        session_destroy();
        header('Location: ' . action_url('/'));
        exit();
    }

    public function updateProfile(): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . action_url('connexion'));
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . action_url('mon-compte'));
            exit();
        }

        $username = trim($_POST['username'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($username === '' || $email === '') {
            throw new \Exception("Champs obligatoires manquants.", 400);
        }

        /** @var \App\Entities\UserEntity $user */
        $user = $_SESSION['user'];
        $id   = $user->getId();

        $data = [
            'id'       => $id,
            'username' => $username,
            'email'    => $email,
        ];

        if ($password !== '') {
            $data['password'] = password_hash($password, PASSWORD_BCRYPT);
        }

        $this->userRepo->save($data);

        $user->setUsername($username);
        $user->setEmail($email);
        if ($password !== '') {
            $user->setPassword('********');
        }
        $_SESSION['user'] = $user;

        header('Location: ' . action_url('mon-compte'));
        exit();
    }

    public function updatePicture(): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . action_url('connexion'));
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['avatar'])) {
            try {
                $uploadDir = 'upload_img'; 
                $filePath = upload_image($_FILES['avatar'], $uploadDir, 400, 80);

                $userRepo = new UserRepository();
                $user = $_SESSION['user'];
            
                $user->setAvatar($filePath);

                $userRepo->save([
                    'id'     => $user->getId(),
                    'avatar' => $filePath
                ]);

                header('Location: ' . action_url('mon-compte'));
                exit();

            } catch (\Exception $e) {
                die($e->getMessage());
            }
        }
    }
}
