-- phpMyAdmin SQL Dump
-- version 4.4.15.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2017-03-11 00:18:00
-- 服务器版本： 5.5.48-log
-- PHP Version: 5.4.45

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `picidae`
--

-- --------------------------------------------------------

--
-- 表的结构 `form`
--

CREATE TABLE IF NOT EXISTS `form` (
  `idx` text NOT NULL,
  `url` text NOT NULL,
  `pic` text NOT NULL,
  `displaynr` int(11) NOT NULL,
  `created` text NOT NULL,
  `loading` int(11) NOT NULL,
  `ref` text NOT NULL,
  `ip` text NOT NULL,
  `cipher_key` text NOT NULL,
  `formfields` text NOT NULL,
  `hiddenfields` text NOT NULL,
  `url_idx` text NOT NULL,
  `url2pic` text NOT NULL,
  `imgnr` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `form`
--

INSERT INTO `form` (`idx`, `url`, `pic`, `displaynr`, `created`, `loading`, `ref`, `ip`, `cipher_key`, `formfields`, `hiddenfields`, `url_idx`, `url2pic`, `imgnr`) VALUES
('', '', '', 0, '20170311001317.000000', 0, '970f907510bd7bdfde8ea3b8b3672cc1', '104.199.195.187', '2240bf7b', '', '', '', '', '');

-- --------------------------------------------------------

--
-- 表的结构 `ref2url`
--

CREATE TABLE IF NOT EXISTS `ref2url` (
  `idx` text NOT NULL,
  `url` text NOT NULL,
  `pic` text NOT NULL,
  `displaynr` int(11) NOT NULL,
  `created` text NOT NULL,
  `loading` int(11) NOT NULL,
  `ref` text NOT NULL,
  `ip` text NOT NULL,
  `cipher_key` text NOT NULL,
  `formfields` text NOT NULL,
  `hiddenfields` text NOT NULL,
  `url_idx` text NOT NULL,
  `url2pic` text NOT NULL,
  `imgnr` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `url2pic`
--

CREATE TABLE IF NOT EXISTS `url2pic` (
  `idx` text NOT NULL,
  `url` text NOT NULL,
  `pic` text NOT NULL,
  `displaynr` int(11) NOT NULL,
  `created` text NOT NULL,
  `loading` int(11) NOT NULL,
  `ref` text NOT NULL,
  `ip` text NOT NULL,
  `cipher_key` text NOT NULL,
  `formfields` text NOT NULL,
  `hiddenfields` text NOT NULL,
  `url_idx` text NOT NULL,
  `url2pic` text NOT NULL,
  `imgnr` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `url2pic`
--

INSERT INTO `url2pic` (`idx`, `url`, `pic`, `displaynr`, `created`, `loading`, `ref`, `ip`, `cipher_key`, `formfields`, `hiddenfields`, `url_idx`, `url2pic`, `imgnr`) VALUES
('', 'http://www.google.com', '9159724561cf16aa15e6f4c13ba0887d', 1, '20170311001325.000000', 2, '', '', '', '', '', '', '', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
