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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

-- Listage des données de la table forum.membre : ~3 rows (environ)
INSERT INTO `membre` (`id_membre`, `pseudo`, `email`, `motDePasse`, `dateInscription`, `role`) VALUES
	(2, 'Admin', 'admin@admin.com', '$2y$10$WTUNywXJ6fkPMMyALUxET.Fv5t3PejJgKAABzd5Ce6np0iT3D8Ao.', '2024-02-20 14:08:22', 'ROLE_ADMIN'),
	(13, 'pseudo', 'email@email.com', '$2y$10$nVyJ/8LQaZNoD6PNQi0Ise9xKXoqn.hKastYCnn3qd27VAk5XTvza', '2024-02-19 18:23:35', 'ROLE_MEMBER'),
	(14, 'pseudo2', 'email2@email2.com', '$2y$10$MswtnYsun.GIOGyCGviMy.aGd1ShTH2WGX8hvBVjZRTh4ZhKwJcni', '2024-02-20 17:16:38', 'ROLE_MEMBER');

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
) ENGINE=InnoDB AUTO_INCREMENT=120 DEFAULT CHARSET=latin1;

-- Listage des données de la table forum.post : ~9 rows (environ)
INSERT INTO `post` (`id_post`, `dateCreation`, `dateDerniereModification`, `contenu`, `membre_id`, `topic_id`) VALUES
	(111, '2024-02-20 17:14:55', '2024-02-20 17:14:55', 'Premier post', 13, 31),
	(112, '2024-02-20 17:15:30', '2024-02-20 17:15:30', 'Deuxi&egrave;me post', 13, 31),
	(113, '2024-02-20 17:15:48', '2024-02-20 17:15:48', 'Premier post', 13, 32),
	(114, '2024-02-20 17:17:45', '2024-02-20 17:17:45', 'Premier post', 14, 33),
	(115, '2024-02-20 17:18:11', '2024-02-20 17:18:11', 'Premier post', 14, 34),
	(116, '2024-02-20 17:18:35', '2024-02-20 17:18:35', 'Deuxi&egrave;me post', 14, 32),
	(117, '2024-02-20 17:18:51', '2024-02-20 17:18:51', 'Troisi&egrave;me post', 14, 32),
	(118, '2024-02-20 17:20:51', '2024-02-20 17:20:51', 'Deuxi&egrave;me post', 13, 33),
	(119, '2024-02-20 17:21:08', '2024-02-20 17:21:08', 'Quatri&egrave;me post', 13, 32);

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
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=latin1;

-- Listage des données de la table forum.topic : ~4 rows (environ)
INSERT INTO `topic` (`id_topic`, `titre`, `dateCreation`, `verrouiller`, `membre_id`, `categorie_id`) VALUES
	(31, 'Topic 1', '2024-02-20 17:14:55', 0, 13, 3),
	(32, 'Topic 2', '2024-02-20 17:15:48', 0, 13, 1),
	(33, 'Topic 3', '2024-02-20 17:17:45', 0, 14, 1),
	(34, 'Topic 4', '2024-02-20 17:18:11', 0, 14, 3);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
