<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Seller extends Model
{
    public function findById(int $id): ?array
    {
        try {
            $stmt = $this->db()->prepare('SELECT id, name, email, password_hash, password_changed_at FROM sellers WHERE id = :id LIMIT 1');
            $stmt->execute(['id' => $id]);
            $row = $stmt->fetch();
            return $row !== false ? $row : null;
        } catch (\PDOException $e) {
            $stmt = $this->db()->prepare('SELECT id, name, email, password_hash FROM sellers WHERE id = :id LIMIT 1');
            $stmt->execute(['id' => $id]);
            $row = $stmt->fetch();
            return $row !== false ? $row : null;
        }
    }

    public function findByEmail(string $email): ?array
    {
        try {
            $stmt = $this->db()->prepare('SELECT id, name, email, password_hash, password_changed_at FROM sellers WHERE email = :email LIMIT 1');
            $stmt->execute(['email' => $email]);
            $row = $stmt->fetch();
            return $row !== false ? $row : null;
        } catch (\PDOException $e) {
            // Fallback for older DB schemas where password_changed_at doesn't exist
            $stmt = $this->db()->prepare('SELECT id, name, email, password_hash FROM sellers WHERE email = :email LIMIT 1');
            $stmt->execute(['email' => $email]);
            $row = $stmt->fetch();
            return $row !== false ? $row : null;
        }
    }

    public function create(string $name, string $email, ?string $passwordHash): int
    {
        $stmt = $this->db()->prepare('INSERT INTO sellers (name, email, password_hash, created_at) VALUES (:name, :email, :password_hash, NOW())');
        $stmt->execute([
            'name' => $name,
            'email' => $email,
            'password_hash' => $passwordHash,
        ]);

        return (int)$this->db()->lastInsertId();
    }

    public function all(): array
    {
        $stmt = $this->db()->query('SELECT id, name, email, created_at FROM sellers ORDER BY id DESC');
        return $stmt->fetchAll();
    }

    public function update(int $id, string $name, string $email, ?string $passwordHash): void
    {
        if ($passwordHash !== null) {
            $stmt = $this->db()->prepare('UPDATE sellers SET name = :name, email = :email, password_hash = :password_hash, password_changed_at = NOW() WHERE id = :id');
            $stmt->execute([
                'id' => $id,
                'name' => $name,
                'email' => $email,
                'password_hash' => $passwordHash,
            ]);
        } else {
            $stmt = $this->db()->prepare('UPDATE sellers SET name = :name, email = :email WHERE id = :id');
            $stmt->execute([
                'id' => $id,
                'name' => $name,
                'email' => $email,
            ]);
        }
    }

    public function delete(int $id): void
    {
        $stmt = $this->db()->prepare('DELETE FROM sellers WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    public function updatePassword(int $id, string $passwordHash): void
    {
        $stmt = $this->db()->prepare('UPDATE sellers SET password_hash = :password_hash, password_changed_at = NOW() WHERE id = :id');
        $stmt->execute([
            'password_hash' => $passwordHash,
            'id' => $id,
        ]);
    }
}
