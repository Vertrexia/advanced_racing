-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 21, 2013 at 12:56 AM
-- Server version: 5.5.27-log
-- PHP Version: 5.4.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `racing`
--

-- --------------------------------------------------------

--
-- Table structure for table `errors`
--

CREATE TABLE IF NOT EXISTS `errors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` text NOT NULL,
  `message` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `maps`
--

CREATE TABLE IF NOT EXISTS `maps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `played` int(11) NOT NULL DEFAULT '0' COMMENT 'number of times players played this map.',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `queuers`
--

CREATE TABLE IF NOT EXISTS `queuers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player` text NOT NULL,
  `amount` int(11) NOT NULL,
  `current` int(11) NOT NULL,
  `current_time` text NOT NULL COMMENT 'the number of seconds they have been in server for',
  `next_time` text NOT NULL COMMENT 'the number of seconds till their queue amount increases',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `records`
--

CREATE TABLE IF NOT EXISTS `records` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player` text NOT NULL,
  `map` text NOT NULL,
  `time` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `rotation`
--

CREATE TABLE IF NOT EXISTS `rotation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item` text NOT NULL,
  `allowed` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=65 ;

--
-- Dumping data for table `rotation`
--

INSERT INTO `rotation` (`id`, `item`, `allowed`) VALUES
(1, 'Lover-Boy/Advanced/Racing/Prayer-1.0.0.aamap.xml', 1),
(2, 'Lover-Boy/Advanced/Racing/XWindMill-1.0.0.aamap.xml', 1),
(3, 'Lover-Boy/Advanced/Racing/Fist_of_Death-1.0.0.aamap.xml', 1),
(4, 'Lover-Boy/Advanced/Racing/Mini_MBlaster-1.0.0.aamap.xml', 1),
(5, 'Lover-Boy/Advanced/Racing/Microphone-1.0.0.aamap.xml', 1),
(6, 'Lover-Boy/Advanced/Racing/Maletrus-1.0.0.aamap.xml', 1),
(7, 'Lover-Boy/Advanced/Racing/Highliter-1.0.1.aamap.xml', 1),
(8, 'Lover-Boy/Advanced/Racing/Strangler-1.0.0.aamap.xml', 1),
(9, 'Lover-Boy/Advanced/Racing/Hand_of_Blood-1.0.0.aamap.xml', 1),
(10, 'Lover-Boy/Advanced/Racing/Zel_Trex-1.0.0.aamap.xml', 1),
(11, 'Lover-Boy/Advanced/Racing/Hand_of_Glory-1.0.0.aamap.xml', 1),
(12, 'Lover-Boy/Advanced/Racing/Cuppas-1.0.0.aamap.xml', 1),
(13, 'Lover-Boy/Advanced/Racing/Fish_Farce-1.0.0.aamap.xml', 1),
(14, 'Lover-Boy/Advanced/Racing/Shazanger-1.0.0.aamap.xml', 1),
(15, 'Lover-Boy/Advanced/Racing/Deaths_Door-1.0.0.aamap.xml', 1),
(16, 'Lover-Boy/Advanced/Racing/Boxing_Gloves-1.0.0.aamap.xml', 1),
(17, 'Lover-Boy/Advanced/Racing/Rocket_Bike-1.0.0.aamap.xml', 1),
(18, 'Lover-Boy/Advanced/Racing/Two_Fingers-1.0.0.aamap.xml', 1),
(19, 'Lover-Boy/Advanced/Racing/Alternative_Worlds-1.0.0.aamap.xml', 1),
(20, 'Lover-Boy/Advanced/Racing/Vollas-1.0.0.aamap.xml', 1),
(21, 'Lover-Boy/Advanced/Racing/Capsule-1.0.0.aamap.xml', 1),
(22, 'Lover-Boy/Advanced/Racing/Babolonious-1.0.0.aamap.xml', 1),
(23, 'Lover-Boy/Advanced/Racing/Start_And_Fire-1.0.0.aamap.xml', 1),
(24, 'Lover-Boy/Advanced/Racing/Cut-1.0.0.aamap.xml', 1),
(25, 'Lover-Boy/Advanced/Racing/Zoo_Bomb-1.0.0.aamap.xml', 1),
(26, 'Lover-Boy/Advanced/Racing/Brozeneck-1.0.0.aamap.xml', 1),
(27, 'Lover-Boy/Advanced/Racing/Talier-1.0.0.aamap.xml', 1),
(28, 'Lover-Boy/Advanced/Racing/Long_Nose-1.0.0.aamap.xml', 1),
(29, 'Lover-Boy/Advanced/Racing/Ribbon-1.0.0.aamap.xml', 1),
(30, 'Lover-Boy/Advanced/Racing/Zelkrier-1.0.0.aamap.xml', 1),
(31, 'Lover-Boy/Advanced/Racing/Shocker-1.0.0.aamap.xml', 1),
(32, 'Lover-Boy/Advanced/Racing/Pencil-1.0.0.aamap.xml', 1),
(33, 'Lover-Boy/Advanced/Racing/Maxtram-1.0.0.aamap.xml', 1),
(34, 'Lover-Boy/Advanced/Racing/Broken_Cup-1.0.0.aamap.xml', 1),
(35, 'Lover-Boy/Advanced/Racing/Iron_Shackle-1.0.0.aamap.xml', 1),
(36, 'Lover-Boy/Advanced/Racing/Balamos-1.0.0.aamap.xml', 1),
(37, 'Lover-Boy/Advanced/Racing/Eternal-2.0.0.aamap.xml', 1),
(38, 'Lover-Boy/Advanced/Racing/Moon_Crystal-1.0.0.aamap.xml', 1),
(39, 'Lover-Boy/Advanced/Racing/XMaker-1.0.1.aamap.xml', 1),
(40, 'Lover-Boy/Advanced/Racing/Long_Break-1.0.0.aamap.xml', 1),
(41, 'Lover-Boy/Advanced/Racing/Razen_Chaos-1.0.0.aamap.xml', 1),
(42, 'Lover-Boy/Advanced/Racing/Small_Disastor-1.0.0.aamap.xml', 1),
(43, 'Lover-Boy/Advanced/Racing/Mini_XYBlaster-1.0.1.aamap.xml', 1),
(44, 'Lover-Boy/Advanced/Racing/Pintar-1.0.0.aamap.xml', 1),
(45, 'Lover-Boy/Advanced/Racing/Bottle_Up-1.0.0.aamap.xml', 1),
(46, 'Lover-Boy/Advanced/Racing/Crumblix-1.0.0.aamap.xml', 1),
(47, 'Lover-Boy/Advanced/Racing/Shove_Through-1.0.0.aamap.xml', 1),
(48, 'Lover-Boy/Advanced/Racing/Zeltrus-1.0.0.aamap.xml', 1),
(49, 'Lover-Boy/Advanced/Racing/Heart_Scale-1.0.0.aamap.xml', 1),
(50, 'Lover-Boy/Advanced/Racing/Torpedo-1.0.0.aamap.xml', 1),
(51, 'Lover-Boy/Advanced/Racing/Mini_YBlaster-1.0.2.aamap.xml', 1),
(52, 'Lover-Boy/Advanced/Racing/Tooth-1.0.0.aamap.xml', 1),
(53, 'Lover-Boy/Advanced/Racing/Optional_Prism-1.0.0.aamap.xml', 1),
(54, 'Lover-Boy/Advanced/Racing/Weird_Dancer-1.0.0.aamap.xml', 1),
(55, 'Lover-Boy/Advanced/Racing/Start_And_Ignite-1.0.0.aamap.xml', 1),
(56, 'Lover-Boy/Advanced/Racing/Valley_Of_Death-1.0.0.aamap.xml', 1),
(57, 'Lover-Boy/Advanced/Racing/XCutter-1.0.0.aamap.xml', 1),
(58, 'Lover-Boy/Advanced/Racing/Butterfly-1.0.1.aamap.xml', 1),
(59, 'Lover-Boy/Advanced/Racing/Huricania-2.0.0.aamap.xml', 1),
(60, 'Lover-Boy/Advanced/Racing/Pot_of_Death-1.0.0.aamap.xml', 1),
(61, 'Lover-Boy/Advanced/Racing/Couch-1.0.1.aamap.xml', 1),
(62, 'Lover-Boy/Advanced/Racing/Compressor-1.0.0.aamap.xml', 1),
(63, 'Lover-Boy/Advanced/Racing/Twin_Mecha-1.0.0.aamap.xml', 1),
(64, 'Lover-Boy/Advanced/Racing/Shizle-1.0.0.aamap.xml', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
