<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Setting;

final class SettingController extends Controller
{
    public function index(): void
    {
        $this->requireAdmin();

        $settings = (new Setting())->all();

        $this->view('admin.settings.index', [
            'promoBannerEnabled' => isset($settings['promo_banner_enabled']) && $settings['promo_banner_enabled'] === '1',
            'promoBannerText' => $settings['promo_banner_text'] ?? 'Nouveauté ✨ Livraison offerte dès 79 000 FCFA d\'achat et conseils beauté offerts.',
        ]);
    }

    public function update(): void
    {
        $this->requireAdmin();

        $enabled = isset($_POST['promo_banner_enabled']) ? '1' : '0';
        $text = trim((string)($_POST['promo_banner_text'] ?? ''));

        $settingModel = new Setting();
        $settingModel->set('promo_banner_enabled', $enabled);
        $settingModel->set('promo_banner_text', $text);

        $_SESSION['flash_success'] = 'Paramètres de bandeau enregistrés.';
        $this->redirect('/admin/settings');
    }
}
