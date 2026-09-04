<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Category;
use App\Models\Product;

final class ProductController extends Controller
{
    public function index(): void
    {
        $q = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
        $categoryId = isset($_GET['category_id']) && (string)$_GET['category_id'] !== '' ? (int)$_GET['category_id'] : null;
        $sort = isset($_GET['sort']) ? (string)$_GET['sort'] : 'newest';

        $categoryModel = new Category();
        $categories = $categoryModel->all();

        $productModel = new Product();
        $products = $productModel->listForCatalog($q, $categoryId, $sort);

        $this->view('products.index', [
            'products' => $products,
            'categories' => $categories,
            'q' => $q,
            'categoryId' => $categoryId,
            'sort' => $sort,
        ]);
    }

    public function show(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            http_response_code(404);
            echo 'Produit introuvable';
            return;
        }

        $productModel = new Product();
        $product = $productModel->findWithCategory($id);
        if ($product === null) {
            http_response_code(404);
            echo 'Produit introuvable';
            return;
        }

        $this->view('products.show', [
            'product' => $product,
        ]);
    }
}
