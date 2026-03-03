<?php

namespace Core;

class Controller
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
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
            if (file_exists($layoutFile) && !in_array($view, ['login', '404'])) {
                require $layoutFile;
            } else {
                echo $content;
            }
        } else {
            die("Błąd systemu: Nie znaleziono pliku widoku {$viewFile}");
        }
    }

    protected function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }
}