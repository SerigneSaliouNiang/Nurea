-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 04 sep. 2026 à 18:00
-- Version du serveur : 8.4.7
-- Version de PHP : 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `nurea`
--

-- --------------------------------------------------------

--
-- Structure de la table `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` varchar(190) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_admins_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `admins`
--

INSERT INTO `admins` (`id`, `email`, `password_hash`, `created_at`) VALUES
(1, 'admin@nurea.sn', '$2y$10$aqVxwioYmm6TdnFcz6A.vO8l4GrSbITjTG0VI8EDVGUxIzJ2jEcZe', '2026-04-03 22:44:37'),
(2, 'admin@glowstore.test', '$2y$12$YsJEkTd4snBn95MgtAA1UeZ3rpcRUz7n0Ev57ZmQ0T3iYiWH/fBcy', '2026-07-27 12:16:26');

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_categories_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`) VALUES
(1, 'PARFUMS', '2026-04-03 22:45:44');

-- --------------------------------------------------------

--
-- Structure de la table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED DEFAULT NULL,
  `guest_nom` varchar(120) DEFAULT NULL,
  `guest_prenom` varchar(120) DEFAULT NULL,
  `guest_telephone` varchar(40) DEFAULT NULL,
  `guest_adresse` varchar(255) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` enum('en_attente','validee','expediee','livree') NOT NULL DEFAULT 'en_attente',
  `paid_at` datetime DEFAULT NULL,
  `paid_amount` decimal(10,2) DEFAULT NULL,
  `payment_method` enum('cash_on_delivery') NOT NULL DEFAULT 'cash_on_delivery',
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_orders_user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `guest_nom`, `guest_prenom`, `guest_telephone`, `guest_adresse`, `total_amount`, `status`, `paid_at`, `paid_amount`, `payment_method`, `created_at`) VALUES
(1, NULL, 'Diop', 'Ahmed', '768642048', 'Dakar', 5000.00, 'en_attente', NULL, NULL, 'cash_on_delivery', '2026-04-04 01:04:16'),
(2, NULL, 'diop', 'mamadou', '701062143', 'Ouakam', 5000.00, 'validee', NULL, NULL, 'cash_on_delivery', '2026-07-27 12:04:44');

-- --------------------------------------------------------

--
-- Structure de la table `order_details`
--

DROP TABLE IF EXISTS `order_details`;
CREATE TABLE IF NOT EXISTS `order_details` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED DEFAULT NULL,
  `product_name` varchar(190) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `quantity` int NOT NULL DEFAULT '1',
  `line_total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `delivery_status` enum('en_attente','packaging','termine','en_attente_livraison','en_cours_de_livraison','valide') NOT NULL DEFAULT 'en_attente',
  PRIMARY KEY (`id`),
  KEY `idx_order_details_order_id` (`order_id`),
  KEY `idx_order_details_product_id` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `product_name`, `unit_price`, `quantity`, `line_total`, `delivery_status`) VALUES
(1, 1, 1, 'ENNANCIRI', 5000.00, 1, 5000.00, 'en_attente'),
(2, 2, 1, 'ENNANCIRI', 5000.00, 1, 5000.00, 'en_attente');

-- --------------------------------------------------------

--
-- Structure de la table `order_events`
--

DROP TABLE IF EXISTS `order_events`;
CREATE TABLE IF NOT EXISTS `order_events` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` int UNSIGNED NOT NULL,
  `detail_id` int UNSIGNED DEFAULT NULL,
  `actor_type` varchar(32) NOT NULL,
  `actor_id` int UNSIGNED DEFAULT NULL,
  `status` varchar(64) NOT NULL,
  `note` text,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_order_events_order_id` (`order_id`),
  KEY `idx_order_events_detail_id` (`detail_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `order_events`
--

INSERT INTO `order_events` (`id`, `order_id`, `detail_id`, `actor_type`, `actor_id`, `status`, `note`, `created_at`) VALUES
(1, 2, NULL, 'admin', 1, 'validee', NULL, '2026-09-04 17:25:57');

-- --------------------------------------------------------

--
-- Structure de la table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` int UNSIGNED DEFAULT NULL,
  `seller_id` int UNSIGNED DEFAULT NULL,
  `name` varchar(190) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `stock` int NOT NULL DEFAULT '0',
  `image` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_products_category_id` (`category_id`),
  KEY `idx_products_seller_id` (`seller_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `products`
--

INSERT INTO `products` (`id`, `category_id`, `seller_id`, `name`, `description`, `price`, `stock`, `image`, `created_at`) VALUES
(1, 1, NULL, 'ENNANCIRI', 'Ennanciri existe plusieurs senteur', 5000.00, 48, '/assets/uploads/products/7a6befaccddabd07d125cb1c.png', '2026-04-03 22:46:41'),
(2, 1, NULL, 'Dindon', NULL, 1500.00, 10, '/assets/uploads/products/6e0deaa5ed6750b1b2047586.png', '2026-07-27 13:00:18');

-- --------------------------------------------------------

--
-- Structure de la table `sellers`
--

DROP TABLE IF EXISTS `sellers`;
CREATE TABLE IF NOT EXISTS `sellers` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(190) NOT NULL,
  `email` varchar(190) NOT NULL,
  `password_hash` varchar(255) DEFAULT NULL,
  `password_changed_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_sellers_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `sellers`
--

INSERT INTO `sellers` (`id`, `name`, `email`, `password_hash`, `password_changed_at`, `created_at`) VALUES
(2, 'Omar Diop', 'omar@nurea.sn', '$2y$10$n9yo9NgnbwKosuxQSUiOJ.XOzi1pX77aJ.ke.l7KoSgicxvhfhTl6', '2026-08-10 12:16:28', '2026-07-28 15:47:46');

-- --------------------------------------------------------

--
-- Structure de la table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(190) NOT NULL,
  `setting_value` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_settings_key` (`setting_key`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `settings`
--

INSERT INTO `settings` (`id`, `setting_key`, `setting_value`, `created_at`, `updated_at`) VALUES
(1, 'promo_banner_enabled', '1', '2026-09-04 17:28:46', '2026-09-04 17:28:54'),
(2, 'promo_banner_text', 'Nouveauté ✨ Livraison offerte dès 12 000 FCFA d\'achat .', '2026-09-04 17:28:46', '2026-09-04 17:28:54');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` varchar(190) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT '0',
  `verification_token` varchar(64) DEFAULT NULL,
  `token_expires_at` datetime DEFAULT NULL,
  `nom` varchar(120) DEFAULT NULL,
  `prenom` varchar(120) DEFAULT NULL,
  `telephone` varchar(40) DEFAULT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_users_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `fk_order_details_order_id` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_order_details_product_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `order_events`
--
ALTER TABLE `order_events`
  ADD CONSTRAINT `fk_order_events_order_id` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_category_id` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_products_seller_id` FOREIGN KEY (`seller_id`) REFERENCES `sellers` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
