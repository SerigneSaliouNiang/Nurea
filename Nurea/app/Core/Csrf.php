<?php

declare(strict_types=1);

namespace App\Core;

final class Csrf
{
    private const SESSION_KEY = 'csrf_token';

    public static function generate(): string
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            return '';
        }

        if (empty($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = bin2hex(random_bytes(32));
        }

        return $_SESSION[self::SESSION_KEY];
    }

    public static function verify(?string $token): bool
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            return false;
        }

        if (empty($token) || empty($_SESSION[self::SESSION_KEY])) {
            return false;
        }

        return hash_equals($_SESSION[self::SESSION_KEY], $token);
    }

    public static function field(): string
    {
        $token = self::generate();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }
}