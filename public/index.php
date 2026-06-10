<?php

$debug = getenv('APP_DEBUG') === '1';
error_reporting(E_ALL);
ini_set('display_errors', $debug ? '1' : '0');

spl_autoload_register(function ($class) {
    $path = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    $file = __DIR__ . '/../src/' . $path . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

use Core\Router;

set_error_handler(function (int $severity, string $message, string $file, int $line): bool {
    throw new ErrorException($message, 0, $severity, $file, $line);
});

set_exception_handler(function (Throwable $e) use ($debug): void {
    error_log(sprintf('[unhandled_exception] %s in %s:%d', $e->getMessage(), $e->getFile(), $e->getLine()));
    http_response_code(500);
    $controller = new \Controllers\ErrorController();
    $controller->serverError($debug ? $e->getMessage() : null);
});

$router = new Router();
require __DIR__ . '/../src/routes.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$router->dispatch($uri, $method);