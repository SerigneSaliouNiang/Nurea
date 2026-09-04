<?php

declare(strict_types=1);

return [
    'app' => [
        'name' => $_ENV['APP_NAME'] ?? 'NUREA',
        'base_url' => '',
        'session_name' => $_ENV['APP_SESSION_NAME'] ?? 'nurea_session',
        'debug' => ($_ENV['APP_DEBUG'] ?? 'false') === 'true',
    ],
    'db' => [
    
    'host' => $_ENV['DB_HOST'] ?? 'mysql-32167c14-kakashii19-18.a.aivencloud.com',
    'port' => $_ENV['DB_PORT'] ?? '20147',
    'name' => $_ENV['DB_NAME'] ?? 'defaultdb',
    'user' => $_ENV['DB_USER'] ?? 'avnadmin',
    'pass' => $_ENV['DB_PASS'] ?? getenv('DB_PASS') ?? '',
    'charset' => 'utf8mb4',

    ],
    'mail' => [
        'driver' => 'mailtrap',
        'host' => '',
        'port' => 2525,
        'username' => '',
        'password' => '',
        'encryption' => 'tls',
        'from_email' => 'no-reply@nurea.local',
        'from_name' => 'NUREA',
    ],
];
