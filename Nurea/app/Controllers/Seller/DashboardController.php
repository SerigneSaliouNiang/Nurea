<?php

declare(strict_types=1);

namespace App\Controllers\Seller;

use App\Core\Controller;

final class DashboardController extends Controller
{
    public function index(): void
    {
        if (empty($_SESSION['seller_id'])) {
            $this->redirect('/seller/login');
        }

        $sellerId = (int)$_SESSION['seller_id'];
        $db = \App\Core\Database::pdo();

        // products count
        $stmt = $db->prepare('SELECT COUNT(*) as cnt FROM products WHERE seller_id = :id');
        $stmt->execute(['id' => $sellerId]);
        $productCount = (int)($stmt->fetchColumn() ?? 0);

        // orders count (distinct orders containing seller products)
        $stmt = $db->prepare('SELECT COUNT(DISTINCT o.id) as cnt FROM orders o JOIN order_details od ON od.order_id = o.id JOIN products p ON od.product_id = p.id WHERE p.seller_id = :id');
        $stmt->execute(['id' => $sellerId]);
        $ordersCount = (int)($stmt->fetchColumn() ?? 0);

        // recent orders
        $stmt = $db->prepare('SELECT o.id, o.total_amount, o.created_at FROM orders o JOIN order_details od ON od.order_id = o.id JOIN products p ON od.product_id = p.id WHERE p.seller_id = :id GROUP BY o.id ORDER BY o.created_at DESC LIMIT 8');
        $stmt->execute(['id' => $sellerId]);
        $recentOrders = $stmt->fetchAll();

        $this->view('seller.dashboard.index', [
            'productCount' => $productCount,
            'ordersCount' => $ordersCount,
            'recentOrders' => $recentOrders,
        ]);
    }
}
