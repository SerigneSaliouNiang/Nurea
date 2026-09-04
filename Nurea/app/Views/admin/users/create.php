<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h1 class="h4 mb-0" style="color:#d4af37;">Ajouter un utilisateur</h1>
        <p class="text-muted mb-0">Créez un administrateur ou un vendeur.</p>
    </div>
    <a class="btn btn-outline-warning btn-sm" href="<?= htmlspecialchars($basePath) ?>/admin/users">Retour</a>
</div>

<div class="card p-3">
    <form method="post" action="<?= htmlspecialchars($basePath) ?>/admin/users/create" class="d-grid gap-3">
        <div>
            <label class="form-label">Rôle</label>
            <select name="role" class="form-select">
                <option value="seller">Vendeur</option>
                <option value="admin">Administrateur</option>
            </select>
        </div>
        <div>
            <label class="form-label">Nom (si vendeur)</label>
            <input name="name" type="text" class="form-control">
        </div>
        <div>
            <label class="form-label">Email</label>
            <input name="email" type="email" class="form-control" required>
        </div>
        <div>
            <label class="form-label">Mot de passe (laisser vide pour le vendeur pour en générer un plus tard)</label>
            <input name="password" type="password" class="form-control">
        </div>
        <button class="btn btn-warning" type="submit">Créer</button>
    </form>
</div>
