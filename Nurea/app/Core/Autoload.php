if (session_status() !== PHP_SESSION_ACTIVE) {
    $sessionName = $config['app']['session_name'] ?? 'app_session';
    session_name($sessionName);

    // Détecter si la requête passe par HTTPS (Render utilise X-Forwarded-Proto)
    $isHttps = (
        (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
        (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
    );

    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => $isHttps,   // Force HTTPS si présent
        'httponly' => true,     // Bloque l'accès JS au cookie
        'samesite' => 'Lax'     // Permet l'envoi du cookie sur les formulaires POST
    ]);

    session_start();
}