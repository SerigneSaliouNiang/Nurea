<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h1 class="h4 mb-0" style="color:#d4af37;">Commandes</h1>
        <p class="text-muted mb-0">Toutes les commandes contenant vos produits</p>
    </div>
</div>

<div class="card p-3">
    <?php if (empty($orders)): ?>
        <p class="text-muted">Aucune commande trouvée.</p>
    <?php else: ?>
        <ul class="list-group list-group-flush">
            <?php foreach ($orders as $o): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <a href="<?= htmlspecialchars($basePath) ?>/seller/orders/show?id=<?= (int)$o['id'] ?>">Commande #<?= (int)$o['id'] ?></a>
                        <div class="small text-muted">Le <?= htmlspecialchars($o['created_at']) ?> • Statut: <?= htmlspecialchars($o['status']) ?></div>
                    </div>
                    <div class="fw-semibold"><?= number_format((float)$o['total_amount'], 2, '.', ' ') ?> FCFA</div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
