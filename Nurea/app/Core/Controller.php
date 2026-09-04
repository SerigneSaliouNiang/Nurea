<?php

declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    protected function view(string $view, array $data = []): void
    {
        View::render($view, $data);
    }

    protected function redirect(string $path): void
    {
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $basePath = is_string($scriptName) ? rtrim(str_replace('\\', '/', dirname($scriptName)), '/') : '';
        if ($basePath === '/' || $basePath === '.') {
            $basePath = '';
        }

        if ($basePath !== '' && str_starts_with($path, '/') && !str_starts_with($path, $basePath . '/')) {
            $path = $basePath . $path;
        }

        header('Location: ' . $path);
        exit;
    }

    protected function requireAdmin(): void
    {
        if (empty($_SESSION['admin_id'])) {
            $this->redirect('/admin/login');
        }
    }
}
