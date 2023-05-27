-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 27, 2023 at 06:22 AM
-- Server version: 5.7.24
-- PHP Version: 8.1.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blog_pro`
--
CREATE DATABASE IF NOT EXISTS `blog_pro` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `blog_pro`;

-- --------------------------------------------------------

--
-- Table structure for table `article`
--

CREATE TABLE IF NOT EXISTS `article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `chapo` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `is_published` tinyint(1) NOT NULL,
  `updated_at` date NOT NULL,
  `created_at` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `author_id` (`author_id`,`category_id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `article`
--

INSERT INTO `article` (`id`, `author_id`, `category_id`, `title`, `chapo`, `slug`, `content`, `image`, `is_published`, `updated_at`, `created_at`) VALUES
(10, 1, 1, 'aze', 'aze', '', 'aze aze', '64621c4eb36e4.jpg', 1, '2023-05-15', '2023-05-15'),
(11, 1, 2, 'test', 'test', '', 'zaerzae rr a', '646232ea86d6c.jpg', 1, '2023-05-15', '2023-05-15'),
(12, 1, 3, 'rtertert', 'erterterter', '', 'erterterter', '646254f6a6325.jpg', 1, '2023-05-15', '2023-05-15');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`, `slug`) VALUES
(1, 'Actualité', 'actualites'),
(2, 'Astuces', 'astuces'),
(3, 'Tutoriels', 'tutoriels'),
(4, 'Divers', 'divers');

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author_id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` date NOT NULL,
  `is_published` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `author_id` (`author_id`,`article_id`),
  KEY `article_id` (`article_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`id`, `author_id`, `article_id`, `username`, `content`, `created_at`, `is_published`) VALUES
(2, 1, 11, '', 'azeaze', '2023-05-16', 1),
(3, 2, 10, '', 'aeaeaeazdqsdqsdaz', '2023-05-16', 1),
(4, 3, 11, '', 'azeazeazea', '2023-05-16', 1),
(5, 3, 10, 'test', 'azaza', '2023-05-16', 1),
(6, 2, 12, 'admin', 'zerzer', '2023-05-23', 1),
(7, 2, 11, 'admin', 'adazeaz', '2023-05-23', 1),
(8, 2, 11, 'admin', 'qazdazd', '2023-05-23', 1),
(9, 1, 10, 'aze', 'aze', '2023-05-23', 0),
(10, 1, 10, 'aze', 'test', '2023-05-23', 0),
(11, 1, 12, 'aze', 'test', '2023-05-24', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `created_at` date NOT NULL,
  `updated_at` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `username`, `role`, `created_at`, `updated_at`) VALUES
(1, 'aze', '$2y$10$CTrjI76H7qNnJ8v2ON9UoOj0oO22qZ/iK1IUUUgXpWzVvYgWGK182', 'aze', 'user', '2023-05-15', '2023-05-15'),
(2, 'admin@fr.fr', '$2y$10$X1LPAkopxCn7NDYDQcJ9k.lNnyAYouIqLFf4siln6y/CYWp2X/lpy', 'admin', 'admin', '2023-05-16', '2023-05-16'),
(3, 'test', '$2y$10$xADW8qTeCz/L7mQi7hyVEuVHnC69RpKaFzlLHC1Q.43EyaEYhQB1G', 'test', 'user', '2023-05-16', '2023-05-16');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `article`
--
ALTER TABLE `article`
  ADD CONSTRAINT `article_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  ADD CONSTRAINT `article_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `article` (`id`),
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
