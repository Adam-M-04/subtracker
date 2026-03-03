<?php

namespace Controllers;

use Core\Controller;

class ErrorController extends Controller
{
    public function notFound(): void
    {
        http_response_code(404);

        $this->render('404', [
            'title' => 'Błąd 404 - Nie znaleziono',
            'message' => 'Strona, której szukasz, nie istnieje lub została przeniesiona.'
        ], 'html');
    }
}