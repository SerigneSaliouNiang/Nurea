<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Order;

final class DashboardController extends Controller
{
    public function index(): void
    {
        $this->requireAdmin();

        $orderModel = new Order();

        $this->view('admin.dashboard.index', [
            'salesToday' => $orderModel->sumSalesToday(),
            'ordersMonth' => $orderModel->countOrdersMonth(),
            'globalRevenue' => $orderModel->getGlobalRevenue(),
            'orderStatusCounts' => $orderModel->countByStatus(),
            'statusLabels' => $orderModel->getStatuses(),
            'topProducts' => $orderModel->topSellingProducts(),
        ]);
    }
}
