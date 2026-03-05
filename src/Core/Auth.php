<?php

namespace Core;

class Auth
{
    public static function check(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }
    }

    public static function requireAdmin(): void
    {
        self::check();

        if (($_SESSION['user_role'] ?? 'user') !== 'admin') {
            header("Location: /");
            exit;
        }
    }
}