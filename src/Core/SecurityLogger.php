<?php

namespace Core;

class SecurityLogger
{
    public static function logFailedLogin(string $email, string $reason): void
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
        $line = sprintf(
            "[%s] failed_login email=%s ip=%s reason=%s ua=%s\n",
            date('c'),
            self::sanitize($email),
            self::sanitize($ip),
            self::sanitize($reason),
            self::sanitize($userAgent)
        );

        $logDir = __DIR__ . '/../../storage/logs';
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0775, true);
        }

        @file_put_contents($logDir . '/security.log', $line, FILE_APPEND);
    }

    private static function sanitize(string $value): string
    {
        return str_replace(["\n", "\r"], '', $value);
    }
}

