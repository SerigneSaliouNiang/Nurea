<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h1 class="h4 mb-0" style="color:#d4af37;">Commandes</h1>
        <p class="text-muted mb-0">Liste des commandes, filtrage par statut et date.</p>
    </div>
    <a class="btn btn-warning btn-sm" href="<?= htmlspecialchars($basePath) ?>/admin">Retour au dashboard</a>
</div>

<form class="row g-2 mb-4" method="get" action="<?= htmlspecialchars($basePath) ?>/admin/orders">
    <div class="col-md-3">
        <label class="form-label">Statut</label>
        <select class="form-select" name="status">
            <option value="">Tous</option>
            <?php foreach ($statuses as $key => $label): ?>
                <option value="<?= htmlspecialchars($key) ?>" <?= $selectedStatus === $key ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Du</label>
        <input type="date" class="form-control" name="from" value="<?= htmlspecialchars($from) ?>">
    </div>
    <div class="col-md-3">
        <label class="form-label">Au</label>
        <input type="date" class="form-control" name="to" value="<?= htmlspecialchars($to) ?>">
    </div>
    <div class="col-md-3 d-flex align-items-end gap-2">
        <button class="btn btn-warning btn-sm" type="submit">Filtrer</button>
        <a class="btn btn-outline-secondary btn-sm" href="<?= htmlspecialchars($basePath) ?>/admin/orders">Réinitialiser</a>
        <a class="btn btn-outline-warning btn-sm" href="<?= htmlspecialchars($basePath) ?>/admin/orders/export-payments?from=<?= htmlspecialchars($from) ?>&to=<?= htmlspecialchars($to) ?>">Exporter paiements (CSV)</a>
    </div>
</form>

<div class="card" style="border:1px solid rgba(212,175,55,.25)">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Montant</th>
                <th>Statut</th>
                <th>Date</th>
                <th class="text-end">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($orders)): ?>
                <tr>
                    <td colspan="6" class="text-secondary">Aucune commande trouvée.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= (int)$order['id'] ?></td>
                        <td><?= htmlspecialchars((string)$order['guest_nom']) ?> <?= htmlspecialchars((string)$order['guest_prenom']) ?></td>
                        <td><?= number_format((float)$order['total_amount'], 2, '.', ' ') ?> FCFA</td>
                        <td><?= htmlspecialchars((string)($statuses[$order['status']] ?? $order['status'])) ?></td>
                        <td><?= htmlspecialchars((string)$order['created_at']) ?></td>
                        <td class="text-end">
                            <a class="btn btn-outline-warning btn-sm" href="<?= htmlspecialchars($basePath) ?>/admin/orders/show?id=<?= (int)$order['id'] ?>">Voir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
