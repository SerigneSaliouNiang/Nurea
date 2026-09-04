<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h4 mb-1" style="color:var(--terracotta);">Votre panier</h1>
        <p class="mb-0" style="color:var(--text-light);">Vérifiez vos articles avant de finaliser la commande.</p>
    </div>
    <a class="btn btn-outline-warning btn-sm" href="<?= htmlspecialchars($basePath) ?>/products">Continuer mes achats</a>
</div>

<?php if (empty($lines)): ?>
    <div class="text-center py-5" style="color:var(--muted);">
        <p class="h5">Votre panier est vide.</p>
        <a class="btn btn-warning mt-3" href="<?= htmlspecialchars($basePath) ?>/products">Découvrir les produits</a>
    </div>
<?php else: ?>
    <div class="row g-4">
        <div class="col-12 col-xl-8">
            <form method="post" action="<?= htmlspecialchars($basePath) ?>/cart/update" class="d-flex flex-column gap-3">
                <?= \App\Core\Csrf::field() ?>
                <?php foreach ($lines as $line): ?>
                    <div class="card cart-item-card p-3">
                        <div class="d-flex flex-column flex-md-row gap-3 align-items-center">
                            <div class="cart-item-image rounded-4 overflow-hidden">
                                <?php if (!empty($line['product']['image'])): ?>
                                    <img src="<?= htmlspecialchars((!empty($basePath) && str_starts_with((string)$line['product']['image'], '/')) ? ($basePath . (string)$line['product']['image']) : (string)$line['product']['image']) ?>" alt="<?= htmlspecialchars((string)$line['product']['name']) ?>" />
                                <?php else: ?>
                                    <div class="d-flex align-items-center justify-content-center" style="color:var(--muted); background:var(--cream);">Sans image</div>
                                <?php endif; ?>
                            </div>
                            <div class="flex-grow-1">
                                <h2 class="h6 mb-1"><?= htmlspecialchars((string)$line['product']['name']) ?></h2>
                                <div class="small mb-2" style="color:var(--text-light);">Prix unitaire : <?= number_format((float)$line['product']['price'], 0, '.', ' ') ?> FCFA</div>
                                <div class="d-flex flex-wrap gap-2 align-items-center">
                                    <div>
                                        <label class="form-label small mb-1">Quantité</label>
                                        <input class="form-control form-control-sm" type="number" name="items[<?= (int)$line['product']['id'] ?>]" value="<?= (int)$line['qty'] ?>" min="0" style="width:100px;">
                                    </div>
                                    <div class="ms-auto text-end">
                                        <div class="fw-semibold">Total</div>
                                        <div><?= number_format((float)$line['line_total'], 2, '.', ' ') ?> FCFA</div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end">
                                <button class="btn btn-outline-danger btn-sm" formaction="<?= htmlspecialchars($basePath) ?>/cart/remove" formmethod="post" name="product_id" value="<?= (int)$line['product']['id'] ?>">Supprimer</button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="d-flex justify-content-end gap-2">
                    <button class="btn btn-outline-warning btn-sm" type="submit">Mettre à jour</button>
                </div>
            </form>
        </div>
        <div class="col-12 col-xl-4">
            <div class="card summary-card p-4">
                <h2 class="h5 mb-3">Résumé</h2>
                <div class="d-flex justify-content-between mb-2" style="color:var(--text-light);">
                    <span>Sous-total</span>
                    <span><?= number_format((float)$total, 0, '.', ' ') ?> FCFA</span>
                </div>
                <div class="d-flex justify-content-between mb-3 small" style="color:var(--sage-deep);">
                    <span>Livraison</span>
                    <span>Offerte</span>
                </div>
                <div class="border-top pt-3 d-flex justify-content-between align-items-center mb-4" style="border-color:var(--border) !important;">
                    <strong>Total</strong>
                    <strong><?= number_format((float)$total, 0, '.', ' ') ?> FCFA</strong>
                </div>
                <a class="btn btn-warning w-100" href="<?= htmlspecialchars($basePath) ?>/checkout">Passer à la commande</a>
                <p class="small mt-3" style="color:var(--muted);">Vous pouvez modifier les quantités ou supprimer un article avant de valider.</p>
            </div>
        </div>
    </div>
<?php endif; ?>
