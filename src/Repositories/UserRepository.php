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
            ->setRole(Role::from($data['role_id']));

        return $user;
    }

    public function save(User $user): bool
    {
        $stmt = $this->db->prepare("INSERT INTO users (email, password_hash, role_id) VALUES (:email, :password_hash, :role_id) RETURNING id");

        $success = $stmt->execute([
            'email' => $user->getEmail(),
            'password_hash' => $user->getPasswordHash(),
            'role_id' => $user->getRole()->value
        ]);

        if ($success) {
            $user->setId($stmt->fetchColumn());
            return true;
        }

        return false;
    }

    public function findAllWithStats(): array
    {
        $sql = "
            SELECT 
                u.id, 
                u.email, 
                u.role_id, 
                u.created_at,
                p.first_name,
                p.last_name,
                COUNT(s.id) as active_subs
            FROM users u
            LEFT JOIN user_profiles p ON u.id = p.user_id
            LEFT JOIN subscriptions s ON u.id = s.user_id AND s.status_id = 1
            GROUP BY u.id, p.first_name, p.last_name
            ORDER BY u.created_at DESC
        ";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}