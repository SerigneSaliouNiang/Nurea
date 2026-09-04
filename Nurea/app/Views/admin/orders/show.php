<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h1 class="h4 mb-0" style="color:#d4af37;">Commande #<?= (int)$order['id'] ?></h1>
        <p class="text-muted mb-0">Détails de la commande et gestion du statut.</p>
    </div>
    <a class="btn btn-warning btn-sm" href="<?= htmlspecialchars($basePath) ?>/admin/orders">Retour</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card" style="border:1px solid rgba(212,175,55,.25)">
            <div class="card-body">
                <h5 class="card-title">Informations cliente</h5>
                <p class="mb-1"><strong>Nom :</strong> <?= htmlspecialchars((string)$order['guest_nom']) ?> <?= htmlspecialchars((string)$order['guest_prenom']) ?></p>
                <p class="mb-1"><strong>Téléphone :</strong> <?= htmlspecialchars((string)$order['guest_telephone']) ?></p>
                <p class="mb-1"><strong>Adresse :</strong> <?= htmlspecialchars((string)$order['guest_adresse']) ?></p>
                <p class="mb-0"><strong>Date :</strong> <?= htmlspecialchars((string)$order['created_at']) ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card" style="border:1px solid rgba(212,175,55,.25)">
            <div class="card-body">
                <h5 class="card-title">Statut de la commande</h5>
                <form method="post" action="<?= htmlspecialchars($basePath) ?>/admin/orders/status">
                    <input type="hidden" name="id" value="<?= (int)$order['id'] ?>">
                    <div class="mb-3">
                        <select class="form-select" name="status">
                            <?php foreach ($statuses as $key => $label): ?>
                                <option value="<?= htmlspecialchars($key) ?>" <?= $order['status'] === $key ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button class="btn btn-warning btn-sm" type="submit">Mettre à jour</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="card" style="border:1px solid rgba(212,175,55,.25)">
    <div class="table-responsive">
        <table class="table table-borderless mb-0">
            <thead>
            <tr>
                <th>Produit</th>
                <th class="text-end">Prix unitaire</th>
                <th class="text-center">Quantité</th>
                <th class="text-end">Total</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($order['details'] as $detail): ?>
                <tr>
                    <td><?= htmlspecialchars((string)$detail['product_name']) ?></td>
                    <td class="text-end"><?= number_format((float)$detail['unit_price'], 2, '.', ' ') ?> FCFA</td>
                    <td class="text-center"><?= (int)$detail['quantity'] ?></td>
                    <td class="text-end"><?= number_format((float)$detail['line_total'], 2, '.', ' ') ?> FCFA</td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
            <tr>
                <th colspan="3" class="text-end">Montant total</th>
                <th class="text-end"><?= number_format((float)$order['total_amount'], 2, '.', ' ') ?> FCFA</th>
            </tr>
            </tfoot>
        </table>
    </div>
</div>

<div class="card mt-3" style="border:1px solid rgba(212,175,55,.25)">
    <div class="card-body">
        <h5>Historique des événements</h5>
        <?php
        $events = [];
        try {
            $events = (new \App\Models\OrderEvent())->findByOrderId((int)$order['id']);
        } catch (\Throwable $e) {
        }
        ?>
        <?php if (empty($events)): ?>
            <p class="text-muted">Aucun événement enregistré.</p>
        <?php else: ?>
            <ul class="list-group list-group-flush">
                <?php foreach ($events as $ev): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div>
                            <div><strong><?= htmlspecialchars($ev['status']) ?></strong></div>
                            <div class="small text-muted">Acteur: <?= htmlspecialchars($ev['actor_type'] === 'seller' ? ($ev['seller_name'] ?? 'vendeur') : ($ev['admin_email'] ?? 'admin')) ?> • <?= htmlspecialchars($ev['created_at']) ?></div>
                            <?php if (!empty($ev['note'])): ?><div class="small"><?= nl2br(htmlspecialchars($ev['note'])) ?></div><?php endif; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</div>
