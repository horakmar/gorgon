-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u1build0.15.04.1
-- http://www.phpmyadmin.net
--
-- Počítač: localhost
-- Vytvořeno: Úte 08. pro 2015, 15:04
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
-- Struktura tabulky `g__race`
--

CREATE TABLE IF NOT EXISTS `g__race` (
`id` int(10) unsigned NOT NULL,
  `raceid` varchar(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `time_0` time NOT NULL,
  `description` varchar(255) NOT NULL,
  `type` enum('training','race') NOT NULL DEFAULT 'training',
  `locked` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Vypisuji data pro tabulku `g__race`
--

INSERT INTO `g__race` (`id`, `raceid`, `name`, `date`, `time_0`, `description`, `type`, `locked`) VALUES
(1, 'test1', 'Testovací závod první', '2015-12-02', '00:00:00', '', 'training', 0),
(2, 'test2', 'Druhý testovací závod', '2015-12-03', '00:00:00', '', 'training', 0),
(3, 'test3', 'Třetí testovací závod', '2015-11-18', '00:00:00', '', 'race', 0);

-- --------------------------------------------------------

--
-- Struktura tabulky `g__user`
--

CREATE TABLE IF NOT EXISTS `g__user` (
`id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Vypisuji data pro tabulku `g__user`
--

INSERT INTO `g__user` (`id`, `username`, `password`, `role`, `name`) VALUES
(1, 'user', '$2y$10$mpqkxRGbJSN.j0YczuRfl.TNkXBDovyb3xCSoe75YQtEWZuQKf9oW', 'user', 'Uživatel'),
(2, 'horakmar', '$2y$10$MqjXQT.3fjllsOhWt6k3we8HODMjsazEUs2j.ONYFsO.KK.gQnegu', 'admin:user', 'Martin Horák'),
(3, 'test', '$2y$10$9u3kuu0NjylfsTJEc0/21uFRw7j0XOlEqefkJYIdWQEtVA18UxqYi', 'user', 'Testovací uživatel');

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
  `registration` varchar(20) NOT NULL,
  `category_id` int(11) NOT NULL,
  `start` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `r1__read`
--

CREATE TABLE IF NOT EXISTS `r1__read` (
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
-- Struktura tabulky `r1__read_punch`
--

CREATE TABLE IF NOT EXISTS `r1__read_punch` (
  `id` int(11) NOT NULL,
  `read_id` int(11) NOT NULL,
  `code` int(11) NOT NULL,
  `time` time NOT NULL
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
-- Klíče pro tabulku `g__race`
--
ALTER TABLE `g__race`
 ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `g__user`
--
ALTER TABLE `g__user`
 ADD PRIMARY KEY (`id`);

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
-- Klíče pro tabulku `r1__read`
--
ALTER TABLE `r1__read`
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
-- AUTO_INCREMENT pro tabulku `g__race`
--
ALTER TABLE `g__race`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pro tabulku `g__user`
--
ALTER TABLE `g__user`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
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
-- AUTO_INCREMENT pro tabulku `r1__read`
--
ALTER TABLE `r1__read`
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
