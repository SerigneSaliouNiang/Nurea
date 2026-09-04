<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h1 class="h4 mb-0" style="color:#d4af37;">Paramètres du site</h1>
        <p class="text-muted mb-0">Activez ou désactivez le bandeau promotionnel et modifiez son message.</p>
    </div>
    <a class="btn btn-warning btn-sm" href="<?= htmlspecialchars($basePath) ?>/admin">Retour au dashboard</a>
</div>

<div class="card shadow-sm" style="border:1px solid rgba(212,175,55,.15)">
    <div class="card-body p-4">
        <form method="post" action="<?= htmlspecialchars($basePath) ?>/admin/settings/update">
            <?= \App\Core\Csrf::field() ?>
            <div class="form-check form-switch mb-4">
                <input class="form-check-input" type="checkbox" id="promo_banner_enabled" name="promo_banner_enabled" value="1" <?= $promoBannerEnabled ? 'checked' : '' ?> />
                <label class="form-check-label" for="promo_banner_enabled">Afficher le bandeau promotionnel</label>
            </div>
            <div class="mb-4">
                <label class="form-label" for="promo_banner_text">Texte du bandeau</label>
                <textarea class="form-control" id="promo_banner_text" name="promo_banner_text" rows="4"><?= htmlspecialchars($promoBannerText) ?></textarea>
                <div class="form-text">Ce texte s'affichera dans le bandeau en haut du site.</div>
            </div>
            <button class="btn btn-warning" type="submit">Enregistrer</button>
        </form>
    </div>
</div>
