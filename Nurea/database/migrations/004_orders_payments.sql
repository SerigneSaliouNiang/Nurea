-- Migration 004: Ajoute le suivi de paiement sur la table orders

ALTER TABLE orders
  ADD COLUMN paid_at DATETIME NULL AFTER status,
  ADD COLUMN paid_amount DECIMAL(10,2) NULL AFTER paid_at;
