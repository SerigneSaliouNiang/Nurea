<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Category extends Model
{
    public function all(): array
    {
        $stmt = $this->db()->query('SELECT id, name, created_at FROM categories ORDER BY id DESC');
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db()->prepare('SELECT id, name, created_at FROM categories WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row !== false ? $row : null;
    }

    public function create(string $name): int
    {
        $stmt = $this->db()->prepare('INSERT INTO categories (name, created_at) VALUES (:name, NOW())');
        $stmt->execute(['name' => $name]);
        return (int)$this->db()->lastInsertId();
    }

    public function update(int $id, string $name): void
    {
        $stmt = $this->db()->prepare('UPDATE categories SET name = :name WHERE id = :id');
        $stmt->execute(['id' => $id, 'name' => $name]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->db()->prepare('DELETE FROM categories WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}
