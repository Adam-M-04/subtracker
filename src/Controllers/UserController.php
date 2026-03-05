<?php

namespace Controllers;

use Core\Controller;
use Core\Auth;
use Repositories\UserRepository;

class UserController extends Controller
{
    public function index(): void
    {
        Auth::requireAdmin();

        $repo = new UserRepository();
        $users = $repo->findAllWithStats();

        $this->render('users', [
            'title' => 'Users Management - SubTracker',
            'userEmail' => Auth::email(),
            'users' => $users
        ]);
    }
}