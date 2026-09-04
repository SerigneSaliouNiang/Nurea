<div class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="h4 mb-0" style="color:#d4af37;">Modifier le produit</h1>
    <a class="btn btn-outline-warning btn-sm" href="<?= htmlspecialchars($basePath) ?>/admin/products">Retour</a>
</div>

<div class="card" style="border:1px solid rgba(212,175,55,.25)">
    <div class="card-body p-4">
        <form method="post" action="<?= htmlspecialchars($basePath) ?>/admin/products/edit" enctype="multipart/form-data" class="d-grid gap-3">
            <input type="hidden" name="id" value="<?= (int)$product['id'] ?>">

            <div>
                <label class="form-label">Nom</label>
                <input class="form-control" name="name" value="<?= htmlspecialchars((string)$product['name']) ?>" required>
            </div>

            <div>
                <label class="form-label">Catégorie</label>
                <select class="form-select" name="category_id">
                    <option value="">—</option>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= (int)$c['id'] ?>" <?= ((int)$product['category_id'] === (int)$c['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars((string)$c['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <label class="form-label">Prix</label>
                    <input class="form-control" name="price" inputmode="decimal" value="<?= htmlspecialchars((string)$product['price']) ?>" required>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label">Stock</label>
                    <input class="form-control" name="stock" inputmode="numeric" value="<?= (int)$product['stock'] ?>" required>
                </div>
            </div>

            <div>
                <label class="form-label">Description</label>
                <textarea class="form-control" rows="4" name="description"><?= htmlspecialchars((string)($product['description'] ?? '')) ?></textarea>
            </div>

            <div>
                <label class="form-label">Image</label>
                <input class="form-control" type="file" name="image" accept="image/png,image/jpeg,image/webp">
                <?php if (!empty($product['image'])): ?>
                    <div class="mt-2">
                        <img src="<?= htmlspecialchars((string)$product['image']) ?>" alt="" width="90" height="90" style="object-fit:cover; border-radius:12px; border:1px solid rgba(212,175,55,.25)">
                    </div>
                <?php endif; ?>
                <div class="text-secondary mt-1" style="font-size:.9rem">Uploader une nouvelle image remplacera l'ancienne.</div>
            </div>

            <div class="d-flex justify-content-end">
                <button class="btn btn-warning" type="submit">Mettre à jour</button>
            </div>
        </form>
    </div>
</div>
