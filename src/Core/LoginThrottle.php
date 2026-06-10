<?php

namespace Core;

class LoginThrottle
{
    private const MAX_ATTEMPTS = 5;
    private const WINDOW_SECONDS = 900;
    private const BLOCK_SECONDS = 900;
    private const SESSION_KEY = 'login_throttle';

    public static function isBlocked(string $email, string $ip): array
    {
        self::startSession();

        $key = self::key($email, $ip);
        $state = $_SESSION[self::SESSION_KEY][$key] ?? null;

        if (!$state) {
            return ['blocked' => false, 'seconds_left' => 0];
        }

        $blockedUntil = (int)($state['blocked_until'] ?? 0);
        if ($blockedUntil <= time()) {
            unset($_SESSION[self::SESSION_KEY][$key]);
            return ['blocked' => false, 'seconds_left' => 0];
        }

        return ['blocked' => true, 'seconds_left' => $blockedUntil - time()];
    }

    public static function registerFailure(string $email, string $ip): array
    {
        self::startSession();

        $key = self::key($email, $ip);
        $now = time();
        $state = $_SESSION[self::SESSION_KEY][$key] ?? [
            'attempts' => 0,
            'first_attempt_at' => $now,
            'blocked_until' => 0,
        ];

        if (($now - (int)$state['first_attempt_at']) > self::WINDOW_SECONDS) {
            $state['attempts'] = 0;
            $state['first_attempt_at'] = $now;
            $state['blocked_until'] = 0;
        }

        $state['attempts']++;

        if ($state['attempts'] >= self::MAX_ATTEMPTS) {
            $state['blocked_until'] = $now + self::BLOCK_SECONDS;
        }

        $_SESSION[self::SESSION_KEY][$key] = $state;

        return [
            'blocked' => $state['blocked_until'] > $now,
            'seconds_left' => max(0, (int)$state['blocked_until'] - $now),
            'attempts' => (int)$state['attempts'],
        ];
    }

    public static function registerSuccess(string $email, string $ip): void
    {
        self::startSession();
        $key = self::key($email, $ip);
        unset($_SESSION[self::SESSION_KEY][$key]);
    }

    private static function key(string $email, string $ip): string
    {
        return hash('sha256', strtolower(trim($email)) . '|' . $ip);
    }

    private static function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}

