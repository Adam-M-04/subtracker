<?php

namespace Core;

class Csrf
{
    private const SESSION_KEY = '_csrf_token';

    public static function token(): string
    {
        self::startSession();

        if (empty($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = bin2hex(random_bytes(32));
        }

        return $_SESSION[self::SESSION_KEY];
    }

    public static function validate(?string $token): bool
    {
        self::startSession();

        if (empty($token) || empty($_SESSION[self::SESSION_KEY])) {
            return false;
        }

        return hash_equals($_SESSION[self::SESSION_KEY], $token);
    }

    public static function tokenFromRequest(): ?string
    {
        $token = $_POST['_csrf_token'] ?? null;

        if (!empty($token)) {
            return (string)$token;
        }

        $headerToken = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
        return $headerToken !== null ? (string)$headerToken : null;
    }

    private static function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}

