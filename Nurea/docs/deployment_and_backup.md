# Déploiement & Sauvegarde - Glow Store

## Environnement local
- XAMPP (Apache, MySQL, PHP). Reproduire PHP version cible (7.4+ ou 8.x selon compatibilité).

## Mise en production (résumé)
1. Provisionner serveur LAMP (Ubuntu recommended) ou hébergement cPanel.
2. Configurer Apache/Nginx, PHP-FPM, MySQL/MariaDB.
3. Déployer code (git pull) dans `/var/www/glow_store`.
4. Configurer `.env`/`config.php` pour accès DB, SMTP, clefs.
5. Importer migration SQL (`database/migrations/001_initial.sql`) puis lancer seeds.
6. Configurer HTTPS (Let's Encrypt).
7. Configurer tâches cron pour sauvegardes et maintenance (ex: purge tmp, generation rapports).

## Sauvegarde
- Backup DB quotidien : `mysqldump` -> stockage chiffré sur espace distant (S3, FTP), retention 30 jours minimal.
- Backup uploads : synchroniser dossier `assets/img/uploads` vers stockage externe quotidiennement.
- Test de restauration trimestriel.

## Rollback
- Garder backups avant déploiement. Si problème : restaurer dump SQL et restorer dossier uploads.
