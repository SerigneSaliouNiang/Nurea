<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Cart;
use App\Core\Controller;
use App\Core\Database;
use App\Models\Product;

final class CheckoutController extends Controller
{
    public function index(): void
    {
        $items = Cart::items();
        if (empty($items)) {
            $_SESSION['flash_error'] = 'Votre panier est vide.';
            $this->redirect('/products');
        }

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

        $this->view('checkout.index', [
            'lines' => $lines,
            'total' => $total,
        ]);
    }

    public function placeOrder(): void
    {
        $items = Cart::items();
        if (empty($items)) {
            $_SESSION['flash_error'] = 'Votre panier est vide.';
            $this->redirect('/products');
        }

        $nom = trim((string)($_POST['nom'] ?? ''));
        $prenom = trim((string)($_POST['prenom'] ?? ''));
        $telephone = trim((string)($_POST['telephone'] ?? ''));
        $adresse = trim((string)($_POST['adresse'] ?? ''));

        if ($nom === '' || $prenom === '' || $telephone === '' || $adresse === '') {
            $_SESSION['flash_error'] = 'Tous les champs sont obligatoires.';
            $this->redirect('/checkout');
        }

        $productModel = new Product();
        $products = $productModel->findManyByIds(array_keys($items));
        if (count($products) !== count($items)) {
            $_SESSION['flash_error'] = 'Certains produits du panier sont introuvables.';
            $this->redirect('/cart');
        }

        foreach ($products as $p) {
            $pid = (int)$p['id'];
            $qty = (int)($items[$pid] ?? 0);
            $stock = (int)$p['stock'];
            if ($qty <= 0) {
                $_SESSION['flash_error'] = 'Quantité invalide.';
                $this->redirect('/cart');
            }
            if ($stock < $qty) {
                $_SESSION['flash_error'] = 'Stock insuffisant pour: ' . (string)$p['name'];
                $this->redirect('/cart');
            }
        }

        $pdo = Database::pdo();
        try {
            $pdo->beginTransaction();

            $total = 0.0;
            foreach ($products as $p) {
                $pid = (int)$p['id'];
                $qty = (int)$items[$pid];
                $total += (float)$p['price'] * $qty;
            }

            $stmt = $pdo->prepare('INSERT INTO orders (user_id, guest_nom, guest_prenom, guest_telephone, guest_adresse, total_amount, status, payment_method, created_at) VALUES (NULL, :nom, :prenom, :telephone, :adresse, :total_amount, \'en_attente\', \'cash_on_delivery\', NOW())');
            $stmt->execute([
                'nom' => $nom,
                'prenom' => $prenom,
                'telephone' => $telephone,
                'adresse' => $adresse,
                'total_amount' => $total,
            ]);

            $orderId = (int)$pdo->lastInsertId();

            $detailStmt = $pdo->prepare('INSERT INTO order_details (order_id, product_id, product_name, unit_price, quantity, line_total) VALUES (:order_id, :product_id, :product_name, :unit_price, :quantity, :line_total)');
            $stockStmt = $pdo->prepare('UPDATE products SET stock = stock - :qty WHERE id = :id');

            foreach ($products as $p) {
                $pid = (int)$p['id'];
                $qty = (int)$items[$pid];
                $unit = (float)$p['price'];
                $lineTotal = $unit * $qty;

                $detailStmt->execute([
                    'order_id' => $orderId,
                    'product_id' => $pid,
                    'product_name' => (string)$p['name'],
                    'unit_price' => $unit,
                    'quantity' => $qty,
                    'line_total' => $lineTotal,
                ]);

                $stockStmt->execute([
                    'qty' => $qty,
                    'id' => $pid,
                ]);
            }

            $pdo->commit();

            Cart::clear();
            $_SESSION['flash_success'] = 'Commande enregistrée. Paiement à la livraison.';
            $this->redirect('/products');
        } catch (\Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $_SESSION['flash_error'] = 'Erreur lors de la commande.';
            $this->redirect('/checkout');
        }
    }
}
