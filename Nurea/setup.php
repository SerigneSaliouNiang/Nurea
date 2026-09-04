<?php

declare(strict_types=1);

require_once __DIR__ . '/app/Core/Autoload.php';

use App\Core\Database;
use App\Models\Admin;

$config = \App\Core\Container::get('config');
$appName = is_array($config) ? ($config['app']['name'] ?? 'App') : 'App';

$errors = [];
$success = null;

$email = trim((string)($_POST['email'] ?? 'admin@nurea.local'));
$password = (string)($_POST['password'] ?? 'admin');

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email invalide.';
    }
    if (strlen($password) < 4) {
        $errors[] = 'Mot de passe trop court (min 4).';
    }

    if (empty($errors)) {
        try {
            $pdo = Database::pdo();
            $schemaPath = __DIR__ . '/database/schema.sql';
            $sql = is_file($schemaPath) ? (string)file_get_contents($schemaPath) : '';

            if (trim($sql) === '') {
                throw new RuntimeException('schema.sql introuvable ou vide.');
            }

            $statements = array_filter(array_map('trim', preg_split('/;\s*\r?\n/', $sql) ?: []));
            foreach ($statements as $stmt) {
                $pdo->exec($stmt);
            }

            $adminModel = new Admin();
            $existing = $adminModel->findByEmail($email);
            if ($existing === null) {
                $hash = password_hash($password, PASSWORD_BCRYPT);
                $adminModel->create($email, (string)$hash);
                $success = 'Tables créées et admin initial ajouté.';
            } else {
                $hash = password_hash($password, PASSWORD_BCRYPT);
                $adminModel->updatePasswordByEmail($email, (string)$hash);
                $success = 'Tables créées (si besoin). Mot de passe admin réinitialisé pour cet email.';
            }
        } catch (Throwable $e) {
            $errors[] = $e->getMessage();
        }
    }
}

?><!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars((string)$appName) ?> - Setup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <?php
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
    $basePath = is_string($scriptName) ? rtrim(str_replace('\\', '/', dirname($scriptName)), '/') : '';
    if ($basePath === '/' || $basePath === '.') {
        $basePath = '';
    }
    ?>
    <link href="<?= htmlspecialchars($basePath) ?>/assets/css/app.css" rel="stylesheet">
</head>
<body class="body-light">
<div class="container py-5">
    <h1 class="h4" style="color:#d4af37;">Setup - <?= htmlspecialchars((string)$appName) ?></h1>
    <p class="text-secondary">Crée les tables MySQL et ajoute un admin initial.</p>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $err): ?>
                <div><?= htmlspecialchars((string)$err) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars((string)$success) ?></div>
        <div class="mt-3">
            <a class="btn btn-warning btn-sm" href="<?= htmlspecialchars($basePath) ?>/admin/login">Aller au login admin</a>
        </div>
    <?php endif; ?>

    <div class="card mt-3" style="border:1px solid rgba(212,175,55,.25)">
        <div class="card-body p-4">
            <form method="post" class="d-grid gap-3">
                <div>
                    <label class="form-label">Email admin</label>
                    <input class="form-control" type="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
                </div>
                <div>
                    <label class="form-label">Mot de passe admin</label>
                    <input class="form-control" type="text" name="password" value="<?= htmlspecialchars($password) ?>" required>
                </div>
                <button class="btn btn-warning" type="submit">Créer les tables + admin</button>
            </form>
        </div>
    </div>

    <div class="mt-3 text-secondary" style="font-size:.9rem">
        Assure-toi d'avoir créé la base MySQL <code>nurea</code> (par défaut) et d'avoir mis les identifiants dans <code>config/config.php</code>.
    </div>
</div>
</body>
</html>
