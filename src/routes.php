<?php

/** @var \Core\Router $router */
$router->get('/', 'Controllers\HomeController', 'index');

// Logowanie i Rejestracja
$router->get('/login', 'Controllers\AuthController', 'loginForm');
$router->post('/login', 'Controllers\AuthController', 'login');
$router->get('/register', 'Controllers\AuthController', 'registerForm');
$router->post('/register', 'Controllers\AuthController', 'register');
$router->get('/logout', 'Controllers\AuthController', 'logout');

// Subskrypcje
$router->get('/subscriptions', 'Controllers\SubscriptionController', 'index');
$router->post('/api/subscriptions', 'Controllers\SubscriptionController', 'store');
$router->post('/api/subscriptions/update', 'Controllers\SubscriptionController', 'update');
$router->post('/api/subscriptions/delete', 'Controllers\SubscriptionController', 'delete');

// Inne widoki
$router->get('/users', 'Controllers\UserController', 'index');
$router->get('/settings', 'Controllers\SettingsController', 'index');
$router->post('/settings', 'Controllers\SettingsController', 'update');

