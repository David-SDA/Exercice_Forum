-- --------------------------------------------------------
-- Hôte:                         127.0.0.1
-- Version du serveur:           8.0.30 - MySQL Community Server - GPL
-- SE du serveur:                Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Listage de la structure de la base pour forum
CREATE DATABASE IF NOT EXISTS `forum` /*!40100 DEFAULT CHARACTER SET latin1 */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `forum`;

-- Listage de la structure de table forum. categorie
CREATE TABLE IF NOT EXISTS `categorie` (
  `id_categorie` int NOT NULL AUTO_INCREMENT,
  `nomCategorie` varchar(50) NOT NULL,
  PRIMARY KEY (`id_categorie`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

-- Listage des données de la table forum.categorie : ~2 rows (environ)
INSERT INTO `categorie` (`id_categorie`, `nomCategorie`) VALUES
	(1, 'Sport'),
	(3, 'Automobile');

-- Listage de la structure de table forum. membre
CREATE TABLE IF NOT EXISTS `membre` (
  `id_membre` int NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `motDePasse` varchar(255) NOT NULL,
  `dateInscription` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `role` varchar(15) NOT NULL,
  PRIMARY KEY (`id_membre`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

-- Listage des données de la table forum.membre : ~6 rows (environ)
INSERT INTO `membre` (`id_membre`, `pseudo`, `email`, `motDePasse`, `dateInscription`, `role`) VALUES
	(1, 'pseudo1', 'test1@test.com', 'test1', '2023-04-30 10:40:29', 'ROLE_MEMBER'),
	(2, 'Admin', 'admin@admin.com', '$2y$10$WTUNywXJ6fkPMMyALUxET.Fv5t3PejJgKAABzd5Ce6np0iT3D8Ao.', '2023-05-02 16:53:37', 'ROLE_ADMIN'),
	(7, 'a', 'a@a.aa', '$2y$10$Qdy6WrjZ/rqdVoMkukUY1e.g2YY62c/oVSOuBQDx.SPzu31BgUN9u', '2023-05-02 11:54:58', 'ROLE_MEMBER'),
	(10, 'aa', 'aa@aa.aa', '$2y$10$gEBKnfy94UpGhjmZ4ACdtuxEWFWVXb3zYLxPsvJtLnXzL7gCQyO8O', '2023-05-03 09:32:32', 'ROLE_MEMBER'),
	(11, 'zzzzzzzzzz', 'zzz@zzz.zz', '$2y$10$ryAPL6c2Gx9M3olmlC4qjurZu4ktz0dZ1563sxVbg5BJ9/CpLaOri', '2023-05-04 15:14:06', 'ROLE_MEMBER'),
	(12, 'b', 'b@b.bb', '$2y$10$BuLe1mzbZW/TENXjx4HM6urIRZn46RzEr8kg5I2vZ96Uw9PzB.ZD.', '2023-05-05 11:18:45', 'ROLE_BAN');

-- Listage de la structure de table forum. post
CREATE TABLE IF NOT EXISTS `post` (
  `id_post` int NOT NULL AUTO_INCREMENT,
  `dateCreation` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dateDerniereModification` datetime DEFAULT CURRENT_TIMESTAMP,
  `contenu` text NOT NULL,
  `membre_id` int NOT NULL,
  `topic_id` int NOT NULL,
  PRIMARY KEY (`id_post`),
  KEY `membre_id` (`membre_id`),
  KEY `topic_id` (`topic_id`),
  CONSTRAINT `post_ibfk_1` FOREIGN KEY (`membre_id`) REFERENCES `membre` (`id_membre`),
  CONSTRAINT `post_ibfk_2` FOREIGN KEY (`topic_id`) REFERENCES `topic` (`id_topic`)
) ENGINE=InnoDB AUTO_INCREMENT=111 DEFAULT CHARSET=latin1;

-- Listage des données de la table forum.post : ~22 rows (environ)
INSERT INTO `post` (`id_post`, `dateCreation`, `dateDerniereModification`, `contenu`, `membre_id`, `topic_id`) VALUES
	(1, '2023-04-30 10:48:43', '2023-05-09 11:13:58', 'test\r\ntest\r\ntest', 7, 1),
	(2, '2023-04-30 10:49:18', '2023-05-09 11:13:58', 'test', 7, 2),
	(3, '2023-04-30 10:49:34', '2023-05-09 11:13:58', 'test', 7, 2),
	(4, '2023-04-30 10:49:40', '2023-05-09 11:13:58', 'test', 7, 2),
	(5, '2023-04-30 10:49:59', '2023-05-09 11:13:58', 'test', 7, 3),
	(6, '2023-04-30 10:50:56', '2023-05-09 11:13:58', 'test', 7, 4),
	(7, '2023-05-01 11:01:30', '2023-05-11 08:38:09', 'test', 7, 4),
	(8, '2023-05-01 11:55:23', '2023-05-09 11:13:58', 'test', 7, 4),
	(25, '2023-05-03 16:42:44', '2023-05-09 11:13:58', 'test', 2, 8),
	(28, '2023-05-04 09:19:14', '2023-05-09 11:13:58', 'zzz', 7, 4),
	(31, '2023-05-04 09:25:34', '2023-05-09 11:13:58', 'zzzzzzzzzz', 7, 4),
	(33, '2023-05-04 09:32:14', '2023-05-09 11:13:58', 'aaaa', 2, 4),
	(34, '2023-05-04 09:32:18', '2023-05-09 11:13:58', 'a', 2, 4),
	(35, '2023-05-04 09:32:21', '2023-05-09 11:13:58', 'eeee', 2, 4),
	(36, '2023-05-04 09:32:24', '2023-05-09 11:13:58', 'eeeee', 2, 4),
	(37, '2023-05-04 11:46:41', '2023-05-11 09:12:19', 'testt', 7, 4),
	(38, '2023-05-04 14:13:29', '2023-05-09 11:13:58', 'ttt', 7, 3),
	(41, '2023-05-05 09:23:15', '2023-05-11 09:15:17', 'testttttttt', 7, 4),
	(57, '2023-05-09 11:05:32', '2023-05-11 11:40:54', 'aaa', 2, 8),
	(92, '2023-05-11 11:55:49', '2023-05-11 11:55:49', 'bbbb', 12, 27),
	(97, '2023-05-11 15:18:29', '2023-05-11 16:33:07', 'ff', 2, 27),
	(105, '2023-05-12 09:36:29', '2023-05-12 09:50:02', 'aaa', 2, 8);

-- Listage de la structure de table forum. topic
CREATE TABLE IF NOT EXISTS `topic` (
  `id_topic` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(50) NOT NULL,
  `dateCreation` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `verrouiller` tinyint DEFAULT '0',
  `membre_id` int NOT NULL,
  `categorie_id` int NOT NULL,
  PRIMARY KEY (`id_topic`),
  KEY `membre_id` (`membre_id`),
  KEY `categorie_id` (`categorie_id`),
  CONSTRAINT `topic_ibfk_1` FOREIGN KEY (`membre_id`) REFERENCES `membre` (`id_membre`),
  CONSTRAINT `topic_ibfk_2` FOREIGN KEY (`categorie_id`) REFERENCES `categorie` (`id_categorie`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;

-- Listage des données de la table forum.topic : ~6 rows (environ)
INSERT INTO `topic` (`id_topic`, `titre`, `dateCreation`, `verrouiller`, `membre_id`, `categorie_id`) VALUES
	(1, 'Test 1', '2023-04-30 10:48:43', 0, 7, 1),
	(2, 'Test 2', '2023-04-30 10:49:18', 1, 7, 1),
	(3, 'Test 3', '2023-04-30 10:49:59', 0, 7, 3),
	(4, 'Test 4', '2023-04-30 10:50:56', 0, 7, 3),
	(8, 'test', '2023-05-03 16:42:44', 0, 2, 1),
	(27, 'bbb', '2023-05-11 11:55:49', 0, 12, 1);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
