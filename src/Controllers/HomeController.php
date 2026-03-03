<?php

namespace Controllers;

use Core\Controller;
use Core\Auth;

class HomeController extends Controller
{
    public function index(): void
    {
        Auth::check();

        $this->render('dashboard', [
            'title' => 'Dashboard - SubTracker',
            'userEmail' => $_SESSION['user_email']
        ]);
    }
}