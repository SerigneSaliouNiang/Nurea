-- Migration 003: Ajoute le champ password_changed_at sur sellers

ALTER TABLE sellers
  ADD COLUMN password_changed_at DATETIME NULL AFTER password_hash;
