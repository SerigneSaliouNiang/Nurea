<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Admin;

final class AuthController extends Controller
{
    public function login(): void
    {
        if (!empty($_SESSION['admin_id'])) {
            $this->redirect('/admin');
        }

        $this->view('admin.auth.login');
    }

    public function authenticate(): void
    {
        $email = trim((string)($_POST['email'] ?? ''));
        $password = (string)($_POST['password'] ?? '');

        if ($email === '' || $password === '') {
            $_SESSION['flash_error'] = 'Identifiants invalides.';
            $this->redirect('/admin/login');
        }

        $adminModel = new Admin();
        $admin = $adminModel->findByEmail($email);
        if (is_array($admin) && isset($admin['password_hash']) && password_verify($password, (string)$admin['password_hash'])) {
            $_SESSION['admin_id'] = (int)($admin['id'] ?? 0);
            $_SESSION['admin_email'] = (string)($admin['email'] ?? $email);
            $this->redirect('/admin');
        }

        $_SESSION['flash_error'] = 'Email ou mot de passe incorrect.';
        $this->redirect('/admin/login');
    }

    public function logout(): void
    {
        unset($_SESSION['admin_id'], $_SESSION['admin_email']);
        $this->redirect('/admin/login');
    }
}
