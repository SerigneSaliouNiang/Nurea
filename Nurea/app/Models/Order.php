<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use PDO;

final class Order extends Model
{
    private const STATUSES = [
        'en_attente' => 'En attente',
        'validee' => 'Validée',
        'expediee' => 'Expédiée',
        'livree' => 'Livrée',
    ];

    public function getStatuses(): array
    {
        return self::STATUSES;
    }

    public function sumSalesToday(): float
    {
        $stmt = $this->db()->query("SELECT COALESCE(SUM(total_amount), 0) AS total FROM orders WHERE DATE(created_at) = DATE(NOW())");
        return (float)($stmt->fetchColumn() ?: 0);
    }

    public function countOrdersMonth(): int
    {
        $stmt = $this->db()->query("SELECT COUNT(*) FROM orders WHERE MONTH(created_at) = MONTH(NOW()) AND YEAR(created_at) = YEAR(NOW())");
        return (int)$stmt->fetchColumn();
    }

    public function getGlobalRevenue(): float
    {
        $stmt = $this->db()->query('SELECT COALESCE(SUM(total_amount), 0) AS total FROM orders');
        return (float)($stmt->fetchColumn() ?: 0);
    }

    public function countByStatus(): array
    {
        $stmt = $this->db()->query('SELECT status, COUNT(*) AS count FROM orders GROUP BY status');
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result = [];
        foreach ($rows as $row) {
            $status = (string)($row['status'] ?? '');
            $result[$status] = (int)($row['count'] ?? 0);
        }

        foreach (self::STATUSES as $status => $label) {
            if (!array_key_exists($status, $result)) {
                $result[$status] = 0;
            }
        }

        return $result;
    }

    public function topSellingProducts(int $limit = 5): array
    {
        $stmt = $this->db()->prepare(
            'SELECT product_name, SUM(quantity) AS total_quantity, SUM(line_total) AS total_revenue
             FROM order_details
             GROUP BY product_name
             ORDER BY total_quantity DESC
             LIMIT :limit'
        );
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function list(?string $status, ?string $from, ?string $to): array
    {
        $sql = 'SELECT o.id, o.guest_nom, o.guest_prenom, o.guest_telephone, o.guest_adresse, o.total_amount, o.status, o.created_at, COUNT(od.id) AS items_count
                FROM orders o
                LEFT JOIN order_details od ON od.order_id = o.id';
        $conditions = [];
        $params = [];

        if ($status !== null && isset(self::STATUSES[$status])) {
            $conditions[] = 'o.status = :status';
            $params['status'] = $status;
        }

        if ($from !== null && $from !== '') {
            $conditions[] = 'o.created_at >= :from_date';
            $params['from_date'] = $from . ' 00:00:00';
        }

        if ($to !== null && $to !== '') {
            $conditions[] = 'o.created_at <= :to_date';
            $params['to_date'] = $to . ' 23:59:59';
        }

        if (!empty($conditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $sql .= ' GROUP BY o.id ORDER BY o.created_at DESC';

        $stmt = $this->db()->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db()->prepare(
            'SELECT id, user_id, guest_nom, guest_prenom, guest_telephone, guest_adresse, total_amount, status, payment_method, created_at
             FROM orders
             WHERE id = :id
             LIMIT 1'
        );
        $stmt->execute(['id' => $id]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($order === false) {
            return null;
        }

        $detailsStmt = $this->db()->prepare(
            'SELECT id, product_id, product_name, unit_price, quantity, line_total
             FROM order_details
             WHERE order_id = :order_id'
        );
        $detailsStmt->execute(['order_id' => $id]);
        $order['details'] = $detailsStmt->fetchAll(PDO::FETCH_ASSOC);

        return $order;
    }

    public function updateStatus(int $id, string $status): void
    {
        if (!isset(self::STATUSES[$status])) {
            return;
        }

        $stmt = $this->db()->prepare('UPDATE orders SET status = :status WHERE id = :id');
        $stmt->execute(['id' => $id, 'status' => $status]);
    }
}
