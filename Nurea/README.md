# Glow Store — Installation rapide

Prérequis: PHP 7.4+ (ou 8.x), MySQL, Composer (optionnel), XAMPP pour dev local.

Étapes d'installation locale

1. Importer le schéma SQL:

```bash
mysql -u root -p your_database < database/schema.sql
```

2. Générer un mot de passe admin et insérer dans le seed:

```bash
php scripts/generate_admin_hash.php admin123
# Remplacer PASSWORD_HASH_PLACEHOLDER dans database/seeds/seed_sample.sql par la valeur affichée
mysql -u root -p your_database < database/seeds/seed_sample.sql
```

3. Configurer la connexion DB: éditer `config/config.php` selon votre environnement.

4. Placer les fichiers dans le webroot (XAMPP `htdocs`), puis accéder à l'application.

Notes:
- Le script `scripts/generate_admin_hash.php` produit un hash compatible avec `password_verify`.
- Pour la production, configurez HTTPS, sauvegardes et variables d'environnement.
