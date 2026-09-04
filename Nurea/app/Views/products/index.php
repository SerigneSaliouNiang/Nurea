<div class="catalog-hero p-4 p-md-5 mb-4">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <div>
            <span class="badge mb-2" style="background:var(--rose-deep); color:var(--terracotta-dark);">Collection Beauté</span>
            <h1 class="display-6 mb-2">Catalogue</h1>
            <p class="mb-0" style="color:var(--text-light);">Soins, maquillage et produits cosmétiques choisis pour sublimer votre routine.</p>
        </div>
        <a class="btn btn-outline-warning btn-lg" href="<?= htmlspecialchars($basePath) ?>/cart">Mon panier</a>
    </div>
</div>

<div class="card mb-4 border-0" style="background:var(--cream);">
    <div class="card-body p-4">
        <form method="get" action="<?= htmlspecialchars($basePath) ?>/products" class="row g-3">
            <div class="col-12 col-md-5">
                <label class="form-label">Recherche</label>
                <input class="form-control" name="q" value="<?= htmlspecialchars((string)$q) ?>" placeholder="Masque, crème, rouge à lèvres...">
            </div>
            <div class="col-12 col-md-4">
                <label class="form-label">Catégorie</label>
                <select class="form-select" name="category_id">
                    <option value="">Toutes</option>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= (int)$c['id'] ?>" <?= ((int)($categoryId ?? 0) === (int)$c['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars((string)$c['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 col-md-3">
                <label class="form-label">Trier par</label>
                <select class="form-select" name="sort">
                    <option value="newest" <?= ($sort === 'newest') ? 'selected' : '' ?>>Nouveautés</option>
                    <option value="price_asc" <?= ($sort === 'price_asc') ? 'selected' : '' ?>>Prix croissant</option>
                    <option value="price_desc" <?= ($sort === 'price_desc') ? 'selected' : '' ?>>Prix décroissant</option>
                    <option value="name_asc" <?= ($sort === 'name_asc') ? 'selected' : '' ?>>Nom A-Z</option>
                </select>
            </div>
            <div class="col-12 d-flex flex-wrap gap-2">
                <button class="btn btn-warning" type="submit">Filtrer</button>
                <a class="btn btn-outline-warning" href="<?= htmlspecialchars($basePath) ?>/products">Réinitialiser</a>
            </div>
        </form>
    </div>
</div>

<?php if (empty($products)): ?>
    <div class="text-center py-5" style="color:var(--muted);">
        <p class="h5">Aucun produit trouvé.</p>
        <a class="btn btn-outline-warning mt-3" href="<?= htmlspecialchars($basePath) ?>/products">Réinitialiser les filtres</a>
    </div>
<?php else: ?>
    <div class="row g-4">
        <?php foreach ($products as $p): ?>
            <div class="col-12 col-sm-6 col-lg-4">
                <a href="<?= htmlspecialchars($basePath) ?>/product?id=<?= (int)$p['id'] ?>" class="text-decoration-none" style="color:var(--text);">
                    <div class="card h-100 product-card">
                        <div class="ratio ratio-1x1 overflow-hidden">
                            <?php if (!empty($p['image'])): ?>
                                <img src="<?= htmlspecialchars((!empty($basePath) && str_starts_with((string)$p['image'], '/')) ? ($basePath . (string)$p['image']) : (string)$p['image']) ?>" alt="<?= htmlspecialchars((string)$p['name']) ?>" class="product-card-image" />
                            <?php else: ?>
                                <div class="d-flex align-items-center justify-content-center" style="color:var(--muted); background:var(--cream);">Sans image</div>
                            <?php endif; ?>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="category-pill"><?= htmlspecialchars((string)($p['category_name'] ?? '')) ?></span>
                                <span class="product-price"><?= number_format((float)$p['price'], 0, '.', ' ') ?> FCFA</span>
                            </div>
                            <h2 class="h6 product-card-title mb-2"><?= htmlspecialchars((string)$p['name']) ?></h2>
                            <div class="d-flex align-items-center justify-content-between mt-auto pt-3">
                                <span class="stock-indicator <?= ((int)$p['stock'] > 0) ? 'in-stock' : 'out-stock' ?>">
                                    <?= ((int)$p['stock'] > 0) ? 'Disponible' : 'Rupture' ?>
                                </span>
                                <span class="btn btn-sm btn-outline-warning">Voir</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
