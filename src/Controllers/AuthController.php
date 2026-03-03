<?php

namespace Controllers;

use Core\Controller;
use Repositories\UserRepository;

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

        $userRepo = new UserRepository();
        $user = $userRepo->findByEmail($email);

        if ($user && password_verify($password, $user->getPasswordHash())) {
            $_SESSION['user_id'] = $user->getId();
            $_SESSION['user_email'] = $user->getEmail();
            $_SESSION['user_role'] = $user->getRole();

            $this->redirect('/');
        } else {
            $this->render('login', ['error' => 'Invalid email or password.']);
        }
    }

    public function logout(): void
    {
        session_unset();
        session_destroy();
        $this->redirect('/login');
    }
}