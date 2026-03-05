<?php

namespace Core;

use Entities\User;

class Auth
{
    public static function check(): void
    {
        self::startSession();

        if (!self::id()) {
            header("Location: /login");
            exit;
        }
    }

    public static function requireAdmin(): void
    {
        self::check();

        if (self::role() !== 'admin') {
            header("Location: /");
            exit;
        }
    }

    public static function login(User $user): void
    {
        self::startSession();
        $_SESSION['user_id'] = $user->getId();
        $_SESSION['user_email'] = $user->getEmail();
        $_SESSION['user_role'] = $user->getRole();
    }

    public static function logout(): void
    {
        self::startSession();
        session_unset();
        session_destroy();
    }

    public static function id(): ?int
    {
        self::startSession();
        return $_SESSION['user_id'] ?? null;
    }

    public static function email(): ?string
    {
        self::startSession();
        return $_SESSION['user_email'] ?? null;
    }

    public static function role(): ?string
    {
        self::startSession();
        return $_SESSION['user_role'] ?? null;
    }

    private static function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}