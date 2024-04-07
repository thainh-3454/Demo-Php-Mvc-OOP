-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 07, 2024 at 04:20 PM
-- Server version: 8.2.0
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `demo_mvc`
--

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
CREATE TABLE IF NOT EXISTS `posts` (
  `post_name` varchar(125) NOT NULL,
  `post_description` varchar(255) DEFAULT NULL,
  `post_type` int NOT NULL DEFAULT '1' COMMENT '1:tech, 2:funny,3:share',
  `post_image` varchar(255) NOT NULL,
  `create_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `update_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `user_id` int NOT NULL,
  `id` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `fk_post_user` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_name`, `post_description`, `post_type`, `post_image`, `create_at`, `update_at`, `user_id`, `id`) VALUES
('asdasd', 'asdasdasdsad', 1, '', '2024-04-05 13:37:36', '2024-04-05 13:37:36', 47, 15),
('asdasd', 'adasdsadasd', 3, '', '2024-04-05 14:45:37', '2024-04-05 14:45:47', 50, 16);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `address` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `remember_token` varchar(200) NOT NULL,
  `failed_attempts` int NOT NULL DEFAULT '0' COMMENT 'Atempted to log into the system',
  `last_attempt` datetime NOT NULL,
  `locked_until` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `address`, `password`, `remember_token`, `failed_attempts`, `last_attempt`, `locked_until`) VALUES
(47, 'Nguyễn Hoàng Thái', 'nguyenhoangthai7871@gmail.com', '', '$2y$10$nsc.jKr1L2T.RR2xCkKXBuihbl4WehtMPDIaP0MiO8pwRV2GaXFG.', '5eaf7f00df785ab857b41d140d360d52', 0, '0000-00-00 00:00:00', 1712212242),
(50, 'Thái Nguyễn', 'nguyenhoangthai7872@gmail.com', '', '$2y$10$fPTN1HTr8aJ9rrlwPEFos.JV2k5csLMiqShlVSUR5cKAFxH/4y7H6', '', 0, '0000-00-00 00:00:00', 0),
(51, 'Nguyễn Thái', 'nguyenhoangthai7873@gmail.com', '', '$2y$10$9kv6.rnoKz0BgOzrtdMrouv8AzmOH2/9E7LqfXj3J1zvhxrQTvgMG', '', 0, '0000-00-00 00:00:00', 0);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `fk_post_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
