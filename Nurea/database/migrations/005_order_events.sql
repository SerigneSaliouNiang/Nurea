-- Migration 005: Crée la table order_events pour tracer l'historique des statuts

CREATE TABLE IF NOT EXISTS order_events (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  order_id INT UNSIGNED NOT NULL,
  detail_id INT UNSIGNED NULL,
  actor_type VARCHAR(32) NOT NULL,
  actor_id INT UNSIGNED NULL,
  status VARCHAR(64) NOT NULL,
  note TEXT NULL,
  created_at DATETIME NOT NULL,
  PRIMARY KEY (id),
  KEY idx_order_events_order_id (order_id),
  KEY idx_order_events_detail_id (detail_id),
  CONSTRAINT fk_order_events_order_id FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;