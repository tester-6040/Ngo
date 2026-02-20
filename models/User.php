<?php

declare(strict_types=1);

namespace Models;

use Core\BaseModel;

final class User extends BaseModel
{
    public function create(string $name, string $email, string $password, string $role): int
    {
        return $this->insert(
            'INSERT INTO users (name, email, password_hash, role, created_at, updated_at) VALUES (:name,:email,:password_hash,:role,NOW(),NOW())',
            [
                'name' => $name,
                'email' => $email,
                'password_hash' => password_hash($password, PASSWORD_BCRYPT),
                'role' => $role,
            ]
        );
    }

    public function findByEmail(string $email): ?array
    {
        $rows = $this->select('SELECT * FROM users WHERE email = :email LIMIT 1', ['email' => $email]);

        return $rows[0] ?? null;
    }

    public function findById(int $id): ?array
    {
        $rows = $this->select('SELECT * FROM users WHERE id = :id LIMIT 1', ['id' => $id]);

        return $rows[0] ?? null;
    }

    public function allByRole(string $role): array
    {
        return $this->select('SELECT id, name, email, role, created_at FROM users WHERE role = :role ORDER BY id DESC', ['role' => $role]);
    }

    public function all(): array
    {
        return $this->select('SELECT id, name, email, role, created_at FROM users ORDER BY id DESC');
    }

    public function updateById(int $id, string $name, string $email, string $role): int
    {
        return $this->update(
            'UPDATE users SET name = :name, email = :email, role = :role, updated_at = NOW() WHERE id = :id',
            ['id' => $id, 'name' => $name, 'email' => $email, 'role' => $role]
        );
    }

    public function deleteById(int $id): int
    {
        return $this->delete('DELETE FROM users WHERE id = :id', ['id' => $id]);
    }
}
