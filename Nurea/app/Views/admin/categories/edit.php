<div class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="h4 mb-0" style="color:#d4af37;">Modifier la catégorie</h1>
    <a class="btn btn-outline-warning btn-sm" href="<?= htmlspecialchars($basePath) ?>/admin/categories">Retour</a>
</div>

<div class="card" style="border:1px solid rgba(212,175,55,.25)">
    <div class="card-body p-4">
        <form method="post" action="<?= htmlspecialchars($basePath) ?>/admin/categories/edit" class="d-grid gap-3">
            <?= \App\Core\Csrf::field() ?>
            <input type="hidden" name="id" value="<?= (int)$category['id'] ?>">
            <div>
                <label class="form-label">Nom</label>
                <input class="form-control" name="name" value="<?= htmlspecialchars((string)$category['name']) ?>" required>
            </div>
            <div class="d-flex justify-content-end">
                <button class="btn btn-warning" type="submit">Mettre à jour</button>
            </div>
        </form>
    </div>
</div>
