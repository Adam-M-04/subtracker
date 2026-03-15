<?php

namespace Controllers;

use Core\Controller;
use Core\Auth;
use Entities\User;
use Enums\Role;
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
            $profile = $userRepo->getProfile($user->getId());
            Auth::login($user, $profile);

            $this->redirect('/');
        } else {
            $this->render('login', ['error' => 'Invalid email or password.']);
        }
    }

    public function registerForm(): void
    {
        if (Auth::id()) {
            $this->redirect('/');
        }

        $this->render('register', ['error' => '']);
    }

    public function register(): void
    {
        $firstName = trim($_POST['first_name'] ?? '');
        $lastName = trim($_POST['last_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        if (empty($firstName) || empty($email) || empty($password)) {
            $this->render('register', ['error' => 'Please fill in all required fields.']);
            return;
        }

        if ($password !== $passwordConfirm) {
            $this->render('register', ['error' => 'Passwords do not match.']);
            return;
        }

        $userRepo = new UserRepository();

        if ($userRepo->findByEmail($email)) {
            $this->render('register', ['error' => 'This email address is already registered.']);
            return;
        }

        $user = new User();
        $user->setEmail($email)
            ->setPasswordHash(password_hash($password, PASSWORD_BCRYPT))
            ->setRole(Role::USER);

        if ($userRepo->save($user, $firstName, $lastName)) {
            $profile = ['first_name' => $firstName, 'last_name' => $lastName, 'currency_id' => 1];
            Auth::login($user, $profile);

            $this->redirect('/');
        } else {
            $this->render('register', ['error' => 'A server error occurred during registration.']);
        }
    }

    public function logout(): void
    {
        Auth::logout();
        $this->redirect('/login');
    }
}