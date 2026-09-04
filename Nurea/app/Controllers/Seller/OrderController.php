<?php

declare(strict_types=1);

namespace App\Controllers\Seller;

use App\Core\Controller;

final class OrderController extends Controller
{
    public function index(): void
    {
        if (empty($_SESSION['seller_id'])) {
            $this->redirect('/seller/login');
        }

        $sellerId = (int)$_SESSION['seller_id'];
        $db = \App\Core\Database::pdo();

        $stmt = $db->prepare('SELECT DISTINCT o.id, o.total_amount, o.status, o.created_at FROM orders o JOIN order_details od ON od.order_id = o.id JOIN products p ON od.product_id = p.id WHERE p.seller_id = :id ORDER BY o.created_at DESC');
        $stmt->execute(['id' => $sellerId]);
        $orders = $stmt->fetchAll();

        $this->view('seller.orders.index', ['orders' => $orders]);
    }

    public function show(): void
    {
        if (empty($_SESSION['seller_id'])) {
            $this->redirect('/seller/login');
        }

        $sellerId = (int)$_SESSION['seller_id'];
        $orderId = (int)($_GET['id'] ?? 0);
        if ($orderId <= 0) {
            $this->redirect('/seller/orders');
        }

        $db = \App\Core\Database::pdo();

        // fetch order
        $stmt = $db->prepare('SELECT * FROM orders WHERE id = :id');
        $stmt->execute(['id' => $orderId]);
        $order = $stmt->fetch();

        // fetch only order_details related to this seller
        $stmt = $db->prepare('SELECT od.*, p.name as product_name FROM order_details od JOIN products p ON od.product_id = p.id WHERE od.order_id = :order_id AND p.seller_id = :seller_id');
        $stmt->execute(['order_id' => $orderId, 'seller_id' => $sellerId]);
        $lines = $stmt->fetchAll();

        $this->view('seller.orders.show', ['order' => $order, 'lines' => $lines]);
    }

    public function updateStatus(): void
    {
        if (empty($_SESSION['seller_id'])) {
            $this->redirect('/seller/login');
        }

        $sellerId = (int)$_SESSION['seller_id'];
        $detailId = (int)($_POST['detail_id'] ?? 0);
        $newStatus = (string)($_POST['status'] ?? '');

        if ($detailId <= 0 || $newStatus === '') {
            $_SESSION['flash_error'] = 'Paramètres invalides.';
            $this->redirect('/seller/orders');
        }

        $db = \App\Core\Database::pdo();

        // verify that detail belongs to seller
        $stmt = $db->prepare('SELECT od.id FROM order_details od JOIN products p ON od.product_id = p.id WHERE od.id = :id AND p.seller_id = :seller_id');
        $stmt->execute(['id' => $detailId, 'seller_id' => $sellerId]);
        $ok = $stmt->fetch();
        if (!$ok) {
            $_SESSION['flash_error'] = 'Droit refusé.';
            $this->redirect('/seller/orders');
        }

        $stmt = $db->prepare('UPDATE order_details SET delivery_status = :status WHERE id = :id');
        $stmt->execute(['status' => $newStatus, 'id' => $detailId]);

        // log event
        try {
            $orderEventModel = new \App\Models\OrderEvent();
            $orderEventModel->create($orderId, $detailId, 'seller', $sellerId, $newStatus, null);
        } catch (\Throwable $e) {
            // non-blocking: don't stop status update if logging fails
        }

        // update overall order status: simple rules
        $stmt = $db->prepare('SELECT order_id FROM order_details WHERE id = :id');
        $stmt->execute(['id' => $detailId]);
        $orderId = (int)$stmt->fetchColumn();

        // If any line is in en_cours_de_livraison -> mark order as expediee
        $stmt = $db->prepare('SELECT COUNT(*) FROM order_details od JOIN products p ON od.product_id = p.id WHERE od.order_id = :order_id AND od.delivery_status = "en_cours_de_livraison" AND p.seller_id = :seller_id');
        $stmt->execute(['order_id' => $orderId, 'seller_id' => $sellerId]);
        $count = (int)$stmt->fetchColumn();
        if ($count > 0) {
            $db->prepare('UPDATE orders SET status = "expediee" WHERE id = :id')->execute(['id' => $orderId]);
        }

        // If all order_details for this order are 'valide' -> mark order as 'livree'
        $stmt = $db->prepare('SELECT COUNT(*) FROM order_details WHERE order_id = :order_id AND delivery_status != "valide"');
        $stmt->execute(['order_id' => $orderId]);
        $notValide = (int)$stmt->fetchColumn();
        if ($notValide === 0) {
            $db->prepare('UPDATE orders SET status = "livree" WHERE id = :id')->execute(['id' => $orderId]);
        }

        $_SESSION['flash_success'] = 'Statut mis à jour.';
        $this->redirect('/seller/orders/show?id=' . $orderId);
    }

    public function markPaid(): void
    {
        if (empty($_SESSION['seller_id'])) {
            $this->redirect('/seller/login');
        }

        $sellerId = (int)$_SESSION['seller_id'];
        $orderId = (int)($_POST['order_id'] ?? 0);
        $paidAmount = (float)($_POST['paid_amount'] ?? 0);

        if ($orderId <= 0 || $paidAmount <= 0) {
            $_SESSION['flash_error'] = 'Paramètres invalides.';
            $this->redirect('/seller/orders');
        }

        $db = \App\Core\Database::pdo();

        // ensure seller is related to this order
        $stmt = $db->prepare('SELECT COUNT(*) FROM order_details od JOIN products p ON od.product_id = p.id WHERE od.order_id = :order_id AND p.seller_id = :seller_id');
        $stmt->execute(['order_id' => $orderId, 'seller_id' => $sellerId]);
        $count = (int)$stmt->fetchColumn();
        if ($count === 0) {
            $_SESSION['flash_error'] = 'Droit refusé.';
            $this->redirect('/seller/orders');
        }

        $stmt = $db->prepare('UPDATE orders SET paid_at = NOW(), paid_amount = :paid_amount WHERE id = :id');
        $stmt->execute(['paid_amount' => $paidAmount, 'id' => $orderId]);

        $_SESSION['flash_success'] = 'Paiement enregistré.';
        $this->redirect('/seller/orders/show?id=' . $orderId);
    }
}
