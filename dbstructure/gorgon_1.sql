-- phpMyAdmin SQL Dump
-- version 4.4.13.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 08, 2016 at 03:28 PM
-- Server version: 5.6.27-0ubuntu1
-- PHP Version: 5.6.11-1ubuntu3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gorgon_1`
--
CREATE DATABASE IF NOT EXISTS `gorgon_1` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `gorgon_1`;

-- --------------------------------------------------------

--
-- Table structure for table `g__race`
--

CREATE TABLE IF NOT EXISTS `g__race` (
  `id` int(10) unsigned NOT NULL,
  `raceid` varchar(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `datetime_0` datetime NOT NULL,
  `descr` varchar(255) NOT NULL,
  `type` enum('training','race') NOT NULL DEFAULT 'training',
  `locked` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `g__user`
--

CREATE TABLE IF NOT EXISTS `g__user` (
  `id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `t__best_splits`
--

CREATE TABLE IF NOT EXISTS `t__best_splits` (
  `id` int(11) NOT NULL,
  `codeid` int(11) NOT NULL,
  `entry_id` int(11) NOT NULL,
  `time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `t__category`
--

CREATE TABLE IF NOT EXISTS `t__category` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `course_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `t__course`
--

CREATE TABLE IF NOT EXISTS `t__course` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `length` float NOT NULL,
  `climb` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `t__course_cp`
--

CREATE TABLE IF NOT EXISTS `t__course_cp` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `sequence` int(11) NOT NULL,
  `cpcode` int(11) NOT NULL,
  `cptype` enum('regular','freeo','bonus','') CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'regular',
  `cpsect` int(11) NOT NULL,
  `cpchange` int(11) NOT NULL,
  `cpdata` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `t__entry`
--

CREATE TABLE IF NOT EXISTS `t__entry` (
  `id` int(11) NOT NULL,
  `si_number` int(11) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `nick` varchar(50) NOT NULL,
  `registration` varchar(20) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `start` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `t__read`
--

CREATE TABLE IF NOT EXISTS `t__read` (
  `id` int(11) NOT NULL,
  `stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `sinum` int(11) NOT NULL,
  `sitype` smallint(6) NOT NULL,
  `siname` varchar(100) NOT NULL,
  `clear` time NOT NULL,
  `check` time NOT NULL,
  `start` time NOT NULL,
  `finish` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `t__read_punch`
--

CREATE TABLE IF NOT EXISTS `t__read_punch` (
  `id` int(11) NOT NULL,
  `read_id` int(11) NOT NULL,
  `code` int(11) NOT NULL,
  `time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `t__results`
--

CREATE TABLE IF NOT EXISTS `t__results` (
  `id` int(11) NOT NULL,
  `entry_id` int(11) NOT NULL,
  `time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `t__splits`
--

CREATE TABLE IF NOT EXISTS `t__splits` (
  `id` int(11) NOT NULL,
  `entry_id` int(11) NOT NULL,
  `sequence` int(11) NOT NULL,
  `control_id` int(11) NOT NULL,
  `time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Indexes for dumped tables
--

--
-- Indexes for table `g__race`
--
ALTER TABLE `g__race`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `g__user`
--
ALTER TABLE `g__user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t__best_splits`
--
ALTER TABLE `t__best_splits`
  ADD KEY `id` (`id`);

--
-- Indexes for table `t__category`
--
ALTER TABLE `t__category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `t__course`
--
ALTER TABLE `t__course`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t__course_cp`
--
ALTER TABLE `t__course_cp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `t__entry`
--
ALTER TABLE `t__entry`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `t__read`
--
ALTER TABLE `t__read`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t__results`
--
ALTER TABLE `t__results`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t__splits`
--
ALTER TABLE `t__splits`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `g__race`
--
ALTER TABLE `g__race`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `g__user`
--
ALTER TABLE `g__user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `t__best_splits`
--
ALTER TABLE `t__best_splits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `t__category`
--
ALTER TABLE `t__category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `t__course`
--
ALTER TABLE `t__course`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `t__course_cp`
--
ALTER TABLE `t__course_cp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `t__entry`
--
ALTER TABLE `t__entry`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `t__read`
--
ALTER TABLE `t__read`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `t__results`
--
ALTER TABLE `t__results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `t__splits`
--
ALTER TABLE `t__splits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `t__category`
--
ALTER TABLE `t__category`
  ADD CONSTRAINT `t__cat_course` FOREIGN KEY (`course_id`) REFERENCES `t__course` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `t__course_cp`
--
ALTER TABLE `t__course_cp`
  ADD CONSTRAINT `t__cp_course` FOREIGN KEY (`course_id`) REFERENCES `t__course` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `t__entry`
--
ALTER TABLE `t__entry`
  ADD CONSTRAINT `t__entry_cat` FOREIGN KEY (`category_id`) REFERENCES `t__category` (`id`) ON DELETE SET NULL;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
