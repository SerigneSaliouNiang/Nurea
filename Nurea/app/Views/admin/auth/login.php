<div class="auth-login-wrapper">
    <div class="row justify-content-center w-100">
        <div class="col-12 col-md-6 col-lg-5">
            <div class="card auth-login-card">
                <div class="card-body">
                            <form method="post" action="<?= htmlspecialchars($basePath) ?>/admin/login" class="d-grid gap-3">
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
                    <div class="text-center mt-3">
                        <a class="small" href="<?= htmlspecialchars($basePath) ?>/">← Retour à la page d'accueil</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
