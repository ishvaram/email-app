-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 18, 2016 at 08:29 AM
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
  `maintenance_start` datetime DEFAULT NULL,
  `maintenance_end` datetime DEFAULT NULL,
  `sandbox_region` varchar(100) DEFAULT NULL,
  `sandbox_maintenance_start` datetime DEFAULT NULL,
  `sandbox_maintenance_end` datetime DEFAULT NULL,
  `import_date` datetime DEFAULT NULL,
  `mail_sent` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `email_list`
--

INSERT INTO `email_list` (`id`, `org_admin`, `firstname`, `lastname`, `email`, `org_id`, `deployment_name`, `deployment_org_region`, `maintenance_start`, `maintenance_end`, `sandbox_region`, `sandbox_maintenance_start`, `sandbox_maintenance_end`, `import_date`, `mail_sent`) VALUES
(1, '2835', 'Pete', 'Abbey', 'hayanthikapj@gmail.com', 'DULe5b', 'Kythera', 'Americas', '2015-12-08 02:17:37', '2015-12-31 13:34:17', '', '2015-11-03 04:16:13', '2015-11-17 03:00:07', '0000-00-00 00:00:00', '2016-03-30 12:44:59');

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
(4493, 'Kannan', 'jeyakannan@dotsolved.com', '<table cellpadding=''5'' cellspacing=''0'' style=''border: solid 1px #000; border-collapse: collapse''><tr style=''background:#f89829; text-align:center; vertical-align:bottom;''><th style=''border: solid 1px #000; border-collapse: collapse; text-align:center; vertical-align:bottom;''>Production Org ID</th><th style=''border: solid 1px #000; text-align:center; vertical-align:bottom;''>Org Name</th><th style=''border: solid 1px #000; text-align:center; vertical-align:bottom;''>Prod Maintenance Start</th><th style=''border: solid 1px #000; border-collapse: collapse text-align:center; vertical-align:bottom;''>Prod Maintenance End</th><th style=''border: solid 1px #000; text-align:center; vertical-align:bottom;''>Full Sandbox Maintenance Start</th><th style=''border: solid 1px #000; border-collapse: collapse text-align:center; vertical-align:bottom;''>Full Sandbox Maintenance End</th></tr><tr><td style=''border: solid 1px #000; border-collapse: collapse''>00Dd0000000iRJF</td><td style=''border: solid 1px #000; border-collapse: collapse''>Antares</td><td style=''border: solid 1px #000; border-collapse: collapse''>Dec 5, 01:00 UTC (Dec 4, 20:00 US/Eastern)</td><td style=''border: solid 1px #000; border-collapse: collapse''>Dec 5, 06:00 UTC (Dec 5, 01:00 US/Eastern)</td><td style=''border: solid 1px #000; border-collapse: collapse''>Nov 14, 01:00 UTC (Nov 13, 20:00 US/Eastern)</td><td style=''border: solid 1px #000; border-collapse: collapse''>Nov 14, 06:00 UTC (Nov 14, 01:00 US/Eastern)</td></tr></table>', '2016-03-28 14:37:32', '2016-03-28 18:34:26');

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
-- AUTO_INCREMENT for table `email_list`
--
ALTER TABLE `email_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `email_list_old`
--
ALTER TABLE `email_list_old`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4494;
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
