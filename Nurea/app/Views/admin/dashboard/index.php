<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h1 class="h4 mb-0" style="color:#d4af37;">Dashboard</h1>
        <p class="text-muted mb-0">Vue globale des ventes, commandes et produits les plus vendus.</p>
    </div>
    <a class="btn btn-warning btn-sm" href="<?= htmlspecialchars($basePath) ?>/admin/orders">Voir les commandes</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-12 col-md-4">
        <div class="card" style="border:1px solid rgba(212,175,55,.25)">
            <div class="card-body">
                <div class="text-secondary">Ventes du jour</div>
                <div class="h4 mb-0"><?= number_format($salesToday, 2, '.', ' ') ?> FCFA</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card" style="border:1px solid rgba(212,175,55,.25)">
            <div class="card-body">
                <div class="text-secondary">Commandes du mois</div>
                <div class="h4 mb-0"><?= (int)$ordersMonth ?></div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card" style="border:1px solid rgba(212,175,55,.25)">
            <div class="card-body">
                <div class="text-secondary">CA global</div>
                <div class="h4 mb-0"><?= number_format($globalRevenue, 2, '.', ' ') ?> FCFA</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-12 col-md-6">
        <div class="card" style="border:1px solid rgba(212,175,55,.25)">
            <div class="card-body">
                <h5 class="card-title">Statut des commandes</h5>
                <ul class="list-group list-group-flush">
                    <?php foreach ($orderStatusCounts as $status => $count): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?= htmlspecialchars($statusLabels[$status] ?? $status) ?>
                            <span class="badge bg-warning text-dark rounded-pill"><?= (int)$count ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="card" style="border:1px solid rgba(212,175,55,.25)">
            <div class="card-body">
                <h5 class="card-title">Produits les plus vendus</h5>
                <?php if (empty($topProducts)): ?>
                    <p class="text-secondary mb-0">Aucun produit vendu pour le moment.</p>
                <?php else: ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($topProducts as $product): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong><?= htmlspecialchars((string)$product['product_name']) ?></strong>
                                    <div class="text-secondary small">Quantité: <?= (int)$product['total_quantity'] ?></div>
                                </div>
                                <span><?= number_format((float)$product['total_revenue'], 2, '.', ' ') ?> FCFA</span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
