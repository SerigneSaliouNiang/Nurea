<div class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="h4 mb-0" style="color:#d4af37;">Produits</h1>
    <a class="btn btn-warning btn-sm" href="<?= htmlspecialchars($basePath) ?>/admin/products/create">Ajouter</a>
</div>

<div class="card" style="border:1px solid rgba(212,175,55,.25)">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead>
            <tr>
                <th style="width:90px">ID</th>
                <th style="width:70px">Img</th>
                <th>Nom</th>
                <th>Catégorie</th>
                <th style="width:110px">Prix</th>
                <th style="width:90px">Stock</th>
                <th style="width:240px" class="text-end">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($products)): ?>
                <tr>
                    <td colspan="7" class="text-secondary">Aucun produit.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($products as $p): ?>
                    <tr>
                        <td><?= (int)$p['id'] ?></td>
                        <td>
                            <?php if (!empty($p['image'])): ?>
                                <img src="<?= htmlspecialchars((!empty($basePath) && str_starts_with((string)$p['image'], '/')) ? ($basePath . (string)$p['image']) : (string)$p['image']) ?>" alt="" width="44" height="44" style="object-fit:cover; border-radius:8px; border:1px solid rgba(212,175,55,.25)">
                            <?php else: ?>
                                <div style="width:44px;height:44px;border-radius:8px;border:1px solid rgba(212,175,55,.15)"></div>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars((string)$p['name']) ?></td>
                        <td class="text-secondary"><?= htmlspecialchars((string)($p['category_name'] ?? '—')) ?></td>
                        <td><?= number_format((float)$p['price'], 2, '.', ' ') ?></td>
                        <td><?= (int)$p['stock'] ?></td>
                        <td class="text-end">
                            <a class="btn btn-outline-warning btn-sm" href="<?= htmlspecialchars($basePath) ?>/admin/products/edit?id=<?= (int)$p['id'] ?>">Modifier</a>
                            <form method="post" action="<?= htmlspecialchars($basePath) ?>/admin/products/delete" class="d-inline" onsubmit="return confirm('Supprimer ce produit ?')">
                                <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
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
