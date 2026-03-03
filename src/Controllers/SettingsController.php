<?php

namespace Controllers;

use Core\Controller;
use Core\Auth;

class SettingsController extends Controller
{
    public function index(): void
    {
        Auth::check();

        $this->render('settings', [
            'title' => 'Settings - SubTracker',
            'userEmail' => $_SESSION['user_email']
        ]);
    }
}