<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h1 class="h4 mb-0" style="color:#d4af37;">Commande #<?= (int)$order['id'] ?></h1>
        <p class="text-muted mb-0">Détails et gestion</p>
    </div>
    <a class="btn btn-outline-warning btn-sm" href="<?= htmlspecialchars($basePath) ?>/seller/orders">Retour</a>
</div>

<div class="row g-3">
    <div class="col-12 col-md-8">
        <div class="card p-3 mb-3">
            <h5>Lignes de commande (vos produits)</h5>
            <?php if (empty($lines)): ?>
                <p class="text-muted">Aucune ligne pour vos produits.</p>
            <?php else: ?>
                <ul class="list-group list-group-flush">
                    <?php foreach ($lines as $line): ?>
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong><?= htmlspecialchars($line['product_name']) ?></strong>
                                    <div class="small text-muted">Quantité: <?= (int)$line['quantity'] ?> • Prix unitaire: <?= number_format((float)$line['unit_price'], 2, '.', ' ') ?> FCFA</div>
                                </div>
                                <div class="text-end">
                                    <div class="mb-2">Statut: <strong><?= htmlspecialchars($line['delivery_status']) ?></strong></div>
                                    <form method="post" action="<?= htmlspecialchars($basePath) ?>/seller/orders/update-status" class="d-flex gap-2">
                                        <input type="hidden" name="detail_id" value="<?= (int)$line['id'] ?>">
                                        <select name="status" class="form-select form-select-sm">
                                            <option value="en_attente" <?= $line['delivery_status'] === 'en_attente' ? 'selected' : '' ?>>En attente</option>
                                            <option value="packaging" <?= $line['delivery_status'] === 'packaging' ? 'selected' : '' ?>>Packaging</option>
                                            <option value="en_cours_de_livraison" <?= $line['delivery_status'] === 'en_cours_de_livraison' ? 'selected' : '' ?>>En livraison</option>
                                            <option value="valide" <?= $line['delivery_status'] === 'valide' ? 'selected' : '' ?>>Acheté / Terminé</option>
                                        </select>
                                        <button class="btn btn-sm btn-warning" type="submit">Mettre</button>
                                    </form>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card p-3 mb-3">
            <h5>Résumé commande</h5>
            <div class="small text-muted">Total commande:</div>
            <div class="h4"><?= number_format((float)$order['total_amount'], 2, '.', ' ') ?> FCFA</div>
            <div class="mt-3">
                <form method="post" action="<?= htmlspecialchars($basePath) ?>/seller/orders/mark-paid">
                    <input type="hidden" name="order_id" value="<?= (int)$order['id'] ?>">
                    <div class="mb-2">
                        <label class="form-label">Montant reçu</label>
                        <input name="paid_amount" type="number" step="0.01" class="form-control" value="<?= htmlspecialchars($order['paid_amount'] ?? $order['total_amount']) ?>">
                    </div>
                    <?php if (!empty($order['paid_at'])): ?>
                        <div class="small text-success">Payé le <?= htmlspecialchars($order['paid_at']) ?> • <?= number_format((float)$order['paid_amount'], 2, '.', ' ') ?> FCFA</div>
                    <?php endif; ?>
                    <button class="btn btn-warning mt-2">Enregistrer paiement</button>
                </form>
            </div>
        </div>
    </div>
</div>
