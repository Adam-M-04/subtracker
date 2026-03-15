<?php

namespace Core;

use Entities\User;
use Enums\Role;

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

        if (self::role() !== Role::ADMIN) {
            header("Location: /");
            exit;
        }
    }

    public static function login(User $user, array $profile = []): void
    {
        self::startSession();
        $_SESSION['user_id'] = $user->getId();
        $_SESSION['user_email'] = $user->getEmail();
        $_SESSION['user_role'] = $user->getRole()->value;

        $_SESSION['user_currency'] = $profile['currency_id'] ?? 1;
        $fullName = trim(($profile['first_name'] ?? '') . ' ' . ($profile['last_name'] ?? ''));
        $_SESSION['user_name'] = !empty($fullName) ? $fullName : $user->getEmail();
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

    public static function role(): ?Role
    {
        self::startSession();
        return isset($_SESSION['user_role']) ? Role::from((int)$_SESSION['user_role']) : null;
    }

    public static function name(): ?string
    {
        self::startSession();
        return $_SESSION['user_name'] ?? self::email();
    }

    public static function currencyId(): int
    {
        self::startSession();
        return $_SESSION['user_currency'] ?? 1;
    }

    private static function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}