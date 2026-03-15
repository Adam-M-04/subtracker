<?php

namespace Repositories;

use Core\Repository;
use Entities\User;
use Enums\Role;
use PDO;
use Exception;

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

    public function save(User $user, string $firstName = '', string $lastName = ''): bool
    {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("INSERT INTO users (email, password_hash, role_id) VALUES (:email, :password_hash, :role_id) RETURNING id");

            $success = $stmt->execute([
                'email' => $user->getEmail(),
                'password_hash' => $user->getPasswordHash(),
                'role_id' => $user->getRole()->value
            ]);

            if ($success) {
                $userId = $stmt->fetchColumn();
                $user->setId($userId);

                $profileStmt = $this->db->prepare("INSERT INTO user_profiles (user_id, first_name, last_name, currency_id) VALUES (:user_id, :first_name, :last_name, 1)");
                $profileStmt->execute([
                    'user_id' => $userId,
                    'first_name' => $firstName,
                    'last_name' => $lastName
                ]);

                $this->db->commit();
                return true;
            }

            $this->db->rollBack();
            return false;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
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

    public function getProfile(int $userId): array
    {
        $stmt = $this->db->prepare("SELECT first_name, last_name, currency_id FROM user_profiles WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ?: ['first_name' => '', 'last_name' => '', 'currency_id' => 1];
    }

    public function updateProfile(int $userId, string $firstName, string $lastName, int $currencyId): bool
    {
        $stmt = $this->db->prepare("
            UPDATE user_profiles 
            SET first_name = :first_name, last_name = :last_name, currency_id = :currency_id 
            WHERE user_id = :user_id
        ");

        return $stmt->execute([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'currency_id' => $currencyId,
            'user_id' => $userId
        ]);
    }

    public function deleteUser(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount() > 0;
    }

    public function updateRole(int $id, Role $role): bool
    {
        $stmt = $this->db->prepare("UPDATE users SET role_id = :role_id WHERE id = :id");
        $stmt->execute(['role_id' => $role->value, 'id' => $id]);
        return $stmt->rowCount() > 0;
    }
}