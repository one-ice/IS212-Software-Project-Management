-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 04, 2019 at 02:56 PM
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

--
-- Dumping data for table `bid`
--

INSERT INTO `bid` (`userid`, `amount`, `code`, `section`, `status`) VALUES
('ben.ng.2009', '11.00', 'IS100', 'S1', 'pending'),
('calvin.ng.2009', '12.00', 'IS100', 'S1', 'pending'),
('dawn.ng.2009', '13.00', 'IS100', 'S1', 'pending'),
('eddy.ng.2009', '14.00', 'IS100', 'S1', 'pending'),
('fred.ng.2009', '15.00', 'IS100', 'S1', 'pending'),
('harry.ng.2009', '17.00', 'IS100', 'S1', 'pending'),
('ian.ng.2009', '18.00', 'IS100', 'S1', 'pending'),
('larry.ng.2009', '19.00', 'IS100', 'S1', 'pending'),
('maggie.ng.2009', '20.00', 'IS100', 'S1', 'pending'),
('neilson.ng.2009', '21.00', 'IS100', 'S1', 'pending'),
('olivia.ng.2009', '22.00', 'IS100', 'S1', 'pending');

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
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`id`, `course`, `school`, `title`, `description`, `exam_date`, `exam_start`, `exam_end`) VALUES
(23, 'ECON001', 'SOE', 'Microeconomics', 'Microeconomics is about economics in smaller scale (e.g. firm-scale)', '2013-11-01', '15:30:00', '18:45:00'),
(24, 'ECON002', 'SOE', 'Macroeconomics', 'You don\'t learn about excel macros here.', '2013-11-01', '08:30:00', '11:45:00'),
(1, 'IS100', 'SIS', 'Calculus', 'The basic objective of Calculus is to relate small-scale (differential) quantities to large-scale (integrated) quantities. This is accomplished by means of the Fundamental Theorem of Calculus. Students should demonstrate an understanding of the integral as a cumulative sum, of the derivative as a rate of change, and of the inverse relationship between integration and differentiation.', '2013-11-19', '08:30:00', '11:45:00'),
(2, 'IS101', 'SIS', 'Advanced Calculus', 'This is a second course on calculus. It is more advanced definitely.', '2013-11-18', '12:00:00', '15:15:00'),
(3, 'IS102', 'SIS', 'Java programming', 'This course teaches you on Java programming. I love Java definitely.', '2013-11-17', '15:30:00', '18:45:00'),
(4, 'IS103', 'SIS', 'Web Programming', 'JSP, Servlets using Tomcat', '2013-11-16', '08:30:00', '11:45:00'),
(5, 'IS104', 'SIS', 'Advanced Programming', 'How to write code that nobody can understand', '2013-11-15', '12:00:00', '15:15:00'),
(6, 'IS105', 'SIS', 'Data Structures', 'Data structure is a particular way of storing and organizing data in a computer so that it can be used efficiently. Arrays, Lists, Stacks and Trees will be covered.', '2013-11-14', '15:30:00', '18:45:00'),
(7, 'IS106', 'SIS', 'Database Modeling & Design', 'Data modeling in software engineering is the process of creating a data model by applying formal data model descriptions using data modeling techniques.', '2013-11-13', '08:30:00', '11:45:00'),
(8, 'IS107', 'SIS', 'IT Outsourcing', 'This course teaches you on how to outsource your programming projects to others.', '2013-11-12', '12:00:00', '15:15:00'),
(9, 'IS108', 'SIS', 'Organization Behaviour', 'Organizational Behavior (OB) is the study and application of knowledge about how people, individuals, and groups act in organizations.', '2013-11-11', '15:30:00', '18:45:00'),
(10, 'IS109', 'SIS', 'Cloud Computing', 'Cloud computing is Internet-based computing, whereby shared resources, software and information are provided to computers and other devices on-demand, like the electricity grid.', '2013-11-10', '08:30:00', '11:45:00'),
(11, 'IS200', 'SIS', 'Final Touch', 'Learn how eat, dress and talk.', '2013-11-09', '12:00:00', '15:15:00'),
(12, 'IS201', 'SIS', 'Fun with Shell Programming', 'Shell scripts are a fundamental part of the UNIX and Linux programming environment.', '2013-11-08', '15:30:00', '18:45:00'),
(13, 'IS202', 'SIS', 'Enterprise integration', 'Enterprise integration is a technical field of Enterprise Architecture, which focused on the study of things like system interconnection, electronic data interchange, product data exchange and distributed computing environments, and it\'s possible other solutions.[1', '2013-11-07', '08:30:00', '11:45:00'),
(14, 'IS203', 'SIS', 'Software Engineering', 'The Sleepless Era.', '2013-11-06', '12:00:00', '15:15:00'),
(15, 'IS204', 'SIS', 'Database System Administration', 'Database administration is a complex, often thankless chore.', '2013-11-05', '15:30:00', '18:45:00'),
(16, 'IS205', 'SIS', 'All Talk, No Action', 'The easiest course of all. We will sit around and talk.', '2013-11-04', '08:30:00', '11:45:00'),
(17, 'IS206', 'SIS', 'Operation Research', 'Operations research, also known as operational research, is an interdisciplinary branch of applied mathematics and formal science that uses advanced analytical methods such as mathematical modeling, statistical analysis, and mathematical optimization to arrive at optimal or near-optimal solutions to complex decision-making problems.', '2013-11-03', '12:00:00', '15:15:00'),
(18, 'IS207', 'SIS', 'GUI Bloopers', 'Common User Interface Design Don\'ts and Dos', '2013-11-03', '15:30:00', '18:45:00'),
(19, 'IS208', 'SIS', 'Artifical Intelligence', 'The science and engineering of making intelligent machine', '2013-11-03', '08:30:00', '11:45:00'),
(20, 'IS209', 'SIS', 'Information Storage and Management', 'Information storage and management (ISM) - once a relatively straightforward operation -has developed into a highly sophisticated pillar of information technology, requiring proven technical expertise.', '2013-11-02', '12:00:00', '15:15:00'),
(21, 'MGMT001', 'SOB', 'Business,Government, and Society', 'learn the interrelation amongst the three', '2013-11-02', '08:30:00', '11:45:00'),
(22, 'MGMT002', 'SOB', 'Technology and World Change', 'As technology changes, so does the world', '2013-11-01', '12:00:00', '15:15:00');

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `course_completed`
--

INSERT INTO `course_completed` (`id`, `userid`, `code`) VALUES
(1, 'amy.ng.2009', 'IS100'),
(2, 'ben.ng.2009', 'IS102'),
(3, 'ben.ng.2009', 'IS103'),
(4, 'gary.ng.2009', 'IS100');

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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `prerequisite`
--

INSERT INTO `prerequisite` (`id`, `course`, `prerequisite`) VALUES
(1, 'IS101', 'IS100'),
(2, 'IS103', 'IS102'),
(3, 'IS104', 'IS103'),
(4, 'IS109', 'IS102'),
(5, 'IS203', 'IS103'),
(6, 'IS203', 'IS106'),
(7, 'IS204', 'IS106'),
(8, 'IS209', 'IS106');

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
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `section`
--

INSERT INTO `section` (`id`, `course`, `section`, `day`, `start`, `end`, `instructor`, `venue`, `size`) VALUES
(34, 'ECON001', 'S1', 4, '08:30:00', '11:45:00', 'John KHOO', 'Seminar Rm 2-34', 10),
(35, 'ECON002', 'S1', 5, '15:30:00', '18:45:00', 'Andy KHOO', 'Seminar Rm 2-35', 10),
(1, 'IS100', 'S1', 1, '08:30:00', '11:45:00', 'Albert KHOO', 'Seminar Rm 2-1', 10),
(2, 'IS100', 'S2', 2, '12:00:00', '15:15:00', 'Billy KHOO', 'Seminar Rm 2-2', 10),
(3, 'IS101', 'S1', 3, '15:30:00', '18:45:00', 'Cheri KHOO', 'Seminar Rm 2-3', 10),
(4, 'IS101', 'S2', 4, '08:30:00', '11:45:00', 'Daniel KHOO', 'Seminar Rm 2-4', 10),
(5, 'IS101', 'S3', 5, '12:00:00', '15:15:00', 'Ernest KHOO', 'Seminar Rm 2-5', 10),
(6, 'IS102', 'S1', 1, '15:30:00', '18:45:00', 'Felicia KHOO', 'Seminar Rm 2-6', 10),
(7, 'IS102', 'S2', 2, '08:30:00', '11:45:00', 'Gerald KHOO', 'Seminar Rm 2-7', 10),
(8, 'IS102', 'S3', 3, '12:00:00', '15:15:00', 'Henry KHOO', 'Seminar Rm 2-8', 10),
(9, 'IS103', 'S1', 4, '15:30:00', '18:45:00', 'Ivy KHOO', 'Seminar Rm 2-9', 10),
(10, 'IS103', 'S2', 5, '08:30:00', '11:45:00', 'Jason KHOO', 'Seminar Rm 2-10', 10),
(11, 'IS103', 'S3', 1, '12:00:00', '15:15:00', 'Kat KHOO', 'Seminar Rm 2-11', 10),
(12, 'IS104', 'S1', 2, '15:30:00', '18:45:00', 'Linn KHOO', 'Seminar Rm 2-12', 10),
(13, 'IS104', 'S2', 3, '08:30:00', '11:45:00', 'Michael KHOO', 'Seminar Rm 2-13', 10),
(14, 'IS105', 'S1', 4, '12:00:00', '15:15:00', 'Nathaniel KHOO', 'Seminar Rm 2-14', 10),
(15, 'IS105', 'S2', 5, '15:30:00', '18:45:00', 'Oreilly KHOO', 'Seminar Rm 2-15', 10),
(16, 'IS106', 'S1', 1, '08:30:00', '11:45:00', 'Peter KHOO', 'Seminar Rm 2-16', 10),
(17, 'IS106', 'S2', 2, '12:00:00', '15:15:00', 'Queen KHOO', 'Seminar Rm 2-17', 10),
(18, 'IS107', 'S1', 3, '15:30:00', '18:45:00', 'Ray KHOO', 'Seminar Rm 2-18', 10),
(19, 'IS107', 'S2', 4, '08:30:00', '11:45:00', 'Simon KHOO', 'Seminar Rm 2-19', 10),
(20, 'IS108', 'S1', 5, '12:00:00', '15:15:00', 'Tim KHOO', 'Seminar Rm 2-20', 10),
(21, 'IS109', 'S1', 2, '08:30:00', '11:45:00', 'Vincent KHOO', 'Seminar Rm 2-22', 10),
(22, 'IS109', 'S2', 3, '12:00:00', '15:15:00', 'Winnie KHOO', 'Seminar Rm 2-23', 10),
(23, 'IS200', 'S1', 4, '15:30:00', '18:45:00', 'Xtra KHOO', 'Seminar Rm 2-24', 10),
(24, 'IS201', 'S1', 5, '08:30:00', '11:45:00', 'Yale KHOO', 'Seminar Rm 2-25', 10),
(25, 'IS202', 'S1', 1, '12:00:00', '15:15:00', 'Zen KHOO', 'Seminar Rm 2-26', 10),
(26, 'IS203', 'S1', 2, '15:30:00', '18:45:00', 'Anderson KHOO', 'Seminar Rm 2-27', 10),
(27, 'IS204', 'S1', 3, '08:30:00', '11:45:00', 'Bing KHOO', 'Seminar Rm 2-28', 10),
(28, 'IS205', 'S1', 4, '12:00:00', '15:15:00', 'Carlo KHOO', 'Seminar Rm 2-29', 10),
(29, 'IS206', 'S1', 5, '15:30:00', '18:45:00', 'Dickson KHOO', 'Seminar Rm 2-30', 10),
(30, 'IS207', 'S1', 1, '08:30:00', '11:45:00', 'Edmund KHOO', 'Seminar Rm 2-31', 10),
(31, 'IS208', 'S1', 2, '12:00:00', '15:15:00', 'Febrice KHOO', 'Seminar Rm 2-32', 10),
(32, 'MGMT001', 'S1', 3, '08:30:00', '11:45:00', 'Gavin KHOO', 'Seminar Rm 2-33', 10),
(33, 'MGMT002', 'S1', 3, '15:30:00', '18:45:00', 'Bob KHOO', 'Seminar Rm 2-37', 10);

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

--
-- Dumping data for table `section-student`
--

INSERT INTO `section-student` (`userid`, `code`, `section`, `amount`) VALUES
('calvin.ng.2009', 'IS100', 'S1', '12.00'),
('dawn.ng.2009', 'IS100', 'S1', '13.00'),
('eddy.ng.2009', 'IS100', 'S1', '14.00'),
('fred.ng.2009', 'IS100', 'S1', '15.00'),
('harry.ng.2009', 'IS100', 'S1', '17.00'),
('ian.ng.2009', 'IS100', 'S1', '18.00'),
('larry.ng.2009', 'IS100', 'S1', '19.00'),
('maggie.ng.2009', 'IS100', 'S1', '20.00'),
('neilson.ng.2009', 'IS100', 'S1', '21.00'),
('olivia.ng.2009', 'IS100', 'S1', '22.00');

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
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`id`, `userid`, `password`, `name`, `school`, `edollar`) VALUES
(1, 'admin', '$2y$10$VPEXI6X.A6nxlJtL89OCjuwweOl1N2ys04y4u7wXnR6kvsmZYAX5.', 'admin', 'NONE', '0.00'),
(2, 'amy.ng.2009', '$2y$10$HXMlUgfGFseeUTTjsPPA6u.IbFqe/wYLlyPQFSrtFwLXMVgkwiMXq', 'Amy NG', 'SIS', '200.00'),
(3, 'ben.ng.2009', '$2y$10$XASNW7Ooxg54GUQJTEQUDu.Q3ICSSdjPNZWmqgzsXwL3H.mj6KjAG', 'Ben NG', 'SIS', '189.00'),
(4, 'calvin.ng.2009', '$2y$10$3/JsrwWpk8SxhZ4oREWg..l.2gTgP5up7h8AXYo/uWWFrxdJCYNA.', 'Calvin NG', 'SIS', '188.00'),
(5, 'dawn.ng.2009', '$2y$10$6yboTiWSh9kCCEAgWuZvmOg1MB4Oz3KB4fw6XTqvdZCRJ.okCW4My', 'Dawn NG', 'SIS', '187.00'),
(6, 'eddy.ng.2009', '$2y$10$rO.iNV5EObGX4naxoTzNEO7epLCm6piwzmVMZ.JtwINY2fKPJHL4u', 'Eddy NG', 'SIS', '186.00'),
(7, 'fred.ng.2009', '$2y$10$w97VMIWVeGTtpDxPDpAvrODKhx2hh.nSIbvOvhzpoDO3Cu1EqbcUm', 'Fred NG', 'SIS', '185.00'),
(8, 'gary.ng.2009', '$2y$10$npyqYryi21w.LwZUZ4CmyOZJt1L/7pIdfWKeNuxer7z8q/wfNtQnS', 'Gary NG', 'SIS', '200.00'),
(9, 'harry.ng.2009', '$2y$10$W5PvYDs4QGzinslZIZ4TDu/KmBb/XcxSeJISdEWWJvl97LKkXHPS.', 'Harry NG', 'SIS', '183.00'),
(10, 'ian.ng.2009', '$2y$10$AOsdL73OlkcFEIEGgijsPuEB56hmYu5r0vVr7P2wvCtCWUToTqk/W', 'Ian NG', 'SIS', '182.00'),
(11, 'jerry.ng.2009', '$2y$10$fvCWJrtGBttw25sjxQ1UB.192BHncOew.czs6uJXWNUM0MMSseJqe', 'Jerry NG', 'SIS', '200.00'),
(12, 'kelly.ng.2009', '$2y$10$zcx3.V1wARynV53yOOHm6.NiTy0bM2X27FcyyJSQqQLs0COLoQ/9C', 'Kelly NG', 'SIS', '200.00'),
(13, 'larry.ng.2009', '$2y$10$EjV.uK4OEgoDhfd738sFgeRdO09rshWUHO2iTX9/.7Pc.YdxhHZ/y', 'Larry NG', 'SIS', '181.00'),
(14, 'maggie.ng.2009', '$2y$10$F0Js/zQ2pGykBWvBuD7SJeQqiBQD/VxlDAygB2f89hgnMRFr27A7a', 'Maggie NG', 'SIS', '180.00'),
(15, 'neilson.ng.2009', '$2y$10$bOzy6Gow43Q6yJHWdHhI1.YZtgp5q/L81w285SJm.pItM1Mu3H46a', 'Neilson NG', 'SIS', '179.00'),
(16, 'olivia.ng.2009', '$2y$10$gLkTrfHzGg.TfeTWoZULB.t/7sh3jXAPHDhh77pkALAnSR3UE0agi', 'Olivia NG', 'SIS', '178.00'),
(17, 'parker.ng.2009', '$2y$10$xUJvufeaN82Wk9NGC1ZF0OGkIm8J4nqwkdBOviuFUwyssj2zOojhC', 'Parker NG', 'SOE', '200.00'),
(18, 'quiten.ng.2009', '$2y$10$GZzSJjgCya5Oxdz6p3f5fur7eEgr9Wh/oYFXLbJ87JAfQSTMJ/te6', 'Quiten NG', 'SOE', '200.00'),
(19, 'ricky.ng.2009', '$2y$10$b82BSjY8GMkqfkotiwl4uOd0C0pWw6XJanAST3idl9KTT4daKc.1C', 'Ricky NG', 'SOE', '200.00'),
(20, 'steven.ng.2009', '$2y$10$CwZ6lJGj0X49RknT0qYqK.eCERdiqzsYN69S2vWg.ITF0R9hf3PYe', 'Steven NG', 'SOE', '200.00'),
(21, 'timothy.ng.2009', '$2y$10$0nqtmyn9MKFZG84eibUvXuG2csxOSsRm8N940oEagTTK29KcOW.5e', 'Timothy NG', 'SOE', '200.00'),
(22, 'ursala.ng.2009', '$2y$10$ZYJ7T5iMG6wqfmydVJ0cF.77YFdly.MzOx.fV2DSefTQdRX2Uf5qq', 'Ursala NG', 'SOE', '200.00'),
(23, 'valarie.ng.2009', '$2y$10$YZwZ.hyn1hpNR0CrBpt7eu2K.mGVN.Ff4obBor4N/kQr6RnNQ51am', 'Valarie NG', 'SOB', '200.00'),
(24, 'winston.ng.2009', '$2y$10$s5nzuPYTDbGCbuA/PMamKe.h.mDW4KJXR3CDMEDNg3jHN9ZBijpwq', 'Winston NG', 'SOB', '200.00'),
(25, 'xavier.ng.2009', '$2y$10$asIH58MLdK9/Z2hKBOckj.c5O8HGJafJNXaP6D4Ow3qEjpbtKw8/u', 'Xavier NG', 'SOB', '200.00'),
(26, 'yasir.ng.2009', '$2y$10$q0UI0/AZmTBDCjw.GHMvQ.OIf5mhgt8LK9MkbnOzd0qKR5R3PI1Re', 'Yasir NG', 'SOB', '200.00'),
(27, 'zac.ng.2009', '$2y$10$mqxAPZ7gp1IHKlZUK7eYQOijwYSkiIB27uMRVTWV.0NZ3BVP2sDvS', 'Zac NG', 'SOB', '200.00');

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
