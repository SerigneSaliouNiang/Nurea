<div class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="h4 mb-0" style="color:#d4af37;">Catégories</h1>
    <a class="btn btn-warning btn-sm" href="<?= htmlspecialchars($basePath) ?>/admin/categories/create">Ajouter</a>
</div>

<div class="card" style="border:1px solid rgba(212,175,55,.25)">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead>
            <tr>
                <th style="width:90px">ID</th>
                <th>Nom</th>
                <th style="width:220px" class="text-end">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($categories)): ?>
                <tr>
                    <td colspan="3" class="text-secondary">Aucune catégorie.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($categories as $cat): ?>
                    <tr>
                        <td><?= (int)$cat['id'] ?></td>
                        <td><?= htmlspecialchars((string)$cat['name']) ?></td>
                        <td class="text-end">
                            <a class="btn btn-outline-warning btn-sm" href="<?= htmlspecialchars($basePath) ?>/admin/categories/edit?id=<?= (int)$cat['id'] ?>">Modifier</a>
                            <form method="post" action="<?= htmlspecialchars($basePath) ?>/admin/categories/delete" class="d-inline" onsubmit="return confirm('Supprimer cette catégorie ?')">
                                <input type="hidden" name="id" value="<?= (int)$cat['id'] ?>">
                                <button class="btn btn-outline-danger btn-sm" type="submit">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
