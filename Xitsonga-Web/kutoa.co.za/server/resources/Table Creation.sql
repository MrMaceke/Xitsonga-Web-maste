CREATE DATABASE IF NOT EXISTS waxbixqf_kutoa_app_qa;

USE waxbixqf_kutoa_app_qa;

--
-- Table structure for table `system_activations`
--
CREATE TABLE IF NOT EXISTS `user_digit_code` (
  `digit_code_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `phone_number` varchar(40) NOT NULL,
  `digit_code` varchar(40) NOT NULL,
  `record_status` char(1) NOT NULL DEFAULT '1',
  `date_created` TIMESTAMP NOT NULL,
  PRIMARY KEY (`digit_code_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Table structure for table `system_activations`
--
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `firebase_id` varchar(200) NOT NULL,
  `facebook_id` varchar(20) NOT NULL,
  `phone_number` varchar(40) NOT NULL,
  `firstname` varchar(40) NOT NULL,
  `lastname` varchar(40) NOT NULL,
  `email_address` varchar(40),
  `record_status` char(1) NOT NULL DEFAULT '1',
  `date_created` TIMESTAMP NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `ride_requests`
--
CREATE TABLE IF NOT EXISTS `ride_requests` (
  `request_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `pickup_name` varchar(100) NOT NULL,
  `destination_name` varchar(100) NOT NULL,
  `pickup_latitude` varchar(100) NOT NULL,
  `pickup_longitude` varchar(100) NOT NULL,
  `destination_latitude` varchar(100) NOT NULL,
  `destination_longitude` varchar(100) NOT NULL,
  `price` varchar(100) NOT NULL,
  `trip_date` DATETIME NOT NULL,
  `request_type` char(1) NOT NULL,
  `status` char(1) NOT NULL DEFAULT '0',
  `record_status` char(1) NOT NULL DEFAULT '1',
  `date_created` TIMESTAMP NOT NULL,
  PRIMARY KEY (`request_id`),
  FOREIGN KEY (`user_id`) REFERENCES users(`user_id`) 
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `trips`
--
CREATE TABLE IF NOT EXISTS `trips` (
  `trip_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `driver_id` bigint(20) NOT NULL,
  `passenger_id` bigint(20) NOT NULL,
  `request_id` bigint(20) NOT NULL,
  `message` varchar(1000) NOT NULL,
  `status` char(1) NOT NULL DEFAULT '0',
  `record_status` char(1) NOT NULL DEFAULT '1',
  `date_created` TIMESTAMP NOT NULL,
  PRIMARY KEY (`trip_id`),
  FOREIGN KEY (`request_id`) REFERENCES ride_requests(`request_id`),
  FOREIGN KEY (`driver_id`) REFERENCES users(`user_id`) ,
  FOREIGN KEY (`passenger_id`) REFERENCES users(`user_id`) 
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `trips`
--
CREATE TABLE IF NOT EXISTS `track` (
  `track_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `driver_id` bigint(20) NOT NULL,
  `passenger_id` bigint(20) NOT NULL,
  `request_id` bigint(20) NOT NULL,
  `driver_latitude` varchar(100) NOT NULL,
  `driver_longitude` varchar(100) NOT NULL,
  `time_estimate` varchar(1000) NOT NULL,
  `record_status` char(1) NOT NULL DEFAULT '1',
  `date_created` TIMESTAMP NOT NULL,
  PRIMARY KEY (`track_id`),
  FOREIGN KEY (`request_id`) REFERENCES ride_requests(`request_id`),
  FOREIGN KEY (`driver_id`) REFERENCES users(`user_id`) ,
  FOREIGN KEY (`passenger_id`) REFERENCES users(`user_id`) 
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Table structure for table `trip_rating`
--
CREATE TABLE IF NOT EXISTS `trip_rating` (
  `rating_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `rating` varchar(40) NOT NULL,
  `comment` varchar(1000) NOT NULL,
  `rated_by` bigint(20) NOT NULL,
  `request_id` bigint(20) NOT NULL,
  `record_status` char(1) NOT NULL DEFAULT '1',
  `date_created` TIMESTAMP NOT NULL,
  PRIMARY KEY (`rating_id`),
  FOREIGN KEY (`request_id`) REFERENCES ride_requests(`request_id`),
  FOREIGN KEY (`user_id`) REFERENCES users(`user_id`),
  FOREIGN KEY (`rated_by`) REFERENCES users(`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;