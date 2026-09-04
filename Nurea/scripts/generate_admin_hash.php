<?php

declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    echo "Run this script from CLI: php generate_admin_hash.php <password>\n";
    exit(1);
}

$password = $argv[1] ?? null;
if ($password === null) {
    echo "Usage: php generate_admin_hash.php <password>\n";
    exit(1);
}

echo password_hash($password, PASSWORD_DEFAULT) . PHP_EOL;
