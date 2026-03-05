<?php

namespace Controllers;

use Core\Controller;
use Core\Auth;
use Repositories\UserRepository;

class AuthController extends Controller
{
    public function loginForm(): void
    {
        if (Auth::id()) {
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
            Auth::login($user);
            $this->redirect('/');
        } else {
            $this->render('login', ['error' => 'Invalid email or password.']);
        }
    }

    public function logout(): void
    {
        Auth::logout();
        $this->redirect('/login');
    }
}