<?php

namespace Repositories;

use Core\Repository;
use Entities\User;
use PDO;

class UserRepository extends Repository
{
    public function findByEmail(string $email): ?User
    {
        $stmt = $this->db->prepare("SELECT id, email, password_hash, role FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        $user = new User();
        $user->setId($data['id'])
            ->setEmail($data['email'])
            ->setPasswordHash($data['password_hash'])
            ->setRole($data['role']);

        return $user;
    }
}