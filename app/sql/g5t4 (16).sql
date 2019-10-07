-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 07, 2019 at 12:51 PM
-- Server version: 5.7.23
-- PHP Version: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `g5t4`
--
CREATE DATABASE IF NOT EXISTS `g5t4` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `g5t4`;

-- --------------------------------------------------------

--
-- Table structure for table `bid`
--

DROP TABLE IF EXISTS `bid`;
CREATE TABLE IF NOT EXISTS `bid` (
  `userid` varchar(128) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `code` varchar(10) NOT NULL,
  `section` varchar(3) NOT NULL,
  `status` varchar(20) NOT NULL,
  PRIMARY KEY (`userid`,`code`),
  KEY `code` (`code`,`section`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

DROP TABLE IF EXISTS `course`;
CREATE TABLE IF NOT EXISTS `course` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `course` varchar(10) NOT NULL,
  `school` varchar(4) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `exam_date` date NOT NULL,
  `exam_start` time NOT NULL,
  `exam_end` time NOT NULL,
  PRIMARY KEY (`course`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `course_completed`
--

DROP TABLE IF EXISTS `course_completed`;
CREATE TABLE IF NOT EXISTS `course_completed` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` varchar(128) NOT NULL,
  `code` varchar(10) NOT NULL,
  PRIMARY KEY (`userid`,`code`),
  KEY `id` (`id`),
  KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `fail_bid`
--

DROP TABLE IF EXISTS `fail_bid`;
CREATE TABLE IF NOT EXISTS `fail_bid` (
  `userid` varchar(128) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `code` varchar(10) NOT NULL,
  `section` varchar(10) NOT NULL,
  PRIMARY KEY (`userid`,`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `min_bid`
--

DROP TABLE IF EXISTS `min_bid`;
CREATE TABLE IF NOT EXISTS `min_bid` (
  `code` varchar(10) NOT NULL,
  `section` varchar(3) NOT NULL,
  `amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `prerequisite`
--

DROP TABLE IF EXISTS `prerequisite`;
CREATE TABLE IF NOT EXISTS `prerequisite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `course` varchar(10) NOT NULL,
  `prerequisite` varchar(10) NOT NULL,
  PRIMARY KEY (`course`,`prerequisite`),
  KEY `id` (`id`),
  KEY `prerequisite` (`prerequisite`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `round`
--

DROP TABLE IF EXISTS `round`;
CREATE TABLE IF NOT EXISTS `round` (
  `round` int(11) NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `section`
--

DROP TABLE IF EXISTS `section`;
CREATE TABLE IF NOT EXISTS `section` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `course` varchar(10) NOT NULL,
  `section` varchar(3) NOT NULL,
  `day` int(11) NOT NULL,
  `start` time NOT NULL,
  `end` time NOT NULL,
  `instructor` varchar(100) NOT NULL,
  `venue` varchar(100) NOT NULL,
  `size` int(11) NOT NULL,
  PRIMARY KEY (`course`,`section`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `section-student`
--

DROP TABLE IF EXISTS `section-student`;
CREATE TABLE IF NOT EXISTS `section-student` (
  `userid` varchar(128) NOT NULL,
  `code` varchar(10) NOT NULL,
  `section` varchar(3) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  PRIMARY KEY (`userid`,`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

DROP TABLE IF EXISTS `student`;
CREATE TABLE IF NOT EXISTS `student` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  `name` varchar(100) NOT NULL,
  `school` varchar(4) NOT NULL,
  `edollar` decimal(10,2) NOT NULL,
  PRIMARY KEY (`userid`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bid`
--
ALTER TABLE `bid`
  ADD CONSTRAINT `bid_ibfk_2` FOREIGN KEY (`userid`) REFERENCES `student` (`userid`),
  ADD CONSTRAINT `bid_ibfk_3` FOREIGN KEY (`code`,`section`) REFERENCES `section` (`course`, `section`);

--
-- Constraints for table `course_completed`
--
ALTER TABLE `course_completed`
  ADD CONSTRAINT `course_completed_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `student` (`userid`),
  ADD CONSTRAINT `course_completed_ibfk_2` FOREIGN KEY (`code`) REFERENCES `course` (`course`);

--
-- Constraints for table `prerequisite`
--
ALTER TABLE `prerequisite`
  ADD CONSTRAINT `prerequisite_ibfk_1` FOREIGN KEY (`course`) REFERENCES `course` (`course`),
  ADD CONSTRAINT `prerequisite_ibfk_2` FOREIGN KEY (`prerequisite`) REFERENCES `course` (`course`);

--
-- Constraints for table `section`
--
ALTER TABLE `section`
  ADD CONSTRAINT `section_ibfk_1` FOREIGN KEY (`course`) REFERENCES `course` (`course`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
