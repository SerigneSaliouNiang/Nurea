<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Category;
use App\Models\Product;

final class HomeController extends Controller
{
    public function index(): void
    {
        $categoryModel = new Category();
        $productModel = new Product();

        $this->view('home.index', [
            'categories' => array_slice($categoryModel->all(), 0, 4),
            'featuredProducts' => $productModel->latest(6),
        ]);
    }
}
