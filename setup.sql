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


-- Dump della struttura del database corsophp
CREATE DATABASE IF NOT EXISTS `corsophp` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `corsophp`;

-- Dump della struttura di tabella corsophp.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `email` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `fiscalcode` char(16) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `age` smallint unsigned NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `roletype` enum('user','editor','admin') COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id`),
  UNIQUE KEY `u_fiscalcode` (`fiscalcode`),
  KEY `i_email` (`email`),
  KEY `i_username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=676 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Dump dei dati della tabella corsophp.users: ~3 rows (circa)
DELETE FROM `users`;
INSERT INTO `users` (`id`, `username`, `email`, `fiscalcode`, `age`, `avatar`, `password`, `roletype`) VALUES
	(673, 'admin', 'admin@mail.com', 'ASDASDASDASDASDF', 22, '', '$2y$10$xkJhZJLJyiGqFb1WdzTy.e550GZ7vnV699buz9AkNgn0P5FNHegGG', 'admin'),
	(674, 'user', 'user@mail.com', 'ASDASDASDASDASD4', 22, '', '$2y$10$xkJhZJLJyiGqFb1WdzTy.e550GZ7vnV699buz9AkNgn0P5FNHegGG', 'user'),
	(675, 'editor', 'editor@mail.com', 'ASDASDASDASDASDM', 22, '', '$2y$10$xkJhZJLJyiGqFb1WdzTy.e550GZ7vnV699buz9AkNgn0P5FNHegGG', 'editor');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
