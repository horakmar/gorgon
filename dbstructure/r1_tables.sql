-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u1build0.15.04.1
-- http://www.phpmyadmin.net
--
-- Počítač: localhost
-- Vytvořeno: Pon 07. pro 2015, 16:38
-- Verze serveru: 5.6.27-0ubuntu0.15.04.1
-- Verze PHP: 5.6.4-4ubuntu6.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databáze: `gorgon_1`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `r1__best_splits`
--

CREATE TABLE IF NOT EXISTS `r1__best_splits` (
`id` int(11) NOT NULL,
  `codeid` int(11) NOT NULL,
  `entry_id` int(11) NOT NULL,
  `time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `r1__category`
--

CREATE TABLE IF NOT EXISTS `r1__category` (
`id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `course_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `r1__course`
--

CREATE TABLE IF NOT EXISTS `r1__course` (
`id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `length` float NOT NULL,
  `climb` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `r1__course_control`
--

CREATE TABLE IF NOT EXISTS `r1__course_control` (
`id` int(11) NOT NULL,
  `code` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `sequence` int(11) NOT NULL,
  `section` int(11) NOT NULL,
  `type` enum('regular','freeorder','bonus','') NOT NULL DEFAULT 'regular'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `r1__entry`
--

CREATE TABLE IF NOT EXISTS `r1__entry` (
`id` int(11) NOT NULL,
  `si_number` int(11) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `nick` varchar(50) NOT NULL,
  `registration` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `r1__results`
--

CREATE TABLE IF NOT EXISTS `r1__results` (
`id` int(11) NOT NULL,
  `entry_id` int(11) NOT NULL,
  `time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `r1__splits`
--

CREATE TABLE IF NOT EXISTS `r1__splits` (
`id` int(11) NOT NULL,
  `entry_id` int(11) NOT NULL,
  `sequence` int(11) NOT NULL,
  `control_id` int(11) NOT NULL,
  `time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Klíče pro exportované tabulky
--

--
-- Klíče pro tabulku `r1__best_splits`
--
ALTER TABLE `r1__best_splits`
 ADD KEY `id` (`id`);

--
-- Klíče pro tabulku `r1__category`
--
ALTER TABLE `r1__category`
 ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `r1__course`
--
ALTER TABLE `r1__course`
 ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `r1__course_control`
--
ALTER TABLE `r1__course_control`
 ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `r1__entry`
--
ALTER TABLE `r1__entry`
 ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `r1__results`
--
ALTER TABLE `r1__results`
 ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `r1__splits`
--
ALTER TABLE `r1__splits`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `r1__best_splits`
--
ALTER TABLE `r1__best_splits`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pro tabulku `r1__category`
--
ALTER TABLE `r1__category`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pro tabulku `r1__course`
--
ALTER TABLE `r1__course`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pro tabulku `r1__course_control`
--
ALTER TABLE `r1__course_control`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pro tabulku `r1__entry`
--
ALTER TABLE `r1__entry`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pro tabulku `r1__results`
--
ALTER TABLE `r1__results`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pro tabulku `r1__splits`
--
ALTER TABLE `r1__splits`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
