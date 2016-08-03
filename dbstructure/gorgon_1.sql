-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 03, 2016 at 12:18 PM
-- Server version: 5.7.13-0ubuntu0.16.04.2
-- PHP Version: 7.0.8-0ubuntu0.16.04.2

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
-- Table structure for table `g__entry`
--

CREATE TABLE `g__entry` (
  `id` int(11) NOT NULL,
  `si_number` int(11) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `nick` varchar(50) NOT NULL,
  `registration` varchar(20) NOT NULL,
  `birth` date DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `start` int(11) DEFAULT NULL,
  `start_opt` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 - none, 1 - early, 2 - late, 3 - red, 4 - orange'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `g__race`
--

CREATE TABLE `g__race` (
  `id` int(10) UNSIGNED NOT NULL,
  `raceid` varchar(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `datetime_0` datetime NOT NULL,
  `descr` varchar(255) NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1 - training, 2 - race',
  `locked` tinyint(1) NOT NULL DEFAULT '0',
  `opt_autocat` tinyint(1) NOT NULL,
  `opt_preftype` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1 - regular, 2 - freeo',
  `opt_incomplete` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1 - last, 2 - disk, 3 - penalty',
  `opt_namefrsi` tinyint(4) NOT NULL DEFAULT '0',
  `opt_addnew` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `g__user`
--

CREATE TABLE `g__user` (
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

CREATE TABLE `t__best_splits` (
  `id` int(11) NOT NULL,
  `codeid` int(11) NOT NULL,
  `entry_id` int(11) NOT NULL,
  `time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `t__category`
--

CREATE TABLE `t__category` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `course_id` int(11) DEFAULT NULL,
  `start_order` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1 - list_si, 2 - si_list, 3 - list, 4 - si'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `t__course`
--

CREATE TABLE `t__course` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `length` float NOT NULL,
  `climb` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `t__course_cp`
--

CREATE TABLE `t__course_cp` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `sequence` int(11) NOT NULL,
  `cpcode` int(11) NOT NULL,
  `cptype` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1 - regular, 2 - freeo, 3 - bonus',
  `cpsect` int(11) DEFAULT NULL,
  `cpchange` int(11) DEFAULT NULL,
  `cpdata` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `t__entry`
--

CREATE TABLE `t__entry` (
  `id` int(11) NOT NULL,
  `si_number` int(11) DEFAULT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `nick` varchar(50) NOT NULL,
  `registration` varchar(20) NOT NULL,
  `birth` date DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `start` int(11) DEFAULT NULL,
  `start_opt` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 - none, 1 - early, 2 - late, 3 - red, 4 - orange'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `t__read`
--

CREATE TABLE `t__read` (
  `id` int(11) NOT NULL,
  `stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `si_number` int(11) NOT NULL,
  `si_type` smallint(6) NOT NULL,
  `si_lname` varchar(100) DEFAULT NULL,
  `si_fname` varchar(100) DEFAULT NULL,
  `tm_clear` int(11) DEFAULT NULL,
  `tm_check` int(11) DEFAULT NULL,
  `tm_start` int(11) DEFAULT NULL,
  `tm_finish` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `t__read_punch`
--

CREATE TABLE `t__read_punch` (
  `id` int(11) NOT NULL,
  `read_id` int(11) NOT NULL,
  `code` int(11) NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `t__results`
--

CREATE TABLE `t__results` (
  `id` int(11) NOT NULL,
  `entry_id` int(11) NOT NULL,
  `tm_start` int(11) DEFAULT NULL,
  `tm_finish` int(11) DEFAULT NULL,
  `tm_bonus` int(11) DEFAULT NULL,
  `tm_result` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL COMMENT '0 ... Disk, 1 ... OK',
  `cp_count` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `t__results_punch`
--

CREATE TABLE `t__results_punch` (
  `id` int(11) NOT NULL,
  `results_id` int(11) NOT NULL,
  `code` int(11) NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `t__splits`
--

CREATE TABLE `t__splits` (
  `id` int(11) NOT NULL,
  `entry_id` int(11) NOT NULL,
  `sequence` int(11) NOT NULL,
  `control_id` int(11) NOT NULL,
  `time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `g__entry`
--
ALTER TABLE `g__entry`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

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
-- Indexes for table `t__read_punch`
--
ALTER TABLE `t__read_punch`
  ADD PRIMARY KEY (`id`),
  ADD KEY `read_id` (`read_id`) USING BTREE;

--
-- Indexes for table `t__results`
--
ALTER TABLE `t__results`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t__results_punch`
--
ALTER TABLE `t__results_punch`
  ADD PRIMARY KEY (`id`),
  ADD KEY `read_id` (`results_id`) USING BTREE;

--
-- Indexes for table `t__splits`
--
ALTER TABLE `t__splits`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `g__entry`
--
ALTER TABLE `g__entry`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;
--
-- AUTO_INCREMENT for table `g__race`
--
ALTER TABLE `g__race`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT for table `g__user`
--
ALTER TABLE `g__user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
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
-- AUTO_INCREMENT for table `t__read_punch`
--
ALTER TABLE `t__read_punch`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `t__results`
--
ALTER TABLE `t__results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `t__results_punch`
--
ALTER TABLE `t__results_punch`
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

--
-- Constraints for table `t__read_punch`
--
ALTER TABLE `t__read_punch`
  ADD CONSTRAINT `t__read_punch_ibfk_1` FOREIGN KEY (`read_id`) REFERENCES `t__read` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `t__results_punch`
--
ALTER TABLE `t__results_punch`
  ADD CONSTRAINT `t__results_punch_ibfk_1` FOREIGN KEY (`results_id`) REFERENCES `t__results` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
