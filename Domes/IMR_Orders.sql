-- phpMyAdmin SQL Dump
-- version 4.0.10.11
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 21, 2020 at 09:12 PM
-- Server version: 5.1.73
-- PHP Version: 5.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cs4130_sp2020`
--

-- --------------------------------------------------------

--
-- Table structure for table `IMR_Orders`
--

CREATE TABLE IF NOT EXISTS `IMR_Orders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hat_id` int(10) unsigned DEFAULT NULL,
  `hat_size` varchar(255) NOT NULL DEFAULT 'M',
  `hat_count` tinyint(3) unsigned DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `street` varchar(255) DEFAULT NULL,
  `zipcode` mediumint(8) unsigned DEFAULT NULL,
  `time_ordered` datetime DEFAULT NULL,
  `time_manufactured` datetime DEFAULT NULL,
  `time_delivered` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=29 ;

--
-- Dumping data for table `IMR_Orders`
--

INSERT INTO `IMR_Orders` (`id`, `hat_id`, `hat_size`, `hat_count`, `customer_name`, `state`, `city`, `street`, `zipcode`, `time_ordered`, `time_manufactured`, `time_delivered`) VALUES
(12, 11, 'S', 2, 'Joey', 'OR', 'Portland', '516 Boombob Road', 97229, '2020-04-16 20:52:53', '2020-04-16 21:06:21', NULL),
(3, 2, 'S', 1, 'Ian', 'MO', 'Raymore', '1403 Pembrocke Dr', 64083, '2020-04-14 16:19:38', '2020-04-14 21:05:38', '2020-04-14 21:06:17'),
(4, 1, 'M', 3, 'Ian', 'MO', 'Raymore', '1403 Pembrocke Dr', 64083, '2020-04-14 16:19:38', '2020-04-14 10:58:48', '2020-04-14 10:58:55'),
(18, 15, 'M', 4, 'Bella', 'PK', 'Petelburg', '415 Chansey Lane', 15657, '2020-04-18 20:58:20', NULL, NULL),
(6, 23, 'XL', 12, 'Billy', 'BingBong', 'Chocolateland', 'Banana Lane', 49615, '2020-04-15 18:28:43', '2020-04-15 18:44:58', NULL),
(7, 24, 'M', 1, 'Billy', 'BingBong', 'Chocolateland', 'Banana Lane', 49615, '2020-04-15 18:28:43', '2020-04-15 18:40:43', NULL),
(13, 13, 'XL', 1, 'Joey', 'OR', 'Portland', '516 Boombob Road', 97229, '2020-04-15 20:52:53', '2020-04-15 21:05:35', NULL),
(14, 14, 'L', 6, 'Joey', 'OR', 'Portland', '516 Boombob Road', 97229, '2020-04-15 20:52:53', '2020-04-15 21:05:34', '2020-04-15 21:06:41'),
(15, 28, 'M', 4, 'Frank', 'NY', 'New York', '123 Rockey Road', 56567, '2020-04-17 20:54:33', NULL, NULL),
(16, 25, 'XS', 20, 'Frank', 'NY', 'New York', '123 Rockey Road', 56567, '2020-04-16 20:54:33', '2020-04-16 21:05:30', NULL),
(17, 26, 'XS', 10, 'Frank', 'NY', 'New York', '123 Rockey Road', 56567, '2020-04-16 20:54:33', '2020-04-16 21:05:32', '2020-04-16 21:06:10'),
(19, 12, 'M', 1, 'Bella', 'PK', 'Petelburg', '415 Chansey Lane', 15657, '2020-04-17 20:58:20', NULL, NULL),
(20, 2, 'M', 3, 'Bella', 'PK', 'Petelburg', '415 Chansey Lane', 15657, '2020-04-19 20:58:21', NULL, NULL),
(21, 16, 'L', 1, 'Bella', 'PK', 'Petelburg', '415 Chansey Lane', 15657, '2020-04-18 20:58:21', NULL, NULL),
(22, 11, 'L', 5, 'Woody', 'TX', 'Big Rock', '64 Dusty Drive', 11122, '2020-04-19 20:59:58', NULL, NULL),
(23, 18, 'M', 3, 'Gracie', 'MO', 'Warrensburg', '48 Pride Street', 64093, '2020-04-21 21:01:33', NULL, NULL),
(24, 27, 'S', 4, 'Gracie', 'MO', 'Warrensburg', '48 Pride Street', 64093, '2020-04-21 21:01:33', '2020-04-21 21:05:44', '2020-04-21 21:05:53'),
(25, 27, 'M', 6, 'Gracie', 'MO', 'Warrensburg', '48 Pride Street', 64093, '2020-04-21 21:01:33', '2020-04-21 21:05:41', '2020-04-21 21:05:56'),
(26, 27, 'L', 2, 'Gracie', 'MO', 'Warrensburg', '48 Pride Street', 64093, '2020-04-21 21:01:33', '2020-04-21 21:05:46', '2020-04-21 21:05:49'),
(27, 28, 'XL', 3, 'Gracie', 'MO', 'Warrensburg', '48 Pride Street', 64093, '2020-04-20 21:01:33', NULL, NULL),
(28, 28, 'S', 3, 'Gracie', 'MO', 'Warrensburg', '48 Pride Street', 64093, '2020-04-20 21:01:33', '2020-04-20 21:06:02', NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
