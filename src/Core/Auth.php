<?php

namespace Core;

use Entities\User;
use Enums\Role;

use Controllers\ErrorController;

class Auth
{
    public static function start(): void
    {
        self::startSession();
    }

    public static function check(): void
    {
        self::startSession();

        if (!self::id()) {
            if (self::isApiRequest()) {
                JsonResponse::send('error', 'Unauthorized', [], 401);
            }

            header('Location: /login');
            exit;
        }
    }

    public static function requireAdmin(): void
    {
        self::check();

        if (self::role() !== Role::ADMIN) {
            if (self::isApiRequest()) {
                JsonResponse::send('error', 'Forbidden', [], 403);
            }

            http_response_code(403);
            (new ErrorController())->forbidden();
            exit;
        }
    }

    public static function login(User $user, array $profile = []): void
    {
        self::startSession();
        session_regenerate_id(true);
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

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], (bool)$params['secure'], (bool)$params['httponly']);
        }
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
            $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || getenv('SESSION_COOKIE_SECURE') === '1';
            session_set_cookie_params([
                'lifetime' => 0,
                'path' => '/',
                'domain' => '',
                'secure' => $secure,
                'httponly' => true,
                'samesite' => 'Lax',
            ]);
            session_start();
        }
    }

    private static function isApiRequest(): bool
    {
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '';
        return str_starts_with($uri, '/api/');
    }
}