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

    /**
     * Metoda do renderowania widoków (plików HTML/PHP)
     * * @param string $view Nazwa pliku widoku (bez rozszerzenia .php)
     * @param array $data Tablica zmiennych przekazywanych do widoku
     */
    protected function render(string $view, array $data = [], string $extension = 'php'): void
    {
        extract($data);

        $viewFile = __DIR__ . '/../Views/' . $view . '.' . $extension;

        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            die("❌ Błąd systemu: Nie znaleziono pliku widoku <b>{$viewFile}</b>");
        }
    }

    /**
     * Metoda pomocnicza do przekierowań (np. po wylogowaniu lub błędnym logowaniu)
     */
    protected function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }
}