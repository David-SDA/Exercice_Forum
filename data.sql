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
  `nomCategorie` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id_categorie`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Listage des données de la table forum.categorie : ~3 rows (environ)
INSERT INTO `categorie` (`id_categorie`, `nomCategorie`) VALUES
	(1, 'Sport'),
	(2, 'Culture'),
	(3, 'Automobile');

-- Listage de la structure de table forum. membre
CREATE TABLE IF NOT EXISTS `membre` (
  `id_membre` int NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `motDePasse` varchar(255) NOT NULL,
  `dateInscription` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `role` varchar(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id_membre`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

-- Listage des données de la table forum.membre : ~3 rows (environ)
INSERT INTO `membre` (`id_membre`, `pseudo`, `email`, `motDePasse`, `dateInscription`, `role`) VALUES
	(1, 'pseudo1', 'test1@test.com', 'test1', '2023-04-30 10:40:29', 'ROLE_MEMBER'),
	(2, 'admin', 'admin@admin.com', '$2y$10$LUymatU5V321wthQhnwZE.aFhOa7yETbAN8ic7JGn2NQgozRz8CAC', '2023-05-02 16:53:37', 'ROLE_ADMIN'),
	(7, 'a', 'a@a.aa', '$2y$10$NPzlZ3BqlOd6fW3YSBNhte0HPFfPh4zmw7Prg7ze5wq0f.jWHLB9i', '2023-05-02 11:54:58', 'ROLE_MEMBER');

-- Listage de la structure de table forum. post
CREATE TABLE IF NOT EXISTS `post` (
  `id_post` int NOT NULL AUTO_INCREMENT,
  `dateCreation` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `contenu` text NOT NULL,
  `membre_id` int NOT NULL,
  `topic_id` int NOT NULL,
  PRIMARY KEY (`id_post`),
  KEY `membre_id` (`membre_id`),
  KEY `topic_id` (`topic_id`),
  CONSTRAINT `post_ibfk_1` FOREIGN KEY (`membre_id`) REFERENCES `membre` (`id_membre`),
  CONSTRAINT `post_ibfk_2` FOREIGN KEY (`topic_id`) REFERENCES `topic` (`id_topic`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

-- Listage des données de la table forum.post : ~9 rows (environ)
INSERT INTO `post` (`id_post`, `dateCreation`, `contenu`, `membre_id`, `topic_id`) VALUES
	(1, '2023-04-30 10:48:43', 'test\r\ntest\r\ntest', 7, 1),
	(2, '2023-04-30 10:49:18', 'test', 7, 2),
	(3, '2023-04-30 10:49:34', 'test', 7, 2),
	(4, '2023-04-30 10:49:40', 'test', 7, 2),
	(5, '2023-04-30 10:49:59', 'test', 7, 3),
	(6, '2023-04-30 10:50:56', 'test', 7, 4),
	(7, '2023-05-01 11:01:30', 'testtesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttest test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test', 7, 4),
	(8, '2023-05-01 11:55:23', 'test', 7, 4),
	(9, '2023-05-01 11:56:12', 'testo', 7, 4);

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Listage des données de la table forum.topic : ~4 rows (environ)
INSERT INTO `topic` (`id_topic`, `titre`, `dateCreation`, `verrouiller`, `membre_id`, `categorie_id`) VALUES
	(1, 'Test 1', '2023-04-30 10:48:43', 0, 7, 1),
	(2, 'Test 2', '2023-04-30 10:49:18', 1, 7, 1),
	(3, 'Test 3', '2023-04-30 10:49:59', 0, 7, 3),
	(4, 'Test 4', '2023-04-30 10:50:56', 0, 7, 3);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
