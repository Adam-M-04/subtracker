<?php

spl_autoload_register(function ($class) {
    $path = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    $file = __DIR__ . '/../' . $path . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

use Entities\User;
use Repositories\UserRepository;

$email = 'user@subtracker.pl';
$password = 'Test123#';
$role = 'user';

$userRepo = new UserRepository();

if ($userRepo->findByEmail($email)) {
    die("User with email {$email} already exists.\n");
}

$user = new User();
$user->setEmail($email);
$user->setPasswordHash(password_hash($password, PASSWORD_BCRYPT));
$user->setRole($role);

if ($userRepo->save($user)) {
    echo "Success! User {$email} created with ID: " . $user->getId() . "\n";
} else {
    echo "Failed to create user.\n";
}