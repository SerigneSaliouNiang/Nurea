<div class="row justify-content-center">
    <div class="col-12 col-md-6 col-lg-5">
        <div class="card auth-login-card">
            <div class="card-body">
                <h1 class="h4 mb-3" style="color:#d4af37;">Premier accès - changer le mot de passe</h1>

                <form method="post" action="<?= htmlspecialchars($basePath) ?>/seller/change-password" class="d-grid gap-3">
                    <?= \App\Core\Csrf::field() ?>
                    <div>
                        <label class="form-label">Nouveau mot de passe</label>
                        <input name="password" type="password" class="form-control" required>
                    </div>
                    <div>
                        <label class="form-label">Confirmer le mot de passe</label>
                        <input name="password_confirm" type="password" class="form-control" required>
                    </div>
                    <div class="small text-muted">Le mot de passe doit contenir au moins une lettre majuscule et un caractère spécial (ex: @ #).</div>
                    <button class="btn btn-warning" type="submit">Mettre à jour</button>
                </form>

            </div>
        </div>
    </div>
</div>
