# User Stories détaillées - MVP Glow Store

## Epic: Navigation & Découverte
- Story: En tant que visiteur, je peux voir la liste des produits afin de parcourir l'offre.
  - Acceptance: Grille paginée avec image, nom, prix et état stock.

- Story: En tant que visiteur, je peux filtrer et trier les produits.
  - Acceptance: Filtres par catégorie et tri par prix fonctionnels.

## Epic: Page produit
- Story: En tant que visiteur, je peux consulter la fiche produit.
  - Acceptance: Galerie d'images, description, prix, disponibilité et bouton "Ajouter au panier".

## Epic: Panier & Checkout
- Story: En tant qu'utilisateur, je peux gérer le panier (ajouter/retirer/modifier quantités).
  - Acceptance: Totaux et quantités mis à jour dynamiquement.

- Story: En tant que client, je peux commander sans créer de compte (guest checkout).
  - Acceptance: Formulaire guest valide, création d'une commande avec statut `en_attente`, confirmation affichée.

## Epic: Compte client (optionnel)
- Story: En tant que client, je peux créer un compte pour suivre mes commandes.
  - Acceptance: Inscription, connexion, page profil affichant historique commandes.

## Epic: Administration
- Story: En tant qu'admin, je peux me connecter au Back Office.
  - Acceptance: Login sécurisé, redirection vers dashboard.

- Story: En tant qu'admin, je peux créer/modifier/supprimer produits et catégories.
  - Acceptance: Opérations CRUD persistantes et visibles en front.

- Story: En tant qu'admin, je peux consulter la liste des commandes et changer leur statut.
  - Acceptance: Liste filtrable, modification statut enregistré.

## Epic: Reporting & Export
- Story: En tant qu'admin, je peux exporter les commandes au format CSV par période.
  - Acceptance: Export CSV téléchargeable contenant les champs requis.

## Tests d'acceptation transverses
- Test: création commande guest -> vérifier en base, montant, statut.
- Test: ajout produit admin -> visible en front.
- Test: export CSV -> contenu et format valides.
