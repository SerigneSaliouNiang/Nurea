<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Admin;
use App\Models\Seller;

final class UserController extends Controller
{
    public function index(): void
    {
        $this->requireAdmin();

        $adminModel = new Admin();
        $sellerModel = new Seller();

        $admins = $adminModel->all();
        $sellers = $sellerModel->all();

        $this->view('admin.users.index', [
            'admins' => $admins,
            'sellers' => $sellers,
        ]);
    }

    public function create(): void
    {
        $this->requireAdmin();
        $this->view('admin.users.create');
    }

    public function store(): void
    {
        $this->requireAdmin();

        $role = $_POST['role'] ?? 'seller';

        if ($role === 'admin') {
            $email = trim((string)($_POST['email'] ?? ''));
            $password = (string)($_POST['password'] ?? '');

            if ($email === '' || $password === '') {
                $_SESSION['flash_error'] = 'Email et mot de passe requis pour créer un administrateur.';
                $this->redirect('/admin/users/create');
            }

            $adminModel = new Admin();
            if ($adminModel->findByEmail($email) !== null) {
                $_SESSION['flash_error'] = 'Un administrateur avec cet email existe déjà.';
                $this->redirect('/admin/users/create');
            }

            $hash = password_hash($password, PASSWORD_DEFAULT);
            $adminModel->create($email, $hash);
            $_SESSION['flash_success'] = 'Administrateur créé.';
            $this->redirect('/admin/users');
        }

        // seller
        $name = trim((string)($_POST['name'] ?? ''));
        $email = trim((string)($_POST['email'] ?? ''));
        $password = (string)($_POST['password'] ?? '');

        if ($name === '' || $email === '') {
            $_SESSION['flash_error'] = 'Nom et email requis pour créer un vendeur.';
            $this->redirect('/admin/users/create');
        }

        $sellerModel = new Seller();
        if ($sellerModel->findByEmail($email) !== null) {
            $_SESSION['flash_error'] = 'Un vendeur avec cet email existe déjà.';
            $this->redirect('/admin/users/create');
        }

        $hash = $password !== '' ? password_hash($password, PASSWORD_DEFAULT) : null;
        $sellerModel->create($name, $email, $hash);
        $_SESSION['flash_success'] = 'Vendeur créé.';
        $this->redirect('/admin/users');
    }

    public function edit(): void
    {
        $this->requireAdmin();

        $id = (int)($_GET['id'] ?? 0);
        $role = (string)($_GET['role'] ?? 'seller');

        $entity = null;
        if ($role === 'admin') {
            $entity = (new Admin())->findById($id);
        } else {
            $entity = (new Seller())->findById($id);
        }

        if ($entity === null) {
            $_SESSION['flash_error'] = 'Utilisateur introuvable.';
            $this->redirect('/admin/users');
        }

        $this->view('admin.users.edit', [
            'role' => $role,
            'entity' => $entity,
        ]);
    }

    public function update(): void
    {
        $this->requireAdmin();

        $id = (int)($_POST['id'] ?? 0);
        $role = (string)($_POST['role'] ?? 'seller');
        $email = trim((string)($_POST['email'] ?? ''));
        $password = (string)($_POST['password'] ?? '');

        if ($email === '') {
            $_SESSION['flash_error'] = 'L\'email est requis.';
            $this->redirect('/admin/users');
        }

        $hash = $password !== '' ? password_hash($password, PASSWORD_DEFAULT) : null;

        if ($role === 'admin') {
            $adminModel = new Admin();
            $existing = $adminModel->findById($id);
            if ($existing === null) {
                $_SESSION['flash_error'] = 'Administrateur introuvable.';
                $this->redirect('/admin/users');
            }

            $other = $adminModel->findByEmail($email);
            if ($other !== null && (int)$other['id'] !== $id) {
                $_SESSION['flash_error'] = 'Un administrateur avec cet email existe déjà.';
                $this->redirect('/admin/users/edit?id=' . $id . '&role=admin');
            }

            $adminModel->update($id, $email, $hash);
            $_SESSION['flash_success'] = 'Administrateur mis à jour.';
        } else {
            $name = trim((string)($_POST['name'] ?? ''));
            if ($name === '') {
                $_SESSION['flash_error'] = 'Le nom est requis pour un vendeur.';
                $this->redirect('/admin/users');
            }

            $sellerModel = new Seller();
            $existing = $sellerModel->findById($id);
            if ($existing === null) {
                $_SESSION['flash_error'] = 'Vendeur introuvable.';
                $this->redirect('/admin/users');
            }

            $other = $sellerModel->findByEmail($email);
            if ($other !== null && (int)$other['id'] !== $id) {
                $_SESSION['flash_error'] = 'Un vendeur avec cet email existe déjà.';
                $this->redirect('/admin/users/edit?id=' . $id . '&role=seller');
            }

            $sellerModel->update($id, $name, $email, $hash);
            $_SESSION['flash_success'] = 'Vendeur mis à jour.';
        }

        $this->redirect('/admin/users');
    }

    public function destroy(): void
    {
        $this->requireAdmin();

        $id = (int)($_POST['id'] ?? 0);
        $role = (string)($_POST['role'] ?? 'seller');

        if ($role === 'admin') {
            $adminModel = new Admin();
            $existing = $adminModel->findById($id);
            if ($existing === null) {
                $_SESSION['flash_error'] = 'Administrateur introuvable.';
                $this->redirect('/admin/users');
            }
            $adminModel->delete($id);
            $_SESSION['flash_success'] = 'Administrateur supprimé.';
        } else {
            $sellerModel = new Seller();
            $existing = $sellerModel->findById($id);
            if ($existing === null) {
                $_SESSION['flash_error'] = 'Vendeur introuvable.';
                $this->redirect('/admin/users');
            }
            $sellerModel->delete($id);
            $_SESSION['flash_success'] = 'Vendeur supprimé.';
        }

        $this->redirect('/admin/users');
    }
}
