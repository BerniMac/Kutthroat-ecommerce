-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 04, 2026 at 06:49 PM
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
-- Database: `kutthroat`
--

-- --------------------------------------------------------

--
-- Table structure for table `brand`
--

DROP TABLE IF EXISTS `brand`;
CREATE TABLE IF NOT EXISTS `brand` (
  `id` int NOT NULL AUTO_INCREMENT,
  `brand` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `brand`
--

INSERT INTO `brand` (`id`, `brand`) VALUES
(1, 'KTB Kutthroat');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
CREATE TABLE IF NOT EXISTS `cart` (
  `id` int NOT NULL AUTO_INCREMENT,
  `items` json NOT NULL COMMENT 'Array of {id, size, quantity} objects',
  `expire_date` datetime NOT NULL,
  `paid` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `expire_date` (`expire_date`),
  KEY `paid` (`paid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `items`, `expire_date`, `paid`) VALUES
(2, '[{\"id\": \"1\", \"size\": \"One Size\", \"quantity\": 1}]', '2026-07-30 13:39:45', 0);

-- --------------------------------------------------------

--
-- Table structure for table `catergories`
--

DROP TABLE IF EXISTS `catergories`;
CREATE TABLE IF NOT EXISTS `catergories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `catergory` varchar(100) NOT NULL,
  `parent` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `parent` (`parent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `list_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `sizes` text COMMENT 'Comma-separated size:qty pairs e.g. S:5,M:10,L:3',
  `image` text COMMENT 'Semicolon-separated image URLs e.g. img1.jpg;img2.jpg',
  `brand` int NOT NULL DEFAULT '0',
  `featured` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `brand` (`brand`),
  KEY `featured` (`featured`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `title`, `description`, `price`, `list_price`, `sizes`, `image`, `brand`, `featured`) VALUES
(1, 'Pistol', 'Enter product description here.', 4788.00, 4590.00, 'One Size:10', 'images/pistol.jpg', 1, 1),
(2, 'Rifle', 'Enter product description here.', 0.00, 0.00, 'One Size:5', 'images/rifle.jpg', 1, 1),
(3, 'Revolver', 'Perfect if it is your first gun, does not jam.', 2300.00, 3330.00, 'One Size:5,Two Size:12', 'images/revolver.jpg', 1, 1),
(4, 'Scope', 'Useful for hitting the target.', 1000.00, 1500.00, 'One Size:5,Two Size:12,Three Size 18', 'images/scope.jpg', 1, 1),
(5, 'Katana', 'Perfect for close combat.', 2300.00, 3330.00, 'One Size:5,Two Size:12,Three Size 19', 'images/katana.jpg', 1, 1),
(6, 'Extended Clip', 'Has extra amount of bullets.', 3800.00, 4120.00, 'One Size:5,Two Size:12,Three Size 22', 'images/extended.jpg', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `charge_id` varchar(255) NOT NULL COMMENT 'Stripe charge ID e.g. ch_xxxxx',
  `cart_id` int NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `street` varchar(255) NOT NULL,
  `street2` varchar(255) DEFAULT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `zip_code` varchar(20) NOT NULL,
  `country` varchar(100) NOT NULL,
  `sub_total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tax` decimal(10,2) NOT NULL DEFAULT '0.00',
  `grand_total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `description` varchar(255) DEFAULT NULL,
  `txn_type` varchar(50) DEFAULT NULL COMMENT 'Stripe charge.object value e.g. charge',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `charge_id` (`charge_id`),
  KEY `cart_id` (`cart_id`),
  KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_product_brand` FOREIGN KEY (`brand`) REFERENCES `brand` (`id`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `fk_txn_cart` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
