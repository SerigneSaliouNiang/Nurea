<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Admin extends Model
{
    public function findById(int $id): ?array
    {
        $stmt = $this->db()->prepare('SELECT id, email, password_hash FROM admins WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row !== false ? $row : null;
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db()->prepare('SELECT id, email, password_hash FROM admins WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch();
        return $row !== false ? $row : null;
    }

    public function updatePasswordByEmail(string $email, string $passwordHash): void
    {
        $stmt = $this->db()->prepare('UPDATE admins SET password_hash = :password_hash WHERE email = :email');
        $stmt->execute([
            'email' => $email,
            'password_hash' => $passwordHash,
        ]);
    }

    public function create(string $email, string $passwordHash): int
    {
        $stmt = $this->db()->prepare('INSERT INTO admins (email, password_hash, created_at) VALUES (:email, :password_hash, NOW())');
        $stmt->execute([
            'email' => $email,
            'password_hash' => $passwordHash,
        ]);

        return (int)$this->db()->lastInsertId();
    }

    public function update(int $id, string $email, ?string $passwordHash): void
    {
        if ($passwordHash !== null) {
            $stmt = $this->db()->prepare('UPDATE admins SET email = :email, password_hash = :password_hash WHERE id = :id');
            $stmt->execute([
                'id' => $id,
                'email' => $email,
                'password_hash' => $passwordHash,
            ]);
        } else {
            $stmt = $this->db()->prepare('UPDATE admins SET email = :email WHERE id = :id');
            $stmt->execute([
                'id' => $id,
                'email' => $email,
            ]);
        }
    }

    public function delete(int $id): void
    {
        $stmt = $this->db()->prepare('DELETE FROM admins WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    public function all(): array
    {
        $stmt = $this->db()->query('SELECT id, email, created_at FROM admins ORDER BY id DESC');
        return $stmt->fetchAll();
    }
}
