<?php

declare(strict_types=1);

namespace App\Core;

final class Router
{
    /** @var array<string, array<string, string>> */
    private array $routes = [
        'GET' => [],
        'POST' => [],
    ];

    public function get(string $path, string $handler): void
    {
        $this->routes['GET'][$this->normalize($path)] = $handler;
    }

    public function post(string $path, string $handler): void
    {
        $this->routes['POST'][$this->normalize($path)] = $handler;
    }

    public function dispatch(string $method, string $requestUri): void
    {
        $method = strtoupper($method);

        // CSRF Protection for POST requests
        if ($method === 'POST') {
            $token = $_POST['csrf_token'] ?? null;
            if (!\App\Core\Csrf::verify($token)) {
                http_response_code(403);
                echo '403 Forbidden - CSRF token mismatch';
                return;
            }
        }

        $path = parse_url($requestUri, PHP_URL_PATH);
        $path = is_string($path) ? $this->normalize($path) : '/';

        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $basePath = is_string($scriptName) ? rtrim(str_replace('\\', '/', dirname($scriptName)), '/') : '';
        if ($basePath === '/' || $basePath === '.') {
            $basePath = '';
        }

        if ($basePath !== '' && $path !== $basePath && str_starts_with($path, $basePath . '/')) {
            $path = substr($path, strlen($basePath));
            $path = $this->normalize($path);
        } elseif ($basePath !== '' && $path === $basePath) {
            $path = '/';
        }

        $handler = $this->routes[$method][$path] ?? null;
        if ($handler === null) {
            http_response_code(404);
            echo '404 Not Found';
            return;
        }

        [$controller, $action] = explode('@', $handler, 2);
        $controllerClass = 'App\\Controllers\\' . $controller;

        if (!class_exists($controllerClass)) {
            http_response_code(500);
            echo 'Controller not found';
            return;
        }

        $instance = new $controllerClass();
        if (!method_exists($instance, $action)) {
            http_response_code(500);
            echo 'Action not found';
            return;
        }

        $instance->{$action}();
    }

    private function normalize(string $path): string
    {
        $path = '/' . ltrim($path, '/');
        $path = rtrim($path, '/');
        return $path === '' ? '/' : $path;
    }
}
