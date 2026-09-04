<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h4 mb-0" style="color:var(--terracotta);">Finaliser la commande</h1>
        <p class="mb-0 small" style="color:var(--text-light);">Vérifiez vos articles et renseignez vos coordonnées.</p>
    </div>
    <a class="btn btn-light btn-sm" href="<?= htmlspecialchars($basePath) ?>/cart">← Retour au panier</a>
</div>

<div class="row g-4">
    <div class="col-12 col-lg-7">
        <div class="card mb-4" style="border:1px solid var(--border);">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead>
                        <tr>
                            <th style="width:70px"></th>
                            <th>Produit</th>
                            <th class="text-end" style="width:120px">Prix</th>
                            <th class="text-center" style="width:70px">Qté</th>
                            <th class="text-end" style="width:120px">Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($lines as $line): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($line['product']['image'])): ?>
                                        <img src="<?= htmlspecialchars((!empty($basePath) && str_starts_with((string)$line['product']['image'], '/')) ? ($basePath . (string)$line['product']['image']) : (string)$line['product']['image']) ?>" alt="" width="44" height="44" style="object-fit:cover; border-radius:8px;">
                                    <?php else: ?>
                                        <div style="width:44px;height:44px;border-radius:8px;background:var(--cream);"></div>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars((string)$line['product']['name']) ?></td>
                                <td class="text-end"><?= number_format((float)$line['product']['price'], 0, '.', ' ') ?></td>
                                <td class="text-center"><?= (int)$line['qty'] ?></td>
                                <td class="text-end fw-semibold"><?= number_format((float)$line['line_total'], 0, '.', ' ') ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4 p-3 rounded-4" style="background:var(--cream);">
            <span class="h5 mb-0">Total</span>
            <span class="h4 mb-0" style="color:var(--terracotta); font-family:'Cormorant Garamond',serif; font-weight:700;"><?= number_format((float)$total, 0, '.', ' ') ?> FCFA</span>
        </div>
    </div>

    <div class="col-12 col-lg-5">
        <div class="card p-4" style="border:1px solid var(--border); position:sticky; top:90px;">
            <h2 class="h5 mb-3">Coordonnées</h2>
            <form method="post" action="<?= htmlspecialchars($basePath) ?>/checkout" class="d-grid gap-3">
                <?= \App\Core\Csrf::field() ?>
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label">Nom</label>
                        <input class="form-control" name="nom" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Prénom</label>
                        <input class="form-control" name="prenom" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Téléphone</label>
                        <input class="form-control" name="telephone" required placeholder="07 XX XX XX XX">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Ville / Adresse</label>
                        <input class="form-control" name="adresse" required>
                    </div>
                </div>
                <div class="d-grid">
                    <button class="btn btn-warning" type="submit">Valider ma commande</button>
                </div>
                <p class="small mb-0 text-center" style="color:var(--muted);">Paiement à la livraison. Livraison offerte.</p>
            </form>
        </div>
    </div>
</div>
