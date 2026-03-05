<?php

namespace Core;

class JsonResponse
{
    public static function send(string $status, string $message, array $data = [], int $httpCode = 200): void
    {
        http_response_code($httpCode);
        header('Content-Type: application/json; charset=utf-8');

        echo json_encode([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ]);

        exit;
    }
}