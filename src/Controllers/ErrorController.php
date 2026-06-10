<?php

namespace Controllers;

use Core\Controller;

class ErrorController extends Controller
{
    public function badRequest(): void
    {
        http_response_code(400);
        $this->render('400', [
            'title' => 'Error 400 - Bad Request',
            'message' => 'The request could not be processed.'
        ]);
    }

    public function unauthorized(): void
    {
        http_response_code(401);
        $this->render('401', [
            'title' => 'Error 401 - Unauthorized',
            'message' => 'Please sign in to access this page.'
        ]);
    }

    public function forbidden(): void
    {
        http_response_code(403);
        $this->render('403', [
            'title' => 'Error 403 - Forbidden',
            'message' => 'You do not have permission to access this resource.'
        ]);
    }

    public function notFound(): void
    {
        http_response_code(404);

        $this->render('404', [
            'title' => 'Error 404 - Not Found',
            'message' => 'The page you are looking for does not exist or has been moved.'
        ]);
    }

    public function serverError(?string $details = null): void
    {
        http_response_code(500);
        $this->render('500', [
            'title' => 'Error 500 - Server Error',
            'message' => 'An internal server error occurred.',
            'details' => $details
        ]);
    }
}