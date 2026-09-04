# Sécurité & GDPR - Glow Store

## Sécurité applicative
- Mot de passe : stocker en `password_hash` via `password_hash()` (PHP), préférer `PASSWORD_ARGON2I`/`ARGON2ID` ou `bcrypt`.
- Requêtes préparées (PDO) pour toutes interactions avec la base.
- Protection CSRF : token pour formulaires sensibles (login, checkout, admin actions).
- Validation & sanitation serveur : valider types, longueurs, formats (email, téléphone).
- Uploads images : vérifier type MIME, extension autorisée, taille max (ex. 2MB), stockage hors web root ou nommages uniques.
- Sessions : cookies `Secure`, `HttpOnly`, `SameSite=Lax`/`Strict` en production.
- Limitation brute-force : lockout après N tentatives, ou rate limiting IP.

## Conformité GDPR
- Consentement : informer et obtenir consentement pour collecte données personnelles lors du checkout (case à cocher si nécessaire).
- Droit d'accès/suppression : implémenter endpoints pour exporter/supprimer données utilisateur sur demande (admin ou via workflow).
- Période de rétention : définir durée de conservation des données clients (ex. 5 ans pour transactions comptables).
- Logs : anonymiser données sensibles dans logs.

## Bonnes pratiques opérationnelles
- HTTPS obligatoire en production (Let's Encrypt possible).
- Sauvegardes régulières DB + export des fichiers uploads.
- Monitoring basique (uptime, erreurs 5xx).
