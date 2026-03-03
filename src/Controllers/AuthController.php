<?php

namespace Controllers;

use Core\Controller;

class AuthController extends Controller
{
    public function loginForm(): void
    {
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/');
        }

        $this->render('login', ['error' => '']);
    }

    public function login(): void
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        // TYMCZASOWE: Symulacja bazy danych dla testów ról
        $mockUsers = [
            'admin@subtracker.pl' => ['id' => 1, 'role' => 'admin', 'password' => 'admin123'],
            'user@example.com' => ['id' => 2, 'role' => 'user', 'password' => 'user123']
        ];

        if (array_key_exists($email, $mockUsers) && $mockUsers[$email]['password'] === $password) {
            $_SESSION['user_id'] = $mockUsers[$email]['id'];
            $_SESSION['user_email'] = $email;
            $_SESSION['user_role'] = $mockUsers[$email]['role'];

            $this->redirect('/');
        } else {
            $this->render('login', ['error' => 'Nieprawidłowy email lub hasło.']);
        }
    }

    public function logout(): void
    {
        session_unset();
        session_destroy();
        $this->redirect('/login');
    }
}