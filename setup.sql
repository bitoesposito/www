-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versione server:              8.4.3 - MySQL Community Server - GPL
-- S.O. server:                  Win64
-- HeidiSQL Versione:            12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dump della struttura del database blog
CREATE DATABASE IF NOT EXISTS `blog` /*!40100 DEFAULT CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `blog`;

-- Dump della struttura di tabella blog.posts
CREATE TABLE IF NOT EXISTS `posts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `message` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `datecreated` datetime NOT NULL,
  `email` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_title` (`title`),
  KEY `idx_user_id` (`user_id`),
  CONSTRAINT `f_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Dump dei dati della tabella blog.posts: ~3 rows (circa)
DELETE FROM `posts`;
INSERT INTO `posts` (`id`, `user_id`, `title`, `message`, `datecreated`, `email`) VALUES
	(1, 1, 'Demo post 1', 'Demo message for post 1', '2025-05-22 16:58:08', 'admin@mail.com'),
	(8, 2, 'Demo post 2', 'Demo message for post 2', '2025-05-23 09:26:05', 'editor@mail.com'),
	(9, 9, 'Demo post 3', 'Demo message for post 3', '2025-05-23 09:36:43', 'user@mail.com');

-- Dump della struttura di tabella blog.postscomments
CREATE TABLE IF NOT EXISTS `postscomments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `post_id` int NOT NULL,
  `comment` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `datecreated` datetime NOT NULL,
  `email` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `user_id` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_post_id` (`post_id`),
  KEY `idx_user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Dump dei dati della tabella blog.postscomments: ~3 rows (circa)
DELETE FROM `postscomments`;
INSERT INTO `postscomments` (`id`, `post_id`, `comment`, `datecreated`, `email`, `user_id`) VALUES
	(1, 1, 'My first comment', '2025-05-22 14:43:58', 'test@mail.com', 0),
	(2, 1, 'My second comment', '2025-05-22 14:45:18', 'test@mail.com', 0),
	(3, 1, 'My third comment', '2025-05-22 14:45:36', 'test@mail.com', 0);

-- Dump della struttura di tabella blog.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fiscalcode` char(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `age` smallint unsigned DEFAULT NULL,
  `avatar` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `roletype` enum('admin','editor','user') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id`),
  UNIQUE KEY `u_fiscalcode` (`fiscalcode`),
  KEY `i_email` (`email`),
  KEY `i_username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dump dei dati della tabella blog.users: ~3 rows (circa)
DELETE FROM `users`;
INSERT INTO `users` (`id`, `username`, `email`, `fiscalcode`, `age`, `avatar`, `password`, `roletype`) VALUES
	(1, 'admin', 'admin@mail.com', NULL, NULL, NULL, '$2y$10$xkJhZJLJyiGqFb1WdzTy.e550GZ7vnV699buz9AkNgn0P5FNHegGG', 'admin'),
	(2, 'editor', 'editor@mail.com', NULL, NULL, NULL, '$2y$10$xkJhZJLJyiGqFb1WdzTy.e550GZ7vnV699buz9AkNgn0P5FNHegGG', 'editor'),
	(9, 'user', 'user@mail.com', NULL, NULL, NULL, '$2y$10$xkJhZJLJyiGqFb1WdzTy.e550GZ7vnV699buz9AkNgn0P5FNHegGG', 'user');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
