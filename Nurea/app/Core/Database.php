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
        $port = (string)($db['port'] ?? '3306');
        $name = (string)($db['name'] ?? '');
        $charset = (string)($db['charset'] ?? 'utf8mb4');

        // Ajout du port dans le DSN
        $dsn = "mysql:host={$host};port={$port};dbname={$name};charset={$charset}";

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        // Option SSL si requise par Aiven (non stricte sur les certificats locaux)
        if (defined('PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT')) {
            $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
        }

        $pdo = new PDO(
            $dsn,
            (string)($db['user'] ?? 'root'),
            (string)($db['pass'] ?? ''),
            $options
        );

        self::$pdo = $pdo;
        return $pdo;
    }
}