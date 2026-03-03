<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

spl_autoload_register(function ($class) {
    $path = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    $file = __DIR__ . '/../src/' . $path . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

use \Core\Router;

$router = new Router();

$router->get('/', 'Controllers\HomeController', 'index');

$router->get('/login', 'Controllers\AuthController', 'loginForm');
$router->post('/login', 'Controllers\AuthController', 'login');
$router->get('/logout', 'Controllers\AuthController', 'logout');

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$router->dispatch($uri, $method);