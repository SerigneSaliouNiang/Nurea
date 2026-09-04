<div class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="h4 mb-0" style="color:#d4af37;">Ajouter un produit</h1>
    <a class="btn btn-outline-warning btn-sm" href="<?= htmlspecialchars($basePath) ?>/admin/products">Retour</a>
</div>

<div class="card" style="border:1px solid rgba(212,175,55,.25)">
    <div class="card-body p-4">
        <form method="post" action="<?= htmlspecialchars($basePath) ?>/admin/products/create" enctype="multipart/form-data" class="d-grid gap-3">
            <div>
                <label class="form-label">Nom</label>
                <input class="form-control" name="name" required>
            </div>

            <div>
                <label class="form-label">Catégorie</label>
                <select class="form-select" name="category_id">
                    <option value="">—</option>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= (int)$c['id'] ?>"><?= htmlspecialchars((string)$c['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <label class="form-label">Prix</label>
                    <input class="form-control" name="price" inputmode="decimal" value="0" required>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label">Stock</label>
                    <input class="form-control" name="stock" inputmode="numeric" value="0" required>
                </div>
            </div>

            <div>
                <label class="form-label">Description</label>
                <textarea class="form-control" rows="4" name="description"></textarea>
            </div>

            <div>
                <label class="form-label">Image</label>
                <input class="form-control" type="file" name="image" accept="image/png,image/jpeg,image/webp">
                <div class="text-secondary mt-1" style="font-size:.9rem">Formats autorisés: jpg, jpeg, png, webp</div>
            </div>

            <div class="d-flex justify-content-end">
                <button class="btn btn-warning" type="submit">Enregistrer</button>
            </div>
        </form>
    </div>
</div>
