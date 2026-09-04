<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class OrderEvent extends Model
{
    public function create(int $orderId, ?int $detailId, string $actorType, ?int $actorId, string $status, ?string $note = null): int
    {
        $stmt = $this->db()->prepare('INSERT INTO order_events (order_id, detail_id, actor_type, actor_id, status, note, created_at) VALUES (:order_id, :detail_id, :actor_type, :actor_id, :status, :note, NOW())');
        $stmt->execute([
            'order_id' => $orderId,
            'detail_id' => $detailId,
            'actor_type' => $actorType,
            'actor_id' => $actorId,
            'status' => $status,
            'note' => $note,
        ]);

        return (int)$this->db()->lastInsertId();
    }

    public function findByOrderId(int $orderId): array
    {
        $stmt = $this->db()->prepare('SELECT oe.*, s.name as seller_name, a.email as admin_email FROM order_events oe LEFT JOIN sellers s ON (oe.actor_type = "seller" AND oe.actor_id = s.id) LEFT JOIN admins a ON (oe.actor_type = "admin" AND oe.actor_id = a.id) WHERE oe.order_id = :order_id ORDER BY oe.created_at ASC');
        $stmt->execute(['order_id' => $orderId]);
        return $stmt->fetchAll();
    }
}