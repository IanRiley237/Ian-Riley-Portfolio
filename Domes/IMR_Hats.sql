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
-- Table structure for table `IMR_Hats`
--

CREATE TABLE IF NOT EXISTS `IMR_Hats` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(30000) DEFAULT NULL,
  `price` double unsigned DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=29 ;

--
-- Dumping data for table `IMR_Hats`
--

INSERT INTO `IMR_Hats` (`id`, `name`, `description`, `price`, `image`) VALUES
(1, 'Lincoln', 'Ever wanted to walk out of your front door feeling like the president of the good ''ol USA? Well this is definitely the hat to wear.', 26.99, 'lincoln.jpg'),
(2, 'Green Baseball Cap', 'The hat you see one of four dad''s at their kid''s ball game is wearing. Buy this hat if you want to be just like your neighbor, Joe.', 8.99, 'green_baseball.jpg'),
(3, 'Matilda', 'Legends say that this hat gives it''s wearer magical telekinetic powers. They are just legends though.', 11.5, 'matilda.jpg'),
(10, 'Skate', 'Apparently Tony Hawk used to wear this hat. That is what my associate insists to me at least.', 19.99, 'skate.jpg'),
(11, 'Gunslinger', '"It''s hiiggghh nooooooooooonnnn." ... *PEW* *PEW*', 28.95, 'gunslinger.jpg'),
(12, 'Trucker', 'You are not a real long haul trucker unless you have the right hat.', 14.95, 'trucker.jpg'),
(13, 'Mystic', 'I swear this hat is cur.. I mean.. It is blessed with magical properties that will let you see unicorns and rainbows! It also gives you sparkly magic! :D', 22.99, 'mystic.jpg'),
(14, 'Enforcer', 'Bad boys bad boys. What''cha gonna do? What''cha gonna do when they come for you?', 30, 'enforcer.jpg'),
(15, 'Neon Trucker', 'This hat is for long haul truckers that always have their brights on, because they clearly want to be seen.', 14.95, 'neon_trucker.jpg'),
(16, 'Detective', 'This hat makes your IQ go up by at least 5 points. Trust me.', 21.54, 'detective.jpg'),
(17, 'Class', 'M''lady.', 13.99, 'class.jpg'),
(18, 'Ritz', 'The party wont really start until you walk in.', 21.99, 'ritz.jpg'),
(19, 'Mando', 'This is the way.', 157.99, 'mando.jpg'),
(20, 'Cat Tail', 'I can see it now. The silhouette of the straw hat is visible against the harsh sun; worn by the root-tootinest baddie in town leaning casually against a fence post. From his lips you hear the word that sends shivers down your spine. "Pard''ner?"', 8.99, 'rice.jpg'),
(21, 'Fiesta', 'Arriba!!', 9.99, 'fiesta.jpg'),
(22, 'Missie', 'Ever wanted to feel like you are from a 1930''s cartoon?', 11.95, 'missie.jpg'),
(23, 'Bucket', 'It''s a bucket. What else is there to say?', 5.99, 'bucket.jpg'),
(24, 'Angler', 'This hat will make it certain that you catch "the one that got away".', 14.99, 'angler.jpg'),
(25, 'Birthday', 'This hat is perfect for birthday parties. Your kid will be the talk of the town when everyone sees them wearing this hat!', 2.99, 'birthday.jpg'),
(26, 'Party', 'The hat that is suitable for any party! Wedding? Birthday? Bar Mitzvah? You name it!', 3.49, 'party.jpg'),
(27, 'Grad', 'This hat has a surprising amount of pomp considering the circumstances.', 18.99, 'grad.jpg'),
(28, 'Birdie', 'Don this hat to put a little extra "UMPH" in your swing!', 16.49, 'birdie.jpg');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
