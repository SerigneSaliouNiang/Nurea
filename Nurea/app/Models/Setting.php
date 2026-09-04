<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use PDO;

final class Setting extends Model
{
    public function all(): array
    {
        $stmt = $this->db()->query('SELECT setting_key, setting_value FROM settings');
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result = [];
        foreach ($rows as $row) {
            $result[(string)$row['setting_key']] = (string)$row['setting_value'];
        }
        return $result;
    }

    public function set(string $key, string $value): void
    {
        $stmt = $this->db()->prepare(
            'INSERT INTO settings (setting_key, setting_value, created_at, updated_at)
             VALUES (:key, :value, NOW(), NOW())
             ON DUPLICATE KEY UPDATE setting_value = :value_update, updated_at = NOW()'
        );

        $stmt->execute([
            'key' => $key,
            'value' => $value,
            'value_update' => $value,
        ]);
    }
}
