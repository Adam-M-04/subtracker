<?php

namespace Controllers;

use Core\Controller;
use Core\Auth;

class SubscriptionController extends Controller
{
    public function index(): void
    {
        Auth::check();

        $this->render('subscriptions', [
            'title' => 'Subscriptions - SubTracker',
            'userEmail' => $_SESSION['user_email']
        ]);
    }
}