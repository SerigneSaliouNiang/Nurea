<?php

declare(strict_types=1);

namespace App\Core;

use App\Models\Setting;

final class View
{
    public static function render(string $view, array $data = []): void
    {
        $config = Container::get('config');
        $appName = is_array($config) ? ($config['app']['name'] ?? 'App') : 'App';

        $promoBannerEnabled = false;
        $promoBannerText = 'Nouveauté ✨ Livraison offerte dès 79 000 FCFA d\'achat et conseils beauté offerts.';
        try {
            $settings = (new Setting())->all();
            $promoBannerEnabled = isset($settings['promo_banner_enabled']) && $settings['promo_banner_enabled'] === '1';
            if (isset($settings['promo_banner_text']) && trim((string)$settings['promo_banner_text']) !== '') {
                $promoBannerText = trim((string)$settings['promo_banner_text']);
            }
        } catch (\Throwable $e) {
            // Silence les erreurs si le module de settings n'est pas encore installé.
        }

        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $basePath = is_string($scriptName) ? rtrim(str_replace('\\', '/', dirname($scriptName)), '/') : '';
        if ($basePath === '/' || $basePath === '.') {
            $basePath = '';
        }

        $viewPath = __DIR__ . '/../Views/' . str_replace('.', '/', $view) . '.php';
        if (!is_file($viewPath)) {
            http_response_code(500);
            echo 'View not found';
            return;
        }

        extract($data, EXTR_SKIP);

        $layoutPath = __DIR__ . '/../Views/layouts/main.php';
        $contentViewPath = $viewPath;

        require $layoutPath;
    }
}
