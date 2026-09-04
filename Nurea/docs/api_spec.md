# API Spec (MVP) - Glow Store

Base: `/api/v1`

## Auth
- `POST /api/v1/admin/login` : {email, password} -> token session (admin).

## Categories
- `GET /api/v1/categories` : liste catégories.
- `POST /api/v1/admin/categories` : créer catégorie (admin).
- `PUT /api/v1/admin/categories/{id}` : update (admin).
- `DELETE /api/v1/admin/categories/{id}` : supprimer (admin).

## Products
- `GET /api/v1/products` : liste produits (filtres: q, category_id, sort, page).
- `GET /api/v1/products/{id}` : détail produit.
- `POST /api/v1/admin/products` : créer produit (multipart pour images).
- `PUT /api/v1/admin/products/{id}` : modifier produit.
- `DELETE /api/v1/admin/products/{id}` : supprimer produit.

## Cart (session)
- `POST /api/v1/cart` : add item {product_id, quantity}.
- `PUT /api/v1/cart/{product_id}` : update qty.
- `DELETE /api/v1/cart/{product_id}` : remove.
- `GET /api/v1/cart` : récupérer panier (session).

## Orders
- `POST /api/v1/orders` : créer commande (guest payload: nom, prenom, telephone, adresse, items[]).
- `GET /api/v1/admin/orders` : lister commandes (admin, filtres période/statut).
- `GET /api/v1/admin/orders/{id}` : détail commande.
- `PUT /api/v1/admin/orders/{id}/status` : changer statut.

## Seller (Vendeur)
- `POST /api/v1/seller/login` : {email, password} -> session/token for seller.
- `GET /api/v1/seller/orders` : liste des commandes contenant des produits du vendeur (filtre par statut, période).
- `GET /api/v1/seller/orders/{order_id}` : détail d'une commande limitée aux items du vendeur.
- `PUT /api/v1/seller/orders/{order_id}/items/{order_detail_id}/delivery_status` : mettre à jour `delivery_status` pour une ligne (valeurs: `en_attente`,`packaging`,`termine`,`en_attente_livraison`,`en_cours_de_livraison`,`valide`).


## Export
- `GET /api/v1/admin/exports/orders?from=YYYY-MM-DD&to=YYYY-MM-DD` : renvoie CSV.

## Reporting
- `GET /api/v1/admin/reports/sales?range=daily|monthly&from=&to=` : données séries temporelles.

## Sécurité
- Toutes routes `admin/*` protégées par authentification session/CSRF et vérification rôle.
- Input validation côté serveur obligatoire.
