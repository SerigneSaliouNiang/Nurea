<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Category;

final class CategoryController extends Controller
{
    public function index(): void
    {
        $this->requireAdmin();

        $categoryModel = new Category();
        $categories = $categoryModel->all();

        $this->view('admin.categories.index', [
            'categories' => $categories,
        ]);
    }

    public function create(): void
    {
        $this->requireAdmin();

        $this->view('admin.categories.create');
    }

    public function store(): void
    {
        $this->requireAdmin();

        $name = trim((string)($_POST['name'] ?? ''));
        if ($name === '') {
            $_SESSION['flash_error'] = 'Le nom de la catégorie est obligatoire.';
            $this->redirect('/admin/categories/create');
        }

        try {
            $categoryModel = new Category();
            $categoryModel->create($name);
            $_SESSION['flash_success'] = 'Catégorie ajoutée.';
            $this->redirect('/admin/categories');
        } catch (\Throwable $e) {
            $_SESSION['flash_error'] = 'Erreur lors de la création (nom déjà utilisé ?)';
            $this->redirect('/admin/categories/create');
        }
    }

    public function edit(): void
    {
        $this->requireAdmin();

        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['flash_error'] = 'Catégorie introuvable.';
            $this->redirect('/admin/categories');
        }

        $categoryModel = new Category();
        $category = $categoryModel->find($id);
        if ($category === null) {
            $_SESSION['flash_error'] = 'Catégorie introuvable.';
            $this->redirect('/admin/categories');
        }

        $this->view('admin.categories.edit', [
            'category' => $category,
        ]);
    }

    public function update(): void
    {
        $this->requireAdmin();

        $id = (int)($_POST['id'] ?? 0);
        $name = trim((string)($_POST['name'] ?? ''));

        if ($id <= 0 || $name === '') {
            $_SESSION['flash_error'] = 'Données invalides.';
            $this->redirect('/admin/categories');
        }

        try {
            $categoryModel = new Category();
            $categoryModel->update($id, $name);
            $_SESSION['flash_success'] = 'Catégorie mise à jour.';
            $this->redirect('/admin/categories');
        } catch (\Throwable $e) {
            $_SESSION['flash_error'] = 'Erreur lors de la mise à jour.';
            $this->redirect('/admin/categories/edit?id=' . $id);
        }
    }

    public function delete(): void
    {
        $this->requireAdmin();

        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['flash_error'] = 'Données invalides.';
            $this->redirect('/admin/categories');
        }

        try {
            $categoryModel = new Category();
            $categoryModel->delete($id);
            $_SESSION['flash_success'] = 'Catégorie supprimée.';
        } catch (\Throwable $e) {
            $_SESSION['flash_error'] = 'Suppression impossible (catégorie liée à des produits ?)';
        }

        $this->redirect('/admin/categories');
    }
}
