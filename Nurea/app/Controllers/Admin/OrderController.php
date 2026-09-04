<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Order;

final class OrderController extends Controller
{
    public function index(): void
    {
        $this->requireAdmin();

        $status = trim((string)($_GET['status'] ?? ''));
        $from = trim((string)($_GET['from'] ?? ''));
        $to = trim((string)($_GET['to'] ?? ''));

        $orderModel = new Order();
        $orders = $orderModel->list($status !== '' ? $status : null, $from !== '' ? $from : null, $to !== '' ? $to : null);

        $this->view('admin.orders.index', [
            'orders' => $orders,
            'statuses' => $orderModel->getStatuses(),
            'selectedStatus' => $status,
            'from' => $from,
            'to' => $to,
        ]);
    }

    public function show(): void
    {
        $this->requireAdmin();

        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['flash_error'] = 'Commande introuvable.';
            $this->redirect('/admin/orders');
        }

        $orderModel = new Order();
        $order = $orderModel->find($id);
        if ($order === null) {
            $_SESSION['flash_error'] = 'Commande introuvable.';
            $this->redirect('/admin/orders');
        }

        $this->view('admin.orders.show', [
            'order' => $order,
            'statuses' => $orderModel->getStatuses(),
        ]);
    }

    public function updateStatus(): void
    {
        $this->requireAdmin();

        $id = (int)($_POST['id'] ?? 0);
        $status = trim((string)($_POST['status'] ?? ''));
        if ($id <= 0 || $status === '') {
            $_SESSION['flash_error'] = 'Données invalides.';
            $this->redirect('/admin/orders');
        }

        $orderModel = new Order();
        $orderModel->updateStatus($id, $status);

        // log admin event
        try {
            $orderEvent = new \App\Models\OrderEvent();
            $orderEvent->create($id, null, 'admin', !empty($_SESSION['admin_id']) ? (int)$_SESSION['admin_id'] : null, $status, null);
        } catch (\Throwable $e) {
            // ignore logging errors
        }

        $_SESSION['flash_success'] = 'Statut mis à jour.';
        $this->redirect('/admin/orders/show?id=' . $id);
    }

    public function exportPayments(): void
    {
        $this->requireAdmin();

        $from = trim((string)($_GET['from'] ?? ''));
        $to = trim((string)($_GET['to'] ?? ''));

        $db = \App\Core\Database::pdo();

        $sql = 'SELECT o.id as order_id, s.id as seller_id, s.name as seller_name, s.email as seller_email, SUM(od.line_total) as amount, o.paid_at FROM orders o JOIN order_details od ON od.order_id = o.id JOIN products p ON od.product_id = p.id JOIN sellers s ON p.seller_id = s.id WHERE o.paid_at IS NOT NULL';
        $params = [];
        if ($from !== '') {
            $sql .= ' AND o.paid_at >= :from';
            $params['from'] = $from . ' 00:00:00';
        }
        if ($to !== '') {
            $sql .= ' AND o.paid_at <= :to';
            $params['to'] = $to . ' 23:59:59';
        }
        $sql .= ' GROUP BY o.id, s.id ORDER BY o.paid_at DESC';

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll();

        // output CSV
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=payments_export_' . date('Ymd_His') . '.csv');
        $out = fopen('php://output', 'w');
        fputcsv($out, ['order_id', 'seller_id', 'seller_name', 'seller_email', 'amount', 'paid_at']);
        foreach ($rows as $r) {
            fputcsv($out, [$r['order_id'], $r['seller_id'], $r['seller_name'], $r['seller_email'], $r['amount'], $r['paid_at']]);
        }
        fclose($out);
        exit;
    }
}
