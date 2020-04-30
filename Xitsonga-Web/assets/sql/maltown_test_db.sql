-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 11, 2015 at 04:12 PM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `tsonga_test_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `activations`
--

CREATE TABLE IF NOT EXISTS `activations` (
  `user_id` bigint(20) NOT NULL,
  `activation_key` varchar(40) NOT NULL,
  `activation_status` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `entity`
--

CREATE TABLE IF NOT EXISTS `entity` (
  `user_id` bigint(20) NOT NULL,
  `entity_id` bigint(10) NOT NULL AUTO_INCREMENT,
  `item_type` tinyint(4) NOT NULL,
  `town_id` bigint(20) NOT NULL DEFAULT '150413163421913030',
  `entity_name` text NOT NULL,
  `date_created` datetime NOT NULL,
  `active` char(1) DEFAULT '1',
  PRIMARY KEY (`entity_id`),
  KEY `item_type` (`item_type`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=150416153321407931 ;

-- --------------------------------------------------------

--
-- Table structure for table `entity_details`
--

CREATE TABLE IF NOT EXISTS `entity_details` (
  `user_id` bigint(20) NOT NULL,
  `entity_details_id` bigint(10) NOT NULL AUTO_INCREMENT,
  `entity_id` bigint(10) NOT NULL,
  `item_type` tinyint(4) NOT NULL,
  `content` text NOT NULL,
  `date_created` datetime NOT NULL,
  `active` char(1) DEFAULT '1',
  PRIMARY KEY (`entity_details_id`),
  KEY `entity_id` (`entity_id`),
  KEY `item_type` (`item_type`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `item_type`
--

CREATE TABLE IF NOT EXISTS `item_type` (
  `user_id` bigint(20) NOT NULL,
  `item_type` tinyint(4) NOT NULL AUTO_INCREMENT,
  `description` varchar(60) NOT NULL,
  `type` char(1) DEFAULT '1',
  `date_created` datetime NOT NULL,
  `active` char(1) DEFAULT '1',
  PRIMARY KEY (`item_type`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=46 ;

-- --------------------------------------------------------

--
-- Table structure for table `passwords`
--

CREATE TABLE IF NOT EXISTS `passwords` (
  `password_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `user_password` varchar(100) NOT NULL,
  `user_salt` varchar(100) NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`password_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` bigint(20) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `password_salt` varchar(100) NOT NULL,
  `registration_date` datetime NOT NULL,
  `account_status` char(1) DEFAULT '0',
  `activation_status` char(1) DEFAULT '0',
  `facebook_reg` int(11) NOT NULL DEFAULT '0',
  `facebook_id` varchar(15) NOT NULL DEFAULT '0',
  `admin_user` char(1) DEFAULT '0',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activations`
--
ALTER TABLE `activations`
  ADD CONSTRAINT `activations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `entity`
--
ALTER TABLE `entity`
  ADD CONSTRAINT `entity_ibfk_1` FOREIGN KEY (`item_type`) REFERENCES `item_type` (`item_type`),
  ADD CONSTRAINT `entity_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `entity_details`
--
ALTER TABLE `entity_details`
  ADD CONSTRAINT `entity_details_ibfk_1` FOREIGN KEY (`entity_id`) REFERENCES `entity` (`entity_id`),
  ADD CONSTRAINT `entity_details_ibfk_2` FOREIGN KEY (`item_type`) REFERENCES `item_type` (`item_type`),
  ADD CONSTRAINT `entity_details_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `item_type`
--
ALTER TABLE `item_type`
  ADD CONSTRAINT `item_type_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `passwords`
--
ALTER TABLE `passwords`
  ADD CONSTRAINT `passwords_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
