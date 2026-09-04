<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Cart;
use App\Core\Controller;
use App\Models\Product;

final class CartController extends Controller
{
    public function index(): void
    {
        $items = Cart::items();
        $productModel = new Product();
        $products = $productModel->findManyByIds(array_keys($items));

        $lines = [];
        $total = 0.0;

        foreach ($products as $p) {
            $pid = (int)$p['id'];
            $qty = (int)($items[$pid] ?? 0);
            $unit = (float)$p['price'];
            $lineTotal = $unit * $qty;
            $total += $lineTotal;

            $lines[] = [
                'product' => $p,
                'qty' => $qty,
                'line_total' => $lineTotal,
            ];
        }

        $this->view('cart.index', [
            'lines' => $lines,
            'total' => $total,
        ]);
    }

    public function add(): void
    {
        $productId = (int)($_POST['product_id'] ?? 0);
        $qty = (int)($_POST['qty'] ?? 1);
        if ($qty <= 0) {
            $qty = 1;
        }

        if ($productId > 0) {
            Cart::add($productId, $qty);
            $_SESSION['flash_success'] = 'Produit ajouté au panier.';
        }

        $redirect = (string)($_POST['redirect'] ?? '/cart');
        $this->redirect($redirect);
    }

    public function update(): void
    {
        $items = $_POST['items'] ?? [];
        if (is_array($items)) {
            foreach ($items as $productId => $qty) {
                Cart::setQty((int)$productId, (int)$qty);
            }
        }

        $_SESSION['flash_success'] = 'Panier mis à jour.';
        $this->redirect('/cart');
    }

    public function remove(): void
    {
        $productId = (int)($_POST['product_id'] ?? 0);
        if ($productId > 0) {
            Cart::remove($productId);
            $_SESSION['flash_success'] = 'Produit supprimé du panier.';
        }
        $this->redirect('/cart');
    }
}
