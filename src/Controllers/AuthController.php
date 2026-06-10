<?php

namespace Controllers;

use Core\Controller;
use Core\Auth;
use Core\LoginThrottle;
use Core\SecurityLogger;
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
        if (!$this->validateCsrf()) {
            return;
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $email = trim(strtolower($email));
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

        $throttle = LoginThrottle::isBlocked($email, $ip);
        if ($throttle['blocked']) {
            $minutesLeft = (int)ceil($throttle['seconds_left'] / 60);
            SecurityLogger::logFailedLogin($email, 'blocked_rate_limit');
            $this->render('login', ['error' => 'Too many login attempts. Try again in about ' . $minutesLeft . ' minute(s).']);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            LoginThrottle::registerFailure($email, $ip);
            SecurityLogger::logFailedLogin($email, 'invalid_email_format');
            $this->render('login', ['error' => 'Invalid email or password.']);
            return;
        }

        $userRepo = new UserRepository();
        $user = $userRepo->findByEmail($email);

        if ($user && password_verify($password, $user->getPasswordHash())) {
            LoginThrottle::registerSuccess($email, $ip);
            $profile = $userRepo->getProfile($user->getId());
            Auth::login($user, $profile);

            $this->redirect('/');
        } else {
            LoginThrottle::registerFailure($email, $ip);
            SecurityLogger::logFailedLogin($email, 'invalid_credentials');
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
        if (!$this->validateCsrf()) {
            return;
        }

        $firstName = trim($_POST['first_name'] ?? '');
        $lastName = trim($_POST['last_name'] ?? '');
        $email = trim(strtolower($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        if (empty($firstName) || empty($email) || empty($password)) {
            $this->render('register', ['error' => 'Please fill in all required fields.']);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->render('register', ['error' => 'Please provide a valid email address.']);
            return;
        }

        if (strlen($firstName) > 100 || strlen($lastName) > 100 || strlen($email) > 255) {
            $this->render('register', ['error' => 'Provided data is too long.']);
            return;
        }

        if (!$this->isStrongPassword($password)) {
            $this->render('register', ['error' => 'Password must be at least 8 characters long and include uppercase, lowercase, number, and special character.']);
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

    private function isStrongPassword(string $password): bool
    {
        if (strlen($password) < 8) {
            return false;
        }

        return (bool)preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).+$/', $password);
    }
}