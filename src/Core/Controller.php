<?php

namespace Core;

class Controller
{
    public function __construct()
    {
        Auth::start();
    }

    protected function render(string $view, array $data = []): void
    {
        extract($data);
        $viewFile = __DIR__ . '/../Views/' . $view . '.php';

        if (file_exists($viewFile)) {
            ob_start();
            require $viewFile;
            $content = ob_get_clean();

            $layoutFile = __DIR__ . '/../Views/layout.php';

            if (file_exists($layoutFile) && !in_array($view, ['login', 'register', '400', '401', '403', '404', '500'])) {
                require $layoutFile;
            } else {
                echo $content;
            }
        } else {
            throw new \RuntimeException('View not found: ' . $view);
        }
    }

    protected function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }

    protected function validateCsrf(bool $json = false): bool
    {
        if (Csrf::validate(Csrf::tokenFromRequest())) {
            return true;
        }

        if ($json) {
            JsonResponse::send('error', 'Invalid CSRF token', [], 403);
        }

        http_response_code(403);
        (new \Controllers\ErrorController())->forbidden();
        return false;
    }
}