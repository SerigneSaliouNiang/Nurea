<div class="row justify-content-center">
    <div class="col-12 col-md-6 col-lg-5">
        <div class="card auth-login-card">
            <div class="card-body">
                <h1 class="h4 mb-3" style="color:#d4af37;">Connexion vendeur</h1>

                <form method="post" action="<?= htmlspecialchars($basePath) ?>/seller/login" class="d-grid gap-3">
                    <?= \App\Core\Csrf::field() ?>
                    <div>
                        <label class="form-label">Email</label>
                        <input name="email" type="email" class="form-control" required>
                    </div>
                    <div>
                        <label class="form-label">Mot de passe</label>
                        <input name="password" type="password" class="form-control" required>
                    </div>
                    <button class="btn btn-warning" type="submit">Se connecter</button>
                </form>

            </div>
        </div>
    </div>
</div>
