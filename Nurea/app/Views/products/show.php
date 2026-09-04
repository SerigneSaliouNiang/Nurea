<div class="mb-4">
    <a class="btn btn-light btn-sm" href="<?= htmlspecialchars($basePath) ?>/products">← Retour au catalogue</a>
</div>

<div class="row g-4 align-items-start">
    <div class="col-12 col-lg-7">
        <div id="productCarousel" class="carousel slide product-show-card" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <?php if (!empty($product['image'])): ?>
                        <img src="<?= htmlspecialchars((!empty($basePath) && str_starts_with((string)$product['image'], '/')) ? ($basePath . (string)$product['image']) : (string)$product['image']) ?>" alt="<?= htmlspecialchars((string)$product['name']) ?>" class="d-block w-100 product-show-image" />
                    <?php else: ?>
                        <div class="d-flex align-items-center justify-content-center" style="min-height:320px; color:var(--muted); background:var(--cream);">Sans image</div>
                    <?php endif; ?>
                </div>
                <div class="carousel-item">
                    <div class="product-carousel-placeholder d-flex align-items-center justify-content-center">
                        <div class="text-center px-4">
                            <h3 class="h5 mb-3">Détails du soin</h3>
                            <p class="mb-0" style="color:var(--text-light);">Profitez d’une vue claire et raffinée de ce produit avant de l’ajouter à votre panier.</p>
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Précédent</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Suivant</span>
            </button>
        </div>
    </div>
    <div class="col-12 col-lg-5">
        <div class="card p-4">
            <span class="category-pill d-inline-block mb-3"><?= htmlspecialchars((string)($product['category_name'] ?? '')) ?></span>
            <h1 class="h2 mb-2"><?= htmlspecialchars((string)$product['name']) ?></h1>
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="product-price display-6"><?= number_format((float)$product['price'], 0, '.', ' ') ?> <small class="fs-6" style="color:var(--muted);">FCFA</small></div>
                <span class="stock-indicator <?= ((int)$product['stock'] > 0) ? 'in-stock' : 'out-stock' ?>">
                    <?= ((int)$product['stock'] > 0) ? 'Disponible' : 'Rupture' ?>
                </span>
            </div>
            <?php if (!empty($product['description'])): ?>
                <p class="mb-4" style="white-space:pre-wrap; line-height:1.8; color:var(--text-light);"><?= htmlspecialchars((string)$product['description']) ?></p>
            <?php else: ?>
                <p class="mb-4" style="color:var(--muted);">Cette fiche produit ne contient pas encore de description détaillée.</p>
            <?php endif; ?>

            <form method="post" action="<?= htmlspecialchars($basePath) ?>/cart/add" class="d-flex flex-column gap-3">
                <?= \App\Core\Csrf::field() ?>
                <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
                <input type="hidden" name="redirect" value="<?= htmlspecialchars((string)($_SERVER['REQUEST_URI'] ?? ($basePath . '/products'))) ?>">
                <div class="d-flex gap-2 align-items-center">
                    <input class="form-control" type="number" name="qty" value="1" min="1" max="<?= (int)$product['stock'] ?>" style="width:110px">
                    <button class="btn btn-warning flex-grow-1" type="submit" <?= ((int)$product['stock'] <= 0) ? 'disabled' : '' ?>>Ajouter au panier</button>
                </div>
                <div class="small" style="color:var(--muted);">Paiement à la livraison. Livraison sous 3 à 5 jours ouvrés.</div>
            </form>
        </div>
    </div>
</div>
