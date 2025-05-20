-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Gen 20, 2019 alle 20:10
-- Versione del server: 5.7.19
-- Versione PHP: 7.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `corsophp`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(12) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `fiscalcode` char(16) COLLATE utf8_unicode_ci NOT NULL,
  `age` smallint(3) UNSIGNED NOT NULL,
  `avatar` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `roletype` enum('admin','editor','user') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id`),
  UNIQUE KEY `u_fiscalcode` (`fiscalcode`),
  KEY `i_email` (`email`),
  KEY `i_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

INSERT INTO `users` (`username`, `email`, `fiscalcode`, `age`, `avatar`, `password`, `roletype`) VALUES
('admin', 'admin@mail.com', 'ASDASDASDASDASDF', 40, NULL, '$2y$10$hhq7jolXqpZDpwA.L49W0uSpbQhan53t3lbOA7o9YASN77HCFFAZe', 'admin'),
('editor', 'editor@mail.com', 'ASDASDASDASDASDM', 35, NULL, '$2y$10$5FmVp6UPnLMssTMQ5MqkoeZ4snBcCVaPaSZNWY0x3ZeuovkYBqc1K', 'editor'),
('user', 'user@mail.com', 'ASDASDASDASDASD4', 28, NULL, '$2y$10$bxO/ELn6LCRtkNW9voy6zejiAvUMMmi/KVSdLCHN/kziKOrDIEWTi', 'user');
