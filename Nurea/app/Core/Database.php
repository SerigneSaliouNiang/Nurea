<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

final class Database
{
    private static ?PDO $pdo = null;

    public static function pdo(): PDO
    {
        if (self::$pdo instanceof PDO) {
            return self::$pdo;
        }

        $config = Container::get('config');
        if (!is_array($config) || !isset($config['db'])) {
            throw new PDOException('Database configuration missing.');
        }

        $db = $config['db'];
        $host = (string)($db['host'] ?? '127.0.0.1');
        $name = (string)($db['name'] ?? '');
        $charset = (string)($db['charset'] ?? 'utf8mb4');

        $dsn = "mysql:host={$host};dbname={$name};charset={$charset}";

        $pdo = new PDO(
            $dsn,
            (string)($db['user'] ?? 'root'),
            (string)($db['pass'] ?? ''),
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );

        self::$pdo = $pdo;
        return $pdo;
    }
}
