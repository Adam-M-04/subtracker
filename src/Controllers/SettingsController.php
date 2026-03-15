<?php

namespace Controllers;

use Core\Controller;
use Core\Auth;
use Repositories\UserRepository;

class SettingsController extends Controller
{
    public function index(): void
    {
        Auth::check();

        $repo = new UserRepository();
        $profile = $repo->getProfile(Auth::id());

        // Odczytujemy i od razu czyścimy komunikaty z sesji
        $success = $_SESSION['settings_success'] ?? null;
        $error = $_SESSION['settings_error'] ?? null;
        unset($_SESSION['settings_success'], $_SESSION['settings_error']);

        $this->render('settings', [
            'title' => 'Settings - SubTracker',
            'userEmail' => Auth::email(),
            'profile' => $profile,
            'success' => $success,
            'error' => $error
        ]);
    }

    public function update(): void
    {
        Auth::check();

        $firstName = trim($_POST['first_name'] ?? '');
        $lastName = trim($_POST['last_name'] ?? '');
        $currencyId = (int)($_POST['currency_id'] ?? 1);

        if (empty($firstName)) {
            $_SESSION['settings_error'] = 'First name is required.';
            $this->redirect('/settings');
            return;
        }

        $repo = new UserRepository();

        if ($repo->updateProfile(Auth::id(), $firstName, $lastName, $currencyId)) {
            $_SESSION['settings_success'] = 'Profile updated successfully.';
        } else {
            $_SESSION['settings_error'] = 'An error occurred while updating your profile.';
        }

        $this->redirect('/settings');
    }
}