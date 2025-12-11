-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 10, 2025 at 04:02 AM
-- Server version: 8.4.7
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fitquest_db`
--
  CREATE DATABASE IF NOT EXISTS 'Fitquest_DB';
  USE 'Fitquest_DB' ;
-- --------------------------------------------------------

--
-- Table structure for table `progress_logs`
--

DROP TABLE IF EXISTS `progress_logs`;
CREATE TABLE IF NOT EXISTS `progress_logs` (
  `log_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `log_date` date NOT NULL,
  `mood` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `focus` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `weight_lb` decimal(5,2) NOT NULL,
  `strength_volume` int NOT NULL,
  `cardio_minutes` int NOT NULL,
  `rpe` tinyint NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`log_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `age` int DEFAULT NULL,
  `height_in` int DEFAULT NULL,
  `weight_lb` decimal(5,2) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `workout_plans`
--

DROP TABLE IF EXISTS `workout_plans`;
CREATE TABLE IF NOT EXISTS `workout_plans` (
  `workout_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `difficulty` enum('Beginner','Intermediate','Advanced') COLLATE utf8mb4_unicode_ci NOT NULL,
  `target_muscle` enum('Full Body','Upper Body','Lower Body','Core') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`workout_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
COMMIT;

-- Insert into the 'User' table

INSERT INTO `authentification_users` 
(`firstname`, `lastname`, `email`, `username`, `password`)
VALUES
('John', 'Doe', 'jdoe@gmail.com', 'JDoe', 'jdoeIsHappy');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
