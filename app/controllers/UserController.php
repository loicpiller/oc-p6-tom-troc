<?php

namespace App\Controllers;

use MVC\Core\View;
use App\Repositories\UserRepository;
use App\Entities\UserEntity;

class UserController
{
    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $userRepository = new UserRepository();
            $user = $userRepository->findUserByEmail($email);

            if ($user && password_verify($password, $user->getPassword())) {
                $_SESSION['user'] = $user;
                header('Location: ' . action_url('/'));
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
                    'username' => $username,
                    'email' => $email,
                    'password' => $hashedPassword,
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
}
