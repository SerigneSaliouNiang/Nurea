<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h1 class="h4 mb-0" style="color:#d4af37;">Gestion des utilisateurs</h1>
        <p class="text-muted mb-0">Admins et vendeurs</p>
    </div>
    <a class="btn btn-warning btn-sm" href="<?= htmlspecialchars($basePath) ?>/admin/users/create">Ajouter</a>
</div>

<div class="row g-3">
    <div class="col-12 col-md-6">
        <div class="card p-3">
            <h5>Administrateurs</h5>
            <?php if (empty($admins)): ?>
                <p class="text-muted">Aucun administrateur enregistré.</p>
            <?php else: ?>
                <ul class="list-group list-group-flush">
                    <?php foreach ($admins as $a): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div><?= htmlspecialchars($a['email']) ?> <div class="small text-muted">Crée le <?= htmlspecialchars($a['created_at']) ?></div></div>
                            <div class="d-flex gap-2">
                                <a class="btn btn-sm btn-outline-warning" href="<?= htmlspecialchars($basePath) ?>/admin/users/edit?id=<?= (int)$a['id'] ?>&role=admin">Modifier</a>
                                <form method="post" action="<?= htmlspecialchars($basePath) ?>/admin/users/delete" class="m-0" onsubmit="return confirm('Supprimer cet administrateur ?');">
                                    <input type="hidden" name="id" value="<?= (int)$a['id'] ?>">
                                    <input type="hidden" name="role" value="admin">
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Supprimer</button>
                                </form>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="card p-3">
            <h5>Vendeurs</h5>
            <?php if (empty($sellers)): ?>
                <p class="text-muted">Aucun vendeur enregistré.</p>
            <?php else: ?>
                <ul class="list-group list-group-flush">
                    <?php foreach ($sellers as $s): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong><?= htmlspecialchars($s['name']) ?></strong>
                                <div class="small text-muted"><?= htmlspecialchars($s['email']) ?> • Crée le <?= htmlspecialchars($s['created_at']) ?></div>
                            </div>
                            <div class="d-flex gap-2">
                                <a class="btn btn-sm btn-outline-warning" href="<?= htmlspecialchars($basePath) ?>/admin/users/edit?id=<?= (int)$s['id'] ?>&role=seller">Modifier</a>
                                <form method="post" action="<?= htmlspecialchars($basePath) ?>/admin/users/delete" class="m-0" onsubmit="return confirm('Supprimer ce vendeur ?');">
                                    <input type="hidden" name="id" value="<?= (int)$s['id'] ?>">
                                    <input type="hidden" name="role" value="seller">
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Supprimer</button>
                                </form>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>
