<?php

namespace Repositories;

use Core\Repository;
use Entities\User;
use Enums\Role;
use PDO;

class UserRepository extends Repository
{
    public function findByEmail(string $email): ?User
    {
        // Notice we select role_id now
        $stmt = $this->db->prepare("SELECT id, email, password_hash, role_id FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        $user = new User();
        $user->setId($data['id'])
            ->setEmail($data['email'])
            ->setPasswordHash($data['password_hash'])
            ->setRole(Role::from($data['role_id'])); // Map Int to Enum

        return $user;
    }

    public function save(User $user): bool
    {
        $stmt = $this->db->prepare("INSERT INTO users (email, password_hash, role_id) VALUES (:email, :password_hash, :role_id) RETURNING id");

        $success = $stmt->execute([
            'email' => $user->getEmail(),
            'password_hash' => $user->getPasswordHash(),
            'role_id' => $user->getRole()->value // Map Enum to Int
        ]);

        if ($success) {
            $user->setId($stmt->fetchColumn());
            return true;
        }

        return false;
    }
}