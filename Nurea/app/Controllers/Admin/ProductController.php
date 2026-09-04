<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Category;
use App\Models\Product;

final class ProductController extends Controller
{
    private string $uploadDir;

    public function __construct()
    {
        $this->uploadDir = __DIR__ . '/../../../assets/uploads/products';
    }

    public function index(): void
    {
        $this->requireAdmin();

        $productModel = new Product();
        $products = $productModel->allWithCategory();

        $this->view('admin.products.index', [
            'products' => $products,
        ]);
    }

    public function create(): void
    {
        $this->requireAdmin();

        $categoryModel = new Category();
        $categories = $categoryModel->all();

        $this->view('admin.products.create', [
            'categories' => $categories,
        ]);
    }

    public function store(): void
    {
        $this->requireAdmin();

        $payload = $this->validatedPayload();
        if ($payload === null) {
            $this->redirect('/admin/products/create');
        }

        $imagePath = $this->handleUpload(null);
        if ($imagePath === false) {
            $this->redirect('/admin/products/create');
        }

        $payload['image'] = $imagePath;

        try {
            $productModel = new Product();
            $productModel->create($payload);
            $_SESSION['flash_success'] = 'Produit ajouté.';
            $this->redirect('/admin/products');
        } catch (\Throwable $e) {
            $_SESSION['flash_error'] = 'Erreur lors de la création du produit.';
            $this->redirect('/admin/products/create');
        }
    }

    public function edit(): void
    {
        $this->requireAdmin();

        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['flash_error'] = 'Produit introuvable.';
            $this->redirect('/admin/products');
        }

        $productModel = new Product();
        $product = $productModel->find($id);
        if ($product === null) {
            $_SESSION['flash_error'] = 'Produit introuvable.';
            $this->redirect('/admin/products');
        }

        $categoryModel = new Category();
        $categories = $categoryModel->all();

        $this->view('admin.products.edit', [
            'product' => $product,
            'categories' => $categories,
        ]);
    }

    public function update(): void
    {
        $this->requireAdmin();

        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['flash_error'] = 'Données invalides.';
            $this->redirect('/admin/products');
        }

        $payload = $this->validatedPayload();
        if ($payload === null) {
            $this->redirect('/admin/products/edit?id=' . $id);
        }

        $productModel = new Product();
        $existing = $productModel->find($id);
        if ($existing === null) {
            $_SESSION['flash_error'] = 'Produit introuvable.';
            $this->redirect('/admin/products');
        }

        $imagePath = $this->handleUpload((string)($existing['image'] ?? ''));
        if ($imagePath === false) {
            $this->redirect('/admin/products/edit?id=' . $id);
        }

        $payload['image'] = $imagePath === '' ? null : $imagePath;

        try {
            $productModel->update($id, $payload);
            $_SESSION['flash_success'] = 'Produit mis à jour.';
            $this->redirect('/admin/products');
        } catch (\Throwable $e) {
            $_SESSION['flash_error'] = 'Erreur lors de la mise à jour.';
            $this->redirect('/admin/products/edit?id=' . $id);
        }
    }

    public function delete(): void
    {
        $this->requireAdmin();

        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['flash_error'] = 'Données invalides.';
            $this->redirect('/admin/products');
        }

        try {
            $productModel = new Product();
            $existing = $productModel->find($id);
            if ($existing !== null && !empty($existing['image'])) {
                $this->tryDeleteImage((string)$existing['image']);
            }
            $productModel->delete($id);
            $_SESSION['flash_success'] = 'Produit supprimé.';
        } catch (\Throwable $e) {
            $_SESSION['flash_error'] = 'Suppression impossible.';
        }

        $this->redirect('/admin/products');
    }

    private function validatedPayload(): ?array
    {
        $name = trim((string)($_POST['name'] ?? ''));
        $description = trim((string)($_POST['description'] ?? ''));
        $categoryIdRaw = trim((string)($_POST['category_id'] ?? ''));
        $priceRaw = trim((string)($_POST['price'] ?? '0'));
        $stockRaw = trim((string)($_POST['stock'] ?? '0'));

        if ($name === '') {
            $_SESSION['flash_error'] = 'Le nom du produit est obligatoire.';
            return null;
        }

        $categoryId = $categoryIdRaw === '' ? null : (int)$categoryIdRaw;

        $priceRaw = str_replace(',', '.', $priceRaw);
        if (!is_numeric($priceRaw)) {
            $_SESSION['flash_error'] = 'Prix invalide.';
            return null;
        }

        if ($stockRaw === '' || !is_numeric($stockRaw)) {
            $_SESSION['flash_error'] = 'Stock invalide.';
            return null;
        }

        return [
            'category_id' => $categoryId,
            'name' => $name,
            'description' => $description === '' ? null : $description,
            'price' => (float)$priceRaw,
            'stock' => (int)$stockRaw,
            'image' => null,
        ];
    }

    /**
     * @return string|false Returns new image path (web path) or false on hard error.
     */
    private function handleUpload(?string $existingImagePath)
    {
        if (!isset($_FILES['image']) || !is_array($_FILES['image'])) {
            return $existingImagePath ?? '';
        }

        if (($_FILES['image']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return $existingImagePath ?? '';
        }

        if (($_FILES['image']['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
            $_SESSION['flash_error'] = "Erreur d'upload image.";
            return false;
        }

        $tmp = (string)($_FILES['image']['tmp_name'] ?? '');
        $originalName = (string)($_FILES['image']['name'] ?? '');

        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        if (!in_array($ext, $allowed, true)) {
            $_SESSION['flash_error'] = 'Format image non autorisé (jpg, jpeg, png, webp).';
            return false;
        }

        if (!is_dir($this->uploadDir)) {
            if (!mkdir($this->uploadDir, 0775, true) && !is_dir($this->uploadDir)) {
                $_SESSION['flash_error'] = "Impossible de créer le dossier d'upload.";
                return false;
            }
        }

        $filename = bin2hex(random_bytes(12)) . '.' . $ext;
        $destPath = $this->uploadDir . '/' . $filename;

        if (!move_uploaded_file($tmp, $destPath)) {
            $_SESSION['flash_error'] = "Impossible d'enregistrer l'image.";
            return false;
        }

        if ($existingImagePath) {
            $this->tryDeleteImage($existingImagePath);
        }

        return '/assets/uploads/products/' . $filename;
    }

    private function tryDeleteImage(string $webPath): void
    {
        $webPath = trim($webPath);
        if ($webPath === '' || !str_starts_with($webPath, '/assets/uploads/products/')) {
            return;
        }

        $file = __DIR__ . '/../../../' . ltrim($webPath, '/');
        if (is_file($file)) {
            @unlink($file);
        }
    }
}
