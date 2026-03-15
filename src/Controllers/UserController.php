<?php

namespace Controllers;

use Core\Controller;
use Core\Auth;
use Core\JsonResponse;
use Enums\Role;
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

    public function delete(): void
    {
        Auth::requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            JsonResponse::send('error', 'Method not allowed', [], 405);
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $userId = (int)($input['id'] ?? 0);

        if (!$userId) {
            JsonResponse::send('error', 'Missing user ID', [], 400);
        }

        if ($userId === Auth::id()) {
            JsonResponse::send('error', 'You cannot delete your own account.', [], 403);
        }

        $repo = new UserRepository();

        if ($repo->deleteUser($userId)) {
            JsonResponse::send('success', 'User deleted successfully');
        } else {
            JsonResponse::send('error', 'User not found or could not be deleted', [], 404);
        }
    }

    public function updateRole(): void
    {
        Auth::requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            JsonResponse::send('error', 'Method not allowed', [], 405);
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $userId = (int)($input['id'] ?? 0);
        $roleValue = (int)($input['role'] ?? 0);

        if (!$userId || !$roleValue) {
            JsonResponse::send('error', 'Invalid data provided', [], 400);
        }

        if ($userId === Auth::id()) {
            JsonResponse::send('error', 'You cannot change your own role.', [], 403);
        }

        $role = Role::tryFrom($roleValue);
        if (!$role) {
            JsonResponse::send('error', 'Invalid role specified', [], 400);
        }

        $repo = new UserRepository();

        if ($repo->updateRole($userId, $role)) {
            JsonResponse::send('success', 'User role updated successfully');
        } else {
            JsonResponse::send('error', 'Failed to update user role', [], 500);
        }
    }
}