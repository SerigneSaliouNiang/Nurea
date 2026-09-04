<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h1 class="h4 mb-0" style="color:#d4af37;">Modifier l'utilisateur</h1>
        <p class="text-muted mb-0">Éditez les informations de l'administrateur ou du vendeur.</p>
    </div>
    <a class="btn btn-outline-warning btn-sm" href="<?= htmlspecialchars($basePath) ?>/admin/users">Retour</a>
</div>

<div class="card p-3">
    <form method="post" action="<?= htmlspecialchars($basePath) ?>/admin/users/edit" class="d-grid gap-3">
        <input type="hidden" name="id" value="<?= (int)$entity['id'] ?>">
        <input type="hidden" name="role" value="<?= htmlspecialchars($role) ?>">

        <?php if ($role === 'seller'): ?>
            <div>
                <label class="form-label">Nom</label>
                <input name="name" type="text" class="form-control" value="<?= htmlspecialchars((string)$entity['name']) ?>" required>
            </div>
        <?php endif; ?>

        <div>
            <label class="form-label">Email</label>
            <input name="email" type="email" class="form-control" value="<?= htmlspecialchars((string)$entity['email']) ?>" required>
        </div>

        <div>
            <label class="form-label">Nouveau mot de passe (laisser vide pour ne pas modifier)</label>
            <input name="password" type="password" class="form-control">
        </div>

        <div class="d-flex justify-content-between">
            <button class="btn btn-warning" type="submit">Enregistrer</button>
            <a class="btn btn-outline-warning" href="<?= htmlspecialchars($basePath) ?>/admin/users">Annuler</a>
        </div>
    </form>
</div>
