<div class="p-4 p-md-5 hero-parallax reveal" data-animate>
    <div class="hero-logo-wrap">
        <img class="brand-logo-lg" src="<?= htmlspecialchars($basePath) ?>/assets/img/logo.png" alt="<?= htmlspecialchars($appName) ?>" onerror="this.style.display='none'">
        <div>
            <h1 class="display-6 mb-2"><?= htmlspecialchars($appName) ?></h1>
            <p class="mb-3" style="color:var(--text-light);">Soins, maquillage et cosmétiques pour sublimer votre beauté au quotidien.</p>
            <div class="d-flex flex-wrap gap-2">
                <a class="btn btn-warning btn-sm" href="<?= htmlspecialchars($basePath) ?>/products">Découvrir le catalogue</a>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mt-4 reveal">
    <div class="col-12 col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <h2 class="h5" style="color:var(--terracotta);">Nouveautés</h2>
                <p class="mb-0" style="color:var(--text-light);">Les derniers soins et produits sélectionnés pour vous chaque semaine.</p>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <h2 class="h5" style="color:var(--terracotta);">Livraison douce</h2>
                <p class="mb-0" style="color:var(--text-light);">Commandez en toute confiance, payez à la livraison.</p>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <h2 class="h5" style="color:var(--terracotta);">Conseils beauté</h2>
                <p class="mb-0" style="color:var(--text-light);">Une sélection pensée pour chaque type de peau et chaque besoin.</p>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($categories)): ?>
    <div class="mt-5 mb-3">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h2 class="h5 mb-0">Nos catégories</h2>
            <a class="btn btn-outline-warning btn-sm" href="<?= htmlspecialchars($basePath) ?>/products">Voir tout</a>
        </div>
        <div class="row g-3">
            <?php foreach ($categories as $category): ?>
                <div class="col-6 col-md-3">
                    <a class="text-decoration-none" href="<?= htmlspecialchars($basePath) ?>/products?category_id=<?= (int)$category['id'] ?>">
                        <div class="card h-100 text-center p-3" style="border:1px solid var(--border);">
                            <div class="card-body d-flex align-items-center justify-content-center">
                                <h3 class="h6 mb-0" style="color:var(--terracotta);"><?= htmlspecialchars((string)$category['name']) ?></h3>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<?php if (!empty($featuredProducts)): ?>
    <div class="mt-5 mb-3">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h2 class="h5 mb-0">Nos coups de cœur</h2>
            <a class="btn btn-outline-warning btn-sm" href="<?= htmlspecialchars($basePath) ?>/products">Découvrir plus</a>
        </div>
        <div class="row g-4">
            <?php foreach ($featuredProducts as $product): ?>
                <div class="col-12 col-sm-6 col-lg-4">
                    <a class="text-decoration-none" href="<?= htmlspecialchars($basePath) ?>/product?id=<?= (int)$product['id'] ?>">
                        <div class="card h-100 product-card" style="border:1px solid var(--border);">
                            <div class="ratio ratio-1x1 overflow-hidden">
                                <?php if (!empty($product['image'])): ?>
                                    <img src="<?= htmlspecialchars((!empty($basePath) && str_starts_with((string)$product['image'], '/')) ? ($basePath . (string)$product['image']) : (string)$product['image']) ?>" alt="<?= htmlspecialchars((string)$product['name']) ?>" class="product-card-image" />
                                <?php else: ?>
                                    <div class="d-flex align-items-center justify-content-center" style="color:var(--muted); background:var(--cream);">Sans image</div>
                                <?php endif; ?>
                            </div>
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="category-pill"><?= htmlspecialchars((string)($product['category_name'] ?? '')) ?></span>
                                    <span class="product-price"><?= number_format((float)$product['price'], 0, '.', ' ') ?> FCFA</span>
                                </div>
                                <h3 class="h6 product-card-title mb-2"><?= htmlspecialchars((string)$product['name']) ?></h3>
                                <div class="d-flex align-items-center justify-content-between mt-auto pt-2">
                                    <span class="stock-indicator <?= ((int)$product['stock'] > 0) ? 'in-stock' : 'out-stock' ?>">
                                        <?= ((int)$product['stock'] > 0) ? 'Disponible' : 'Rupture' ?>
                                    </span>
                                    <span class="btn btn-sm btn-outline-warning">Voir</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
