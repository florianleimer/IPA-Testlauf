-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 27. Feb 2021 um 19:55
-- Server-Version: 10.4.11-MariaDB
-- PHP-Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `probeipa`
--

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`uid`, `name`, `initials`, `password`, `active`, `status`) VALUES
(1, 'Florian Leimer', 'FL', '$2y$10$iHR3EHgIiqR.ZSxxUDe6x.jk2U8exNx6l67Aw/rsVBEbSh2qT9I9y', 1, 'admin'),
(2, 'David Dubach', 'DD', '$2y$10$2XjQJc4mRBhB7Go9Yqfx3.b73oAMIAmeJczLLoqykOoJ.eK6uGbuG', 1, 'user');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
