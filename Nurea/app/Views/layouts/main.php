<?php
/** @var string $appName */
/** @var string $contentViewPath */

$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
if (!empty($basePath) && $currentPath !== $basePath && str_starts_with($currentPath, $basePath . '/')) {
    $currentPath = substr($currentPath, strlen($basePath));
}

$isAdmin = str_starts_with($currentPath ?: '/', '/admin');
$assetVersion = '';
$cssAssetPath = __DIR__ . '/../../../assets/css/app.css';
$jsAssetPath = __DIR__ . '/../../../assets/js/app.js';
if (file_exists($cssAssetPath) || file_exists($jsAssetPath)) {
    $assetVersion = max(
        file_exists($cssAssetPath) ? filemtime($cssAssetPath) : 0,
        file_exists($jsAssetPath) ? filemtime($jsAssetPath) : 0
    );
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($appName) ?> — Boutique de cosmétiques</title>
    <meta name="description" content="Découvrez NUREA, votre boutique en ligne de soins et cosmétiques.">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= htmlspecialchars($basePath) ?>/assets/css/app.css?v=<?= htmlspecialchars($assetVersion) ?>" rel="stylesheet">
    <script defer src="<?= htmlspecialchars($basePath) ?>/assets/js/app.js?v=<?= htmlspecialchars($assetVersion) ?>"></script>
</head>
<body class="site-shell<?= $isAdmin ? ' is-admin' : '' ?>">
<nav class="navbar navbar-expand-lg site-navbar sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="<?= htmlspecialchars($basePath) ?>/">
            <img class="brand-logo" src="<?= htmlspecialchars($basePath) ?>/assets/img/logo.png" alt="<?= htmlspecialchars($appName) ?>" onerror="this.style.display='none'">
            <span><?= htmlspecialchars($appName) ?></span>
        </a>
        <?php if (!$isAdmin): ?>
            <div class="d-flex gap-2">
                <a class="btn btn-outline-warning btn-sm" href="<?= htmlspecialchars($basePath) ?>/products">Catalogue</a>
                <a class="btn btn-warning btn-sm" href="<?= htmlspecialchars($basePath) ?>/cart">Panier</a>
            </div>
        <?php endif; ?>
        <?php if ($isAdmin && !empty($_SESSION['admin_id'])): ?>
            <button class="btn btn-outline-warning btn-sm admin-drawer-toggle ms-2" aria-label="Ouvrir le menu">☰</button>
        <?php endif; ?>
        <div class="ms-auto d-flex gap-2">
            <?php if ($isAdmin): ?>
                <?php if (!empty($_SESSION['admin_id'])): ?>
                    <form method="post" action="<?= htmlspecialchars($basePath) ?>/admin/logout" class="m-0">
                        <?= \App\Core\Csrf::field() ?>
                        <button class="btn btn-outline-warning btn-sm" type="submit">Déconnexion</button>
                    </form>
                <?php else: ?>
                    <a class="btn btn-outline-warning btn-sm" href="<?= htmlspecialchars($basePath) ?>/admin/login">Admin</a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</nav>

<?php if ($isAdmin && !empty($_SESSION['admin_id'])): ?>
<!-- Admin drawer -->
<div class="admin-drawer" id="adminDrawer" aria-hidden="true">
    <div class="admin-drawer-header">
        <div class="d-flex align-items-center gap-2">
            <img class="brand-logo" src="<?= htmlspecialchars($basePath) ?>/assets/img/logo.png" alt="">
            <div>
                <div class="fw-bold">Admin</div>
                <div class="small text-muted"><?= htmlspecialchars($appName) ?></div>
            </div>
        </div>
        <button class="btn-close admin-drawer-close" aria-label="Fermer"></button>
    </div>
    <div class="admin-drawer-body">
        <nav class="nav flex-column">
            <a class="nav-link" href="<?= htmlspecialchars($basePath) ?>/admin">Dashboard</a>
            <a class="nav-link" href="<?= htmlspecialchars($basePath) ?>/admin/categories">Catégories</a>
            <a class="nav-link" href="<?= htmlspecialchars($basePath) ?>/admin/products">Produits</a>
            <a class="nav-link" href="<?= htmlspecialchars($basePath) ?>/admin/orders">Commandes</a>
            <a class="nav-link" href="<?= htmlspecialchars($basePath) ?>/admin/users">Utilisateurs</a>
            <a class="nav-link" href="<?= htmlspecialchars($basePath) ?>/admin/settings">Paramètres</a>
        </nav>
    </div>
</div>
<div class="admin-drawer-overlay" id="adminDrawerOverlay" tabindex="-1"></div>
<?php endif; ?>

<?php if (!empty($promoBannerEnabled)): ?>
<section class="promo-banner py-2">
    <div class="container d-flex flex-column flex-md-row align-items-center justify-content-between gap-2">
        <div>
            <?= htmlspecialchars($promoBannerText) ?>
        </div>
        <a class="btn btn-sm btn-warning" href="<?= htmlspecialchars($basePath) ?>/products">Voir la collection</a>
    </div>
</section>
<?php endif; ?>

<main class="py-4">
    <div class="container">
        <?php if (!empty($_SESSION['flash_success'])): ?>
            <div class="visually-hidden"><?= htmlspecialchars((string)$_SESSION['flash_success']) ?></div>
            <script>document.addEventListener('DOMContentLoaded',function(){ if(window.showToast) showToast(<?= json_encode('Succès') ?>, <?= json_encode((string)$_SESSION['flash_success']) ?>, 'success',4000); });</script>
            <?php unset($_SESSION['flash_success']); ?>
        <?php endif; ?>
        <?php if (!empty($_SESSION['flash_error'])): ?>
            <div class="visually-hidden"><?= htmlspecialchars((string)$_SESSION['flash_error']) ?></div>
            <script>document.addEventListener('DOMContentLoaded',function(){ if(window.showToast) showToast(<?= json_encode('Erreur') ?>, <?= json_encode((string)$_SESSION['flash_error']) ?>, 'error',5000); });</script>
            <?php unset($_SESSION['flash_error']); ?>
        <?php endif; ?>
        <?php require $contentViewPath; ?>
    </div>
</main>

<footer class="mt-auto">
    <div class="container">
        <div class="row g-4 align-items-center">
            <div class="col-md-6">
                <span class="h5" style="font-family:'Cormorant Garamond',serif; font-weight:700;"><?= htmlspecialchars($appName) ?></span>
                <p class="mb-0 small">Soins &amp; cosmétiques sélectionnés avec soin.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="<?= htmlspecialchars($basePath) ?>/products" class="me-3 small">Catalogue</a>
                <a href="<?= htmlspecialchars($basePath) ?>/cart" class="small">Panier</a>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
