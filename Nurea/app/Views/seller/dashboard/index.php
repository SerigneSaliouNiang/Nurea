<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h1 class="h4 mb-0" style="color:#d4af37;">Espace vendeur</h1>
        <p class="text-muted mb-0">Tableau de bord • Aperçu rapide de votre activité</p>
    </div>
    <a class="btn btn-outline-warning btn-sm" href="<?= htmlspecialchars($basePath) ?>/seller/logout">Se déconnecter</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-12 col-md-4">
        <div class="card p-3">
            <div class="text-secondary">Produits</div>
            <div class="h4 mb-0"><?= (int)$productCount ?></div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card p-3">
            <div class="text-secondary">Commandes</div>
            <div class="h4 mb-0"><?= (int)$ordersCount ?></div>
        </div>
    </div>
</div>

<div class="card p-3">
    <h5>Dernières commandes impliquant vos produits</h5>
    <?php if (empty($recentOrders)): ?>
        <p class="text-muted">Aucune commande pour le moment.</p>
    <?php else: ?>
        <ul class="list-group list-group-flush">
            <?php foreach ($recentOrders as $o): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>Commande #<?= (int)$o['id'] ?> <div class="small text-muted">Le <?= htmlspecialchars($o['created_at']) ?></div></div>
                    <div class="fw-semibold"><?= number_format((float)$o['total_amount'], 2, '.', ' ') ?> FCFA</div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
