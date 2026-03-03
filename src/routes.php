<?php

/** @var \Core\Router $router */
$router->get('/', 'Controllers\HomeController', 'index');
$router->get('/login', 'Controllers\AuthController', 'loginForm');
$router->post('/login', 'Controllers\AuthController', 'login');
$router->get('/logout', 'Controllers\AuthController', 'logout');

$router->get('/subscriptions', 'Controllers\SubscriptionController', 'index');
$router->get('/users', 'Controllers\UserController', 'index');
$router->get('/settings', 'Controllers\SettingsController', 'index');
