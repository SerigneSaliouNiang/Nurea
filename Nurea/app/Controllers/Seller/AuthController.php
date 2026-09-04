<?php

declare(strict_types=1);

namespace App\Controllers\Seller;

use App\Core\Controller;
use App\Models\Seller;

final class AuthController extends Controller
{
    public function login(): void
    {
        if (!empty($_SESSION['seller_id'])) {
            $this->redirect('/');
        }

        $this->view('seller.auth.login');
    }

    public function authenticate(): void
    {
        $email = trim((string)($_POST['email'] ?? ''));
        $password = (string)($_POST['password'] ?? '');

        if ($email === '' || $password === '') {
            $_SESSION['flash_error'] = 'Identifiants invalides.';
            $this->redirect('/seller/login');
        }

        $sellerModel = new Seller();
        $seller = $sellerModel->findByEmail($email);
        if (is_array($seller) && isset($seller['password_hash']) && $seller['password_hash'] !== null && password_verify($password, (string)$seller['password_hash'])) {
            $_SESSION['seller_id'] = (int)($seller['id'] ?? 0);
            $_SESSION['seller_email'] = (string)($seller['email'] ?? $email);

            // If seller never changed password, redirect to change page
            if (empty($seller['password_changed_at'] ?? null)) {
                $this->redirect('/seller/change-password');
            }

            $this->redirect('/seller');
        }

        // allow sellers with NULL password? they must contact admin
        $_SESSION['flash_error'] = 'Email ou mot de passe incorrect. Si vous n\'avez pas reçu de mot de passe, contactez l\'administrateur.';
        $this->redirect('/seller/login');
    }

    public function logout(): void
    {
        unset($_SESSION['seller_id'], $_SESSION['seller_email']);
        $this->redirect('/seller/login');
    }

    public function showChangePassword(): void
    {
        if (empty($_SESSION['seller_id'])) {
            $this->redirect('/seller/login');
        }

        $this->view('seller.auth.change_password');
    }

    public function changePassword(): void
    {
        if (empty($_SESSION['seller_id'])) {
            $this->redirect('/seller/login');
        }

        $id = (int)$_SESSION['seller_id'];
        $password = (string)($_POST['password'] ?? '');
        $passwordConfirm = (string)($_POST['password_confirm'] ?? '');

        if ($password === '' || $passwordConfirm === '') {
            $_SESSION['flash_error'] = 'Veuillez remplir les deux champs de mot de passe.';
            $this->redirect('/seller/change-password');
        }

        if ($password !== $passwordConfirm) {
            $_SESSION['flash_error'] = 'Les mots de passe ne correspondent pas.';
            $this->redirect('/seller/change-password');
        }

        // validation: au moins une majuscule et un caractère spécial (@# etc.)
        if (!preg_match('/[A-Z]/', $password) || !preg_match('/[\@\#\!\$\%\^\&\*\(\)\-\_\+\=\[\]\{\};:\'"\\|,.<>\/\?]/', $password)) {
            $_SESSION['flash_error'] = 'Le mot de passe doit contenir au moins une lettre majuscule et un caractère spécial (ex: @ #).';
            $this->redirect('/seller/change-password');
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sellerModel = new Seller();
        $sellerModel->updatePassword($id, $hash);

        $_SESSION['flash_success'] = 'Mot de passe mis à jour.';
        $this->redirect('/seller');
    }
}
