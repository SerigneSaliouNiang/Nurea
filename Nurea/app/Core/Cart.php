<?php

declare(strict_types=1);

namespace App\Core;

final class Cart
{
    private const SESSION_KEY = 'cart_items';

    /** @return array<int,int> */
    public static function items(): array
    {
        $items = $_SESSION[self::SESSION_KEY] ?? [];
        if (!is_array($items)) {
            return [];
        }

        $clean = [];
        foreach ($items as $productId => $qty) {
            $pid = (int)$productId;
            $q = (int)$qty;
            if ($pid > 0 && $q > 0) {
                $clean[$pid] = $q;
            }
        }

        return $clean;
    }

    public static function add(int $productId, int $qty = 1): void
    {
        if ($productId <= 0 || $qty <= 0) {
            return;
        }

        $items = self::items();
        $items[$productId] = ($items[$productId] ?? 0) + $qty;
        $_SESSION[self::SESSION_KEY] = $items;
    }

    public static function setQty(int $productId, int $qty): void
    {
        if ($productId <= 0) {
            return;
        }

        $items = self::items();
        if ($qty <= 0) {
            unset($items[$productId]);
        } else {
            $items[$productId] = $qty;
        }
        $_SESSION[self::SESSION_KEY] = $items;
    }

    public static function remove(int $productId): void
    {
        $items = self::items();
        unset($items[$productId]);
        $_SESSION[self::SESSION_KEY] = $items;
    }

    public static function clear(): void
    {
        unset($_SESSION[self::SESSION_KEY]);
    }
}
