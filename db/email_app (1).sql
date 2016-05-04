-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 26, 2016 at 02:27 PM
-- Server version: 10.1.9-MariaDB
-- PHP Version: 5.6.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `email_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `email_content`
--

CREATE TABLE `email_content` (
  `id` int(11) NOT NULL,
  `name` text,
  `email` text,
  `content` text,
  `import_date` datetime DEFAULT NULL,
  `mail_sent` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `email_content`
--

INSERT INTO `email_content` (`id`, `name`, `email`, `content`, `import_date`, `mail_sent`) VALUES
(1, 'Jeff Ackerman', 'jeff@merck.com', '<table style="border: solid 1px #000; border-collapse: collapse" cellpadding="5" cellspacing="0"><tbody><tr style="background:#f89829; text-align:center; vertical-align:bottom;"><th style="border: solid 1px #000; border-collapse: collapse; text-align:center; vertical-align:bottom;">Production Org ID</th><th style="border: solid 1px #000; text-align:center; vertical-align:bottom;">Org Name</th><th style="border: solid 1px #000; text-align:center; vertical-align:bottom;">Prod Maintenance Start</th><th style="border: solid 1px #000; border-collapse: collapse text-align:center; vertical-align:bottom;">Prod Maintenance End</th><th style="border: solid 1px #000; text-align:center; vertical-align:bottom;">Full Sandbox Maintenance Start</th><th style="border: solid 1px #000; border-collapse: collapse text-align:center; vertical-align:bottom;">Full Sandbox Maintenance End</th></tr><tr><td style="border: solid 1px #000; border-collapse: collapse">DUIRl9</td><td style="border: solid 1px #000; border-collapse: collapse">US Merck</td><td style="border: solid 1px #000; border-collapse: collapse">Jul 25, 00:01 UTC (Jul 24, 20:01 US/Eastern)</td><td style="border: solid 1px #000; border-collapse: collapse">Jul 25, 05:00 UTC (Jul 25, 01:00 US/Eastern)</td><td style="border: solid 1px #000; border-collapse: collapse">Jul 03, 00:01 UTC (Jul 02, 20:01 US/Eastern)</td><td style="border: solid 1px #000; border-collapse: collapse">Jul 03, 05:00 UTC (Jul 03, 01:00 US/Eastern)</td></tr><tr><td style="border: solid 1px #000; border-collapse: collapse">DUJsh1</td><td style="border: solid 1px #000; border-collapse: collapse">Animal Health Merck</td><td style="border: solid 1px #000; border-collapse: collapse">Jul 25, 00:01 UTC (Jul 24, 20:01 US/Eastern)</td><td style="border: solid 1px #000; border-collapse: collapse">Jul 25, 05:00 UTC (Jul 25, 01:00 US/Eastern)</td><td style="border: solid 1px #000; border-collapse: collapse">Jul 03, 00:01 UTC (Jul 02, 20:01 US/Eastern)</td><td style="border: solid 1px #000; border-collapse: collapse">Jul 03, 05:00 UTC (Jul 03, 01:00 US/Eastern)</td></tr></tbody></table><br><br><br><br>', '2016-04-22 09:09:29', NULL),
(2, 'Blake Adams', 'Test@amylin.com', '<table style="border: solid 1px #000; border-collapse: collapse" cellpadding="5" cellspacing="0"><tbody><tr style="background:#f89829; text-align:center; vertical-align:bottom;"><th style="border: solid 1px #000; border-collapse: collapse; text-align:center; vertical-align:bottom;">Production Org ID</th><th style="border: solid 1px #000; text-align:center; vertical-align:bottom;">Org Name</th><th style="border: solid 1px #000; text-align:center; vertical-align:bottom;">Prod Maintenance Start</th><th style="border: solid 1px #000; border-collapse: collapse text-align:center; vertical-align:bottom;">Prod Maintenance End</th><th style="border: solid 1px #000; text-align:center; vertical-align:bottom;">Full Sandbox Maintenance Start</th><th style="border: solid 1px #000; border-collapse: collapse text-align:center; vertical-align:bottom;">Full Sandbox Maintenance End</th></tr><tr><td style="border: solid 1px #000; border-collapse: collapse">DAAN9F</td><td style="border: solid 1px #000; border-collapse: collapse">Pharmaceuticals</td><td style="border: solid 1px #000; border-collapse: collapse">Jul 25, 00:01 UTC (Jul 24, 20:01 US/Eastern)</td><td style="border: solid 1px #000; border-collapse: collapse">Jul 25, 05:00 UTC (Jul 25, 01:00 US/Eastern)</td><td style="border: solid 1px #000; border-collapse: collapse">Jul 03, 00:01 UTC (Jul 02, 20:01 US/Eastern)</td><td style="border: solid 1px #000; border-collapse: collapse">Jul 03, 05:00 UTC (Jul 03, 01:00 US/Eastern)</td></tr></tbody></table><br><br><br>', '2016-04-22 09:09:30', NULL),
(3, 'Pratyush Adhwaryu', 'Test@biocodexusa.com', '<table cellpadding=''5'' cellspacing=''0'' style=''border: solid 1px #000; border-collapse: collapse''><tr style=''background:#f89829; text-align:center; vertical-align:bottom;''><th style=''border: solid 1px #000; border-collapse: collapse; text-align:center; vertical-align:bottom;''>Production Org ID</th><th style=''border: solid 1px #000; text-align:center; vertical-align:bottom;''>Org Name</th><th style=''border: solid 1px #000; text-align:center; vertical-align:bottom;''>Prod Maintenance Start</th><th style=''border: solid 1px #000; border-collapse: collapse text-align:center; vertical-align:bottom;''>Prod Maintenance End</th><th style=''border: solid 1px #000; text-align:center; vertical-align:bottom;''>Full Sandbox Maintenance Start</th><th style=''border: solid 1px #000; border-collapse: collapse text-align:center; vertical-align:bottom;''>Full Sandbox Maintenance End</th></tr><tr><td style=''border: solid 1px #000; border-collapse: collapse''>DUKsUJ</td><td style=''border: solid 1px #000; border-collapse: collapse''>codex</td><td style=''border: solid 1px #000; border-collapse: collapse''>Jul 25, 00:01 UTC (Jul 24, 20:01 US/Eastern)</td><td style=''border: solid 1px #000; border-collapse: collapse''>Jul 25, 05:00 UTC (Jul 25, 01:00 US/Eastern)</td><td style=''border: solid 1px #000; border-collapse: collapse''>Jul 03, 00:01 UTC (Jul 02, 20:01 US/Eastern)</td><td style=''border: solid 1px #000; border-collapse: collapse''>Jul 03, 05:00 UTC (Jul 03, 01:00 US/Eastern)</td></tr></table>', '2016-04-22 09:09:30', NULL),
(4, 'Uphar Agarwal', 'Test@its.jnj.com', '<table cellpadding=''5'' cellspacing=''0'' style=''border: solid 1px #000; border-collapse: collapse''><tr style=''background:#f89829; text-align:center; vertical-align:bottom;''><th style=''border: solid 1px #000; border-collapse: collapse; text-align:center; vertical-align:bottom;''>Production Org ID</th><th style=''border: solid 1px #000; text-align:center; vertical-align:bottom;''>Org Name</th><th style=''border: solid 1px #000; text-align:center; vertical-align:bottom;''>Prod Maintenance Start</th><th style=''border: solid 1px #000; border-collapse: collapse text-align:center; vertical-align:bottom;''>Prod Maintenance End</th><th style=''border: solid 1px #000; text-align:center; vertical-align:bottom;''>Full Sandbox Maintenance Start</th><th style=''border: solid 1px #000; border-collapse: collapse text-align:center; vertical-align:bottom;''>Full Sandbox Maintenance End</th></tr><tr><td style=''border: solid 1px #000; border-collapse: collapse''>DEL9Tr</td><td style=''border: solid 1px #000; border-collapse: collapse''>Life</td><td style=''border: solid 1px #000; border-collapse: collapse''>Jul 25, 00:01 UTC (Jul 24, 20:01 US/Eastern)</td><td style=''border: solid 1px #000; border-collapse: collapse''>Jul 25, 05:00 UTC (Jul 25, 01:00 US/Eastern)</td><td style=''border: solid 1px #000; border-collapse: collapse''>Jul 03, 00:01 UTC (Jul 02, 20:01 US/Eastern)</td><td style=''border: solid 1px #000; border-collapse: collapse''>Jul 03, 05:00 UTC (Jul 03, 01:00 US/Eastern)</td></tr></table>', '2016-04-22 09:09:30', NULL),
(5, 'Pete Abbey', 'Test@kytherabiopharma.com', '<table cellpadding=''5'' cellspacing=''0'' style=''border: solid 1px #000; border-collapse: collapse''><tr style=''background:#f89829; text-align:center; vertical-align:bottom;''><th style=''border: solid 1px #000; border-collapse: collapse; text-align:center; vertical-align:bottom;''>Production Org ID</th><th style=''border: solid 1px #000; text-align:center; vertical-align:bottom;''>Org Name</th><th style=''border: solid 1px #000; text-align:center; vertical-align:bottom;''>Prod Maintenance Start</th><th style=''border: solid 1px #000; border-collapse: collapse text-align:center; vertical-align:bottom;''>Prod Maintenance End</th><th style=''border: solid 1px #000; text-align:center; vertical-align:bottom;''>Full Sandbox Maintenance Start</th><th style=''border: solid 1px #000; border-collapse: collapse text-align:center; vertical-align:bottom;''>Full Sandbox Maintenance End</th></tr><tr><td style=''border: solid 1px #000; border-collapse: collapse''>DULe5b</td><td style=''border: solid 1px #000; border-collapse: collapse''>Kythera</td><td style=''border: solid 1px #000; border-collapse: collapse''>Jul 25, 00:01 UTC (Jul 24, 20:01 US/Eastern)</td><td style=''border: solid 1px #000; border-collapse: collapse''>Jul 25, 05:00 UTC (Jul 25, 01:00 US/Eastern)</td><td style=''border: solid 1px #000; border-collapse: collapse''>Jul 03, 00:01 UTC (Jul 02, 20:01 US/Eastern)</td><td style=''border: solid 1px #000; border-collapse: collapse''>Jul 03, 05:00 UTC (Jul 03, 01:00 US/Eastern)</td></tr></table>', '2016-04-22 09:09:29', NULL),
(6, 'Toshimitsu Abe', 'Test@merck.com', '<table cellpadding=''5'' cellspacing=''0'' style=''border: solid 1px #000; border-collapse: collapse''><tr style=''background:#f89829; text-align:center; vertical-align:bottom;''><th style=''border: solid 1px #000; border-collapse: collapse; text-align:center; vertical-align:bottom;''>Production Org ID</th><th style=''border: solid 1px #000; text-align:center; vertical-align:bottom;''>Org Name</th><th style=''border: solid 1px #000; text-align:center; vertical-align:bottom;''>Prod Maintenance Start</th><th style=''border: solid 1px #000; border-collapse: collapse text-align:center; vertical-align:bottom;''>Prod Maintenance End</th><th style=''border: solid 1px #000; text-align:center; vertical-align:bottom;''>Full Sandbox Maintenance Start</th><th style=''border: solid 1px #000; border-collapse: collapse text-align:center; vertical-align:bottom;''>Full Sandbox Maintenance End</th></tr><tr><td style=''border: solid 1px #000; border-collapse: collapse''>D1JvL7</td><td style=''border: solid 1px #000; border-collapse: collapse''>Japan Merck</td><td style=''border: solid 1px #000; border-collapse: collapse''>Jul 24, 17:00 UTC (Jul 25, 02:00 Japan)</td><td style=''border: solid 1px #000; border-collapse: collapse''>Jul 24, 19:00 UTC (Jul 25, 04:00 Japan)</td><td style=''border: solid 1px #000; border-collapse: collapse''>Jul 03, 16:00 UTC (Jul 04, 01:00 Japan)</td><td style=''border: solid 1px #000; border-collapse: collapse''>Jul 03, 19:00 UTC (Jul 04, 04:00 Japan)</td></tr></table>', '2016-04-22 09:09:29', NULL),
(26, 'Sean Antoniewicz', 'jeyakannan@dotsolved.com', '<table cellpadding=''5'' cellspacing=''0'' style=''border: solid 1px #000; border-collapse: collapse''><tr style=''background:#f89829; text-align:center; vertical-align:bottom;''><th style=''border: solid 1px #000; border-collapse: collapse; text-align:center; vertical-align:bottom;''>Production Org ID</th><th style=''border: solid 1px #000; text-align:center; vertical-align:bottom;''>Org Name</th><th style=''border: solid 1px #000; text-align:center; vertical-align:bottom;''>Prod Maintenance Start</th><th style=''border: solid 1px #000; border-collapse: collapse text-align:center; vertical-align:bottom;''>Prod Maintenance End</th><th style=''border: solid 1px #000; text-align:center; vertical-align:bottom;''>Full Sandbox Maintenance Start</th><th style=''border: solid 1px #000; border-collapse: collapse text-align:center; vertical-align:bottom;''>Full Sandbox Maintenance End</th></tr><tr><td style=''border: solid 1px #000; border-collapse: collapse''>00DU0000000J38w</td><td style=''border: solid 1px #000; border-collapse: collapse''>Par Specialty</td><td style=''border: solid 1px #000; border-collapse: collapse''>Dec 5, 01:00 UTC (Dec 4, 20:00 US/Eastern)</td><td style=''border: solid 1px #000; border-collapse: collapse''>Dec 5, 06:00 UTC (Dec 5, 01:00 US/Eastern)</td><td style=''border: solid 1px #000; border-collapse: collapse''>Nov 14, 01:00 UTC (Nov 13, 20:00 US/Eastern)</td><td style=''border: solid 1px #000; border-collapse: collapse''>Nov 14, 06:00 UTC (Nov 14, 01:00 US/Eastern)</td></tr></table>', '2016-04-22 12:24:08', '2016-04-22 12:24:08'),
(27, 'Dave Hammond', 'kovalan@dotsolved.com', '<table cellpadding=''5'' cellspacing=''0'' style=''border: solid 1px #000; border-collapse: collapse''><tr style=''background:#f89829; text-align:center; vertical-align:bottom;''><th style=''border: solid 1px #000; border-collapse: collapse; text-align:center; vertical-align:bottom;''>Production Org ID</th><th style=''border: solid 1px #000; text-align:center; vertical-align:bottom;''>Org Name</th><th style=''border: solid 1px #000; text-align:center; vertical-align:bottom;''>Prod Maintenance Start</th><th style=''border: solid 1px #000; border-collapse: collapse text-align:center; vertical-align:bottom;''>Prod Maintenance End</th><th style=''border: solid 1px #000; text-align:center; vertical-align:bottom;''>Full Sandbox Maintenance Start</th><th style=''border: solid 1px #000; border-collapse: collapse text-align:center; vertical-align:bottom;''>Full Sandbox Maintenance End</th></tr><tr><td style=''border: solid 1px #000; border-collapse: collapse''>00DF000000052LV</td><td style=''border: solid 1px #000; border-collapse: collapse''>Arbor Pharma</td><td style=''border: solid 1px #000; border-collapse: collapse''>Dec 5, 01:00 UTC (Dec 4, 20:00 US/Eastern)</td><td style=''border: solid 1px #000; border-collapse: collapse''>Dec 5, 06:00 UTC (Dec 5, 01:00 US/Eastern)</td><td style=''border: solid 1px #000; border-collapse: collapse''>Nov 14, 01:00 UTC (Nov 13, 20:00 US/Eastern)</td><td style=''border: solid 1px #000; border-collapse: collapse''>Nov 14, 06:00 UTC (Nov 14, 01:00 US/Eastern)</td></tr></table>', '2016-04-22 12:24:13', '2016-04-22 12:24:13'),
(28, 'Michael Tier', 'jeyakannanrd@gmail.com', '<table cellpadding=''5'' cellspacing=''0'' style=''border: solid 1px #000; border-collapse: collapse''><tr style=''background:#f89829; text-align:center; vertical-align:bottom;''><th style=''border: solid 1px #000; border-collapse: collapse; text-align:center; vertical-align:bottom;''>Production Org ID</th><th style=''border: solid 1px #000; text-align:center; vertical-align:bottom;''>Org Name</th><th style=''border: solid 1px #000; text-align:center; vertical-align:bottom;''>Prod Maintenance Start</th><th style=''border: solid 1px #000; border-collapse: collapse text-align:center; vertical-align:bottom;''>Prod Maintenance End</th><th style=''border: solid 1px #000; text-align:center; vertical-align:bottom;''>Full Sandbox Maintenance Start</th><th style=''border: solid 1px #000; border-collapse: collapse text-align:center; vertical-align:bottom;''>Full Sandbox Maintenance End</th></tr><tr><td style=''border: solid 1px #000; border-collapse: collapse''>00Dd0000000iRJF</td><td style=''border: solid 1px #000; border-collapse: collapse''>Antares</td><td style=''border: solid 1px #000; border-collapse: collapse''>Dec 5, 01:00 UTC (Dec 4, 20:00 US/Eastern)</td><td style=''border: solid 1px #000; border-collapse: collapse''>Dec 5, 06:00 UTC (Dec 5, 01:00 US/Eastern)</td><td style=''border: solid 1px #000; border-collapse: collapse''>Nov 14, 01:00 UTC (Nov 13, 20:00 US/Eastern)</td><td style=''border: solid 1px #000; border-collapse: collapse''>Nov 14, 06:00 UTC (Nov 14, 01:00 US/Eastern)</td></tr></table>', '2016-04-22 12:24:18', '2016-04-22 12:24:18');

-- --------------------------------------------------------

--
-- Table structure for table `email_list`
--

CREATE TABLE `email_list` (
  `id` int(11) NOT NULL,
  `org_admin` varchar(100) DEFAULT NULL,
  `firstname` varchar(220) DEFAULT NULL,
  `lastname` varchar(220) DEFAULT NULL,
  `email` varchar(220) DEFAULT NULL,
  `org_id` varchar(100) DEFAULT NULL,
  `deployment_name` varchar(220) DEFAULT NULL,
  `deployment_org_region` varchar(220) DEFAULT NULL,
  `maintenance_start` varchar(220) DEFAULT NULL,
  `maintenance_end` varchar(220) DEFAULT NULL,
  `sandbox_region` varchar(100) DEFAULT NULL,
  `sandbox_maintenance_start` varchar(220) DEFAULT NULL,
  `sandbox_maintenance_end` varchar(220) DEFAULT NULL,
  `import_date` datetime DEFAULT NULL,
  `mail_sent` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `email_list`
--

INSERT INTO `email_list` (`id`, `org_admin`, `firstname`, `lastname`, `email`, `org_id`, `deployment_name`, `deployment_org_region`, `maintenance_start`, `maintenance_end`, `sandbox_region`, `sandbox_maintenance_start`, `sandbox_maintenance_end`, `import_date`, `mail_sent`) VALUES
(1, '2835', 'Pete', 'Abbey', 'Test@kytherabiopharma.com', 'DULe5b', 'Kythera', 'Americas', 'Jul 25, 00:01 UTC (Jul 24, 20:01 US/Eastern)', 'Jul 25, 05:00 UTC (Jul 25, 01:00 US/Eastern)', '', 'Jul 03, 00:01 UTC (Jul 02, 20:01 US/Eastern)', 'Jul 03, 05:00 UTC (Jul 03, 01:00 US/Eastern)', '2016-04-22 09:09:29', NULL),
(2, '2168', 'Toshimitsu', 'Abe', 'Test@merck.com', 'D1JvL7', 'Japan Merck', 'Asia', 'Jul 24, 17:00 UTC (Jul 25, 02:00 Japan)', 'Jul 24, 19:00 UTC (Jul 25, 04:00 Japan)', '', 'Jul 03, 16:00 UTC (Jul 04, 01:00 Japan)', 'Jul 03, 19:00 UTC (Jul 04, 04:00 Japan)', '2016-04-22 09:09:29', NULL),
(3, '1074', 'Jeff', 'Ackerman', 'jeff@merck.com', 'DUIRl9', 'US Merck', 'Americas', 'Jul 25, 00:01 UTC (Jul 24, 20:01 US/Eastern)', 'Jul 25, 05:00 UTC (Jul 25, 01:00 US/Eastern)', '', 'Jul 03, 00:01 UTC (Jul 02, 20:01 US/Eastern)', 'Jul 03, 05:00 UTC (Jul 03, 01:00 US/Eastern)', '2016-04-22 09:09:29', NULL),
(4, '1046', 'Jeff', 'Ackerman', 'jeff@merck.com', 'DUJsh1', 'Animal Health Merck', 'Americas', 'Jul 25, 00:01 UTC (Jul 24, 20:01 US/Eastern)', 'Jul 25, 05:00 UTC (Jul 25, 01:00 US/Eastern)', '', 'Jul 03, 00:01 UTC (Jul 02, 20:01 US/Eastern)', 'Jul 03, 05:00 UTC (Jul 03, 01:00 US/Eastern)', '2016-04-22 09:09:29', NULL),
(5, '173', 'Blake', 'Adams', 'Test@amylin.com', 'DAAN9F', 'Pharmaceuticals', 'Americas', 'Jul 25, 00:01 UTC (Jul 24, 20:01 US/Eastern)', 'Jul 25, 05:00 UTC (Jul 25, 01:00 US/Eastern)', '', 'Jul 03, 00:01 UTC (Jul 02, 20:01 US/Eastern)', 'Jul 03, 05:00 UTC (Jul 03, 01:00 US/Eastern)', '2016-04-22 09:09:30', NULL),
(6, '3068', 'Pratyush', 'Adhwaryu', 'Test@biocodexusa.com', 'DUKsUJ', 'codex', 'Americas', 'Jul 25, 00:01 UTC (Jul 24, 20:01 US/Eastern)', 'Jul 25, 05:00 UTC (Jul 25, 01:00 US/Eastern)', 'North America', 'Jul 03, 00:01 UTC (Jul 02, 20:01 US/Eastern)', 'Jul 03, 05:00 UTC (Jul 03, 01:00 US/Eastern)', '2016-04-22 09:09:30', NULL),
(7, '2570', 'Uphar', 'Agarwal', 'Test@its.jnj.com', 'DEL9Tr', 'Life', 'Americas', 'Jul 25, 00:01 UTC (Jul 24, 20:01 US/Eastern)', 'Jul 25, 05:00 UTC (Jul 25, 01:00 US/Eastern)', '', 'Jul 03, 00:01 UTC (Jul 02, 20:01 US/Eastern)', 'Jul 03, 05:00 UTC (Jul 03, 01:00 US/Eastern)', '2016-04-22 09:09:30', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `email_list_old`
--

CREATE TABLE `email_list_old` (
  `id` int(11) NOT NULL,
  `name` text,
  `email` text,
  `content` text,
  `import_date` datetime DEFAULT NULL,
  `mail_sent` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `email_list_old`
--

INSERT INTO `email_list_old` (`id`, `name`, `email`, `content`, `import_date`, `mail_sent`) VALUES
(1, 'Adrienne Myers', 'adrienne.myers@veeva.com', '<table cellpadding=''5'' cellspacing=''0'' style=''border: solid 1px #000; border-collapse: collapse''><tr style=''background:#f89829; text-align:center; vertical-align:bottom;''><th style=''border: solid 1px #000; border-collapse: collapse; text-align:center; vertical-align:bottom;''>Production Org ID</th><th style=''border: solid 1px #000; text-align:center; vertical-align:bottom;''>Org Name</th><th style=''border: solid 1px #000; text-align:center; vertical-align:bottom;''>Prod Maintenance Start</th><th style=''border: solid 1px #000; border-collapse: collapse text-align:center; vertical-align:bottom;''>Prod Maintenance End</th><th style=''border: solid 1px #000; text-align:center; vertical-align:bottom;''>Full Sandbox Maintenance Start</th><th style=''border: solid 1px #000; border-collapse: collapse text-align:center; vertical-align:bottom;''>Full Sandbox Maintenance End</th></tr><tr><td style=''border: solid 1px #000; border-collapse: collapse''>00DU0000000J38w</td><td style=''border: solid 1px #000; border-collapse: collapse''>Par Specialty</td><td style=''border: solid 1px #000; border-collapse: collapse''>Dec 5, 01:00 UTC (Dec 4, 20:00 US/Eastern)</td><td style=''border: solid 1px #000; border-collapse: collapse''>Dec 5, 06:00 UTC (Dec 5, 01:00 US/Eastern)</td><td style=''border: solid 1px #000; border-collapse: collapse''>Nov 14, 01:00 UTC (Nov 13, 20:00 US/Eastern)</td><td style=''border: solid 1px #000; border-collapse: collapse''>Nov 14, 06:00 UTC (Nov 14, 01:00 US/Eastern)</td></tr></table>', '2016-03-28 14:37:32', '2016-03-28 18:34:45'),
(2, 'Prasad Ramakrishnan', 'prasad.ramakrishnan@veeva.com', '<table cellpadding=''5'' cellspacing=''0'' style=''border: solid 1px #000; border-collapse: collapse''><tr style=''background:#f89829; text-align:center; vertical-align:bottom;''><th style=''border: solid 1px #000; border-collapse: collapse; text-align:center; vertical-align:bottom;''>Production Org ID</th><th style=''border: solid 1px #000; text-align:center; vertical-align:bottom;''>Org Name</th><th style=''border: solid 1px #000; text-align:center; vertical-align:bottom;''>Prod Maintenance Start</th><th style=''border: solid 1px #000; border-collapse: collapse text-align:center; vertical-align:bottom;''>Prod Maintenance End</th><th style=''border: solid 1px #000; text-align:center; vertical-align:bottom;''>Full Sandbox Maintenance Start</th><th style=''border: solid 1px #000; border-collapse: collapse text-align:center; vertical-align:bottom;''>Full Sandbox Maintenance End</th></tr><tr><td style=''border: solid 1px #000; border-collapse: collapse''>00DF000000052LV</td><td style=''border: solid 1px #000; border-collapse: collapse''>Arbor Pharma</td><td style=''border: solid 1px #000; border-collapse: collapse''>Dec 5, 01:00 UTC (Dec 4, 20:00 US/Eastern)</td><td style=''border: solid 1px #000; border-collapse: collapse''>Dec 5, 06:00 UTC (Dec 5, 01:00 US/Eastern)</td><td style=''border: solid 1px #000; border-collapse: collapse''>Nov 14, 01:00 UTC (Nov 13, 20:00 US/Eastern)</td><td style=''border: solid 1px #000; border-collapse: collapse''>Nov 14, 06:00 UTC (Nov 14, 01:00 US/Eastern)</td></tr></table>', '2016-03-28 14:37:32', '2016-03-28 18:34:40'),
(3, 'Cynthia Davis', 'cynthia.davis@veeva.com', '<table cellpadding=''5'' cellspacing=''0'' style=''border: solid 1px #000; border-collapse: collapse''><tr style=''background:#f89829; text-align:center; vertical-align:bottom;''><th style=''border: solid 1px #000; border-collapse: collapse; text-align:center; vertical-align:bottom;''>Production Org ID</th><th style=''border: solid 1px #000; text-align:center; vertical-align:bottom;''>Org Name</th><th style=''border: solid 1px #000; text-align:center; vertical-align:bottom;''>Prod Maintenance Start</th><th style=''border: solid 1px #000; border-collapse: collapse text-align:center; vertical-align:bottom;''>Prod Maintenance End</th><th style=''border: solid 1px #000; text-align:center; vertical-align:bottom;''>Full Sandbox Maintenance Start</th><th style=''border: solid 1px #000; border-collapse: collapse text-align:center; vertical-align:bottom;''>Full Sandbox Maintenance End</th></tr><tr><td style=''border: solid 1px #000; border-collapse: collapse''>00Dd0000000iRJF</td><td style=''border: solid 1px #000; border-collapse: collapse''>Antares</td><td style=''border: solid 1px #000; border-collapse: collapse''>Dec 5, 01:00 UTC (Dec 4, 20:00 US/Eastern)</td><td style=''border: solid 1px #000; border-collapse: collapse''>Dec 5, 06:00 UTC (Dec 5, 01:00 US/Eastern)</td><td style=''border: solid 1px #000; border-collapse: collapse''>Nov 14, 01:00 UTC (Nov 13, 20:00 US/Eastern)</td><td style=''border: solid 1px #000; border-collapse: collapse''>Nov 14, 06:00 UTC (Nov 14, 01:00 US/Eastern)</td></tr></table>', '2016-03-28 14:37:32', '2016-03-28 18:34:35'),
(4492, 'Arun', 'arun@dotsolved.com', '<table cellpadding=''5'' cellspacing=''0'' style=''border: solid 1px #000; border-collapse: collapse''><tr style=''background:#f89829; text-align:center; vertical-align:bottom;''><th style=''border: solid 1px #000; border-collapse: collapse; text-align:center; vertical-align:bottom;''>Production Org ID</th><th style=''border: solid 1px #000; text-align:center; vertical-align:bottom;''>Org Name</th><th style=''border: solid 1px #000; text-align:center; vertical-align:bottom;''>Prod Maintenance Start</th><th style=''border: solid 1px #000; border-collapse: collapse text-align:center; vertical-align:bottom;''>Prod Maintenance End</th><th style=''border: solid 1px #000; text-align:center; vertical-align:bottom;''>Full Sandbox Maintenance Start</th><th style=''border: solid 1px #000; border-collapse: collapse text-align:center; vertical-align:bottom;''>Full Sandbox Maintenance End</th></tr><tr><td style=''border: solid 1px #000; border-collapse: collapse''>00DF000000052LV</td><td style=''border: solid 1px #000; border-collapse: collapse''>Arbor Pharma</td><td style=''border: solid 1px #000; border-collapse: collapse''>Dec 5, 01:00 UTC (Dec 4, 20:00 US/Eastern)</td><td style=''border: solid 1px #000; border-collapse: collapse''>Dec 5, 06:00 UTC (Dec 5, 01:00 US/Eastern)</td><td style=''border: solid 1px #000; border-collapse: collapse''>Nov 14, 01:00 UTC (Nov 13, 20:00 US/Eastern)</td><td style=''border: solid 1px #000; border-collapse: collapse''>Nov 14, 06:00 UTC (Nov 14, 01:00 US/Eastern)</td></tr></table>', '2016-03-28 14:37:32', '2016-03-28 18:34:30'),
(4493, 'Kannan', 'arun@dotsolved.com', '<table cellpadding=''5'' cellspacing=''0'' style=''border: solid 1px #000; border-collapse: collapse''><tr style=''background:#f89829; text-align:center; vertical-align:bottom;''><th style=''border: solid 1px #000; border-collapse: collapse; text-align:center; vertical-align:bottom;''>Production Org ID</th><th style=''border: solid 1px #000; text-align:center; vertical-align:bottom;''>Org Name</th><th style=''border: solid 1px #000; text-align:center; vertical-align:bottom;''>Prod Maintenance Start</th><th style=''border: solid 1px #000; border-collapse: collapse text-align:center; vertical-align:bottom;''>Prod Maintenance End</th><th style=''border: solid 1px #000; text-align:center; vertical-align:bottom;''>Full Sandbox Maintenance Start</th><th style=''border: solid 1px #000; border-collapse: collapse text-align:center; vertical-align:bottom;''>Full Sandbox Maintenance End</th></tr><tr><td style=''border: solid 1px #000; border-collapse: collapse''>00Dd0000000iRJF</td><td style=''border: solid 1px #000; border-collapse: collapse''>Antares</td><td style=''border: solid 1px #000; border-collapse: collapse''>Dec 5, 01:00 UTC (Dec 4, 20:00 US/Eastern)</td><td style=''border: solid 1px #000; border-collapse: collapse''>Dec 5, 06:00 UTC (Dec 5, 01:00 US/Eastern)</td><td style=''border: solid 1px #000; border-collapse: collapse''>Nov 14, 01:00 UTC (Nov 13, 20:00 US/Eastern)</td><td style=''border: solid 1px #000; border-collapse: collapse''>Nov 14, 06:00 UTC (Nov 14, 01:00 US/Eastern)</td></tr></table>', '2016-03-28 14:37:32', '2016-03-28 18:34:26');

-- --------------------------------------------------------

--
-- Table structure for table `email_list_temp`
--

CREATE TABLE `email_list_temp` (
  `id` int(11) NOT NULL,
  `org_admin` varchar(100) DEFAULT NULL,
  `firstname` varchar(220) DEFAULT NULL,
  `lastname` varchar(220) DEFAULT NULL,
  `email` varchar(220) DEFAULT NULL,
  `org_id` varchar(100) DEFAULT NULL,
  `deployment_name` varchar(220) DEFAULT NULL,
  `deployment_org_region` varchar(220) DEFAULT NULL,
  `maintenance_start` varchar(220) DEFAULT NULL,
  `maintenance_end` varchar(220) DEFAULT NULL,
  `sandbox_region` varchar(100) DEFAULT NULL,
  `sandbox_maintenance_start` varchar(220) DEFAULT NULL,
  `sandbox_maintenance_end` varchar(220) DEFAULT NULL,
  `import_date` datetime DEFAULT NULL,
  `mail_sent` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `email_template`
--

CREATE TABLE `email_template` (
  `pkey` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `from_name` varchar(255) NOT NULL,
  `from_email` varchar(255) NOT NULL,
  `university` int(20) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `body` text NOT NULL,
  `status` int(10) NOT NULL,
  `job` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `email_template`
--

INSERT INTO `email_template` (`pkey`, `name`, `from_name`, `from_email`, `university`, `subject`, `body`, `status`, `job`) VALUES
(5, 'Par Speciality', 'Veeva', 'noreply@veeva.com', 0, 'Maintanence Schedule', 'Hello {user}<div><br></div><div>Excuse me you have maintenance</div><div><br></div><div>{table_content}</div><div><br></div><div>With Regards,</div><div>Veeva</div><br><br>', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `display_name` varchar(50) DEFAULT NULL,
  `password` varchar(128) NOT NULL,
  `state` smallint(5) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `username`, `email`, `display_name`, `password`, `state`) VALUES
(1, 'superadmin', 'superadmin@veeva.com', 'Super Admin', '$2y$14$5np9087hVhiRVYI657CSwuIaxK.iKXd2GnOauBYjw8Leisf/Tyqau', 1),
(2, 'admin', 'admin1@hotchalk.com', 'Admin', '$2y$14$raCUNUT5hb1RbPhY3VGQhucmTy2bjatzOVjinCQkrJxhklzTv8NUG', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_password_reset`
--

CREATE TABLE `user_password_reset` (
  `request_key` varchar(32) NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `request_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE `user_role` (
  `id` int(11) NOT NULL,
  `role_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `parent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` (`id`, `role_id`, `is_default`, `parent_id`) VALUES
(1, 'super-admin', 0, NULL),
(2, 'admin', 0, NULL),
(3, 'applicant', 0, NULL),
(4, 'faculty', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_role_linker`
--

CREATE TABLE `user_role_linker` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user_role_linker`
--

INSERT INTO `user_role_linker` (`user_id`, `role_id`) VALUES
(1, 1),
(2, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `email_content`
--
ALTER TABLE `email_content`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_list`
--
ALTER TABLE `email_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_list_old`
--
ALTER TABLE `email_list_old`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_list_temp`
--
ALTER TABLE `email_list_temp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_template`
--
ALTER TABLE `email_template`
  ADD PRIMARY KEY (`pkey`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `username` (`username`),
  ADD KEY `email` (`email`);

--
-- Indexes for table `user_password_reset`
--
ALTER TABLE `user_password_reset`
  ADD PRIMARY KEY (`request_key`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_role` (`role_id`),
  ADD KEY `idx_parent_id` (`parent_id`);

--
-- Indexes for table `user_role_linker`
--
ALTER TABLE `user_role_linker`
  ADD PRIMARY KEY (`user_id`,`role_id`),
  ADD KEY `idx_role_id` (`role_id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `email_content`
--
ALTER TABLE `email_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT for table `email_list`
--
ALTER TABLE `email_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `email_list_old`
--
ALTER TABLE `email_list_old`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4494;
--
-- AUTO_INCREMENT for table `email_list_temp`
--
ALTER TABLE `email_list_temp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `email_template`
--
ALTER TABLE `email_template`
  MODIFY `pkey` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `user_role`
--
ALTER TABLE `user_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
