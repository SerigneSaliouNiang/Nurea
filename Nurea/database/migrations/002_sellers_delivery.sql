-- Migration 002: Ajoute table sellers, seller_id sur products, et delivery_status sur order_details

CREATE TABLE IF NOT EXISTS sellers (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(190) NOT NULL,
  email VARCHAR(190) NOT NULL,
  password_hash VARCHAR(255) NULL,
  created_at DATETIME NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uniq_sellers_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Ajouter lien vendeur sur produits
ALTER TABLE products
  ADD COLUMN seller_id INT UNSIGNED NULL AFTER category_id,
  ADD KEY idx_products_seller_id (seller_id);

ALTER TABLE products
  ADD CONSTRAINT fk_products_seller_id FOREIGN KEY (seller_id) REFERENCES sellers(id) ON DELETE SET NULL;

-- Ajouter statut de livraison par ligne de commande (order_details)
ALTER TABLE order_details
  ADD COLUMN delivery_status ENUM('en_attente','packaging','termine','en_attente_livraison','en_cours_de_livraison','valide') NOT NULL DEFAULT 'en_attente' AFTER line_total;
