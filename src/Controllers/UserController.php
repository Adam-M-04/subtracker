<?php

namespace Controllers;

use Core\Controller;
use Core\Auth;

class UserController extends Controller
{
    public function index(): void
    {
        Auth::requireAdmin();

        $this->render('users', [
            'title' => 'Users - SubTracker',
            'userEmail' => $_SESSION['user_email']
        ]);
    }
}