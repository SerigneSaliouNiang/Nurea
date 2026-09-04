<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Product extends Model
{
    public function allWithCategory(): array
    {
        $sql = "SELECT p.id, p.name, p.price, p.stock, p.image, p.category_id, c.name AS category_name
                FROM products p
                LEFT JOIN categories c ON c.id = p.category_id
                ORDER BY p.id DESC";
        $stmt = $this->db()->query($sql);
        return $stmt->fetchAll();
    }

    public function listForCatalog(?string $q, ?int $categoryId, string $sort): array
    {
        $where = [];
        $params = [];

        if ($q !== null && trim($q) !== '') {
            $where[] = 'p.name LIKE :q';
            $params['q'] = '%' . trim($q) . '%';
        }

        if ($categoryId !== null && $categoryId > 0) {
            $where[] = 'p.category_id = :category_id';
            $params['category_id'] = $categoryId;
        }

        $orderBy = 'p.id DESC';
        if ($sort === 'price_asc') {
            $orderBy = 'p.price ASC';
        } elseif ($sort === 'price_desc') {
            $orderBy = 'p.price DESC';
        } elseif ($sort === 'name_asc') {
            $orderBy = 'p.name ASC';
        }

        $sql = "SELECT p.id, p.name, p.price, p.stock, p.image, p.category_id, c.name AS category_name
                FROM products p
                LEFT JOIN categories c ON c.id = p.category_id";

        if (!empty($where)) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        $sql .= ' ORDER BY ' . $orderBy;

        $stmt = $this->db()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function latest(int $limit = 4): array
    {
        $stmt = $this->db()->prepare(
            'SELECT p.id, p.name, p.price, p.stock, p.image, p.category_id, c.name AS category_name
             FROM products p
             LEFT JOIN categories c ON c.id = p.category_id
             ORDER BY p.created_at DESC
             LIMIT :limit'
        );
        $stmt->bindValue('limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findWithCategory(int $id): ?array
    {
        $sql = "SELECT p.id, p.category_id, p.name, p.description, p.price, p.stock, p.image, c.name AS category_name
                FROM products p
                LEFT JOIN categories c ON c.id = p.category_id
                WHERE p.id = :id
                LIMIT 1";
        $stmt = $this->db()->prepare($sql);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row !== false ? $row : null;
    }

    public function findManyByIds(array $ids): array
    {
        $ids = array_values(array_unique(array_filter(array_map(static fn ($v): int => (int)$v, $ids), static fn (int $v): bool => $v > 0)));
        if (empty($ids)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = 'SELECT id, category_id, name, description, price, stock, image FROM products WHERE id IN (' . $placeholders . ')';
        $stmt = $this->db()->prepare($sql);
        $stmt->execute($ids);
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db()->prepare('SELECT id, category_id, name, description, price, stock, image FROM products WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row !== false ? $row : null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db()->prepare('INSERT INTO products (category_id, name, description, price, stock, image, created_at) VALUES (:category_id, :name, :description, :price, :stock, :image, NOW())');
        $stmt->execute([
            'category_id' => $data['category_id'],
            'name' => $data['name'],
            'description' => $data['description'],
            'price' => $data['price'],
            'stock' => $data['stock'],
            'image' => $data['image'],
        ]);

        return (int)$this->db()->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->db()->prepare('UPDATE products SET category_id = :category_id, name = :name, description = :description, price = :price, stock = :stock, image = :image WHERE id = :id');
        $stmt->execute([
            'id' => $id,
            'category_id' => $data['category_id'],
            'name' => $data['name'],
            'description' => $data['description'],
            'price' => $data['price'],
            'stock' => $data['stock'],
            'image' => $data['image'],
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->db()->prepare('DELETE FROM products WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}
