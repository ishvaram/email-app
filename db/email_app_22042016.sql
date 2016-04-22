-- MySQL dump 10.13  Distrib 5.6.28, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: email_app
-- ------------------------------------------------------
-- Server version	5.6.28-0ubuntu0.15.10.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `email_list`
--

DROP TABLE IF EXISTS `email_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `email_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `org_admin` varchar(100) DEFAULT NULL,
  `firstname` varchar(220) DEFAULT NULL,
  `lastname` varchar(220) DEFAULT NULL,
  `email` varchar(220) DEFAULT NULL,
  `org_id` varchar(100) DEFAULT NULL,
  `deployment_name` varchar(220) DEFAULT NULL,
  `deployment_org_region` varchar(220) DEFAULT NULL,
  `maintenance_start` varchar(100) DEFAULT NULL,
  `maintenance_end` varchar(100) DEFAULT NULL,
  `sandbox_region` varchar(100) DEFAULT NULL,
  `sandbox_maintenance_start` varchar(100) DEFAULT NULL,
  `sandbox_maintenance_end` varchar(100) DEFAULT NULL,
  `import_date` datetime DEFAULT NULL,
  `mail_sent` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email_list`
--

LOCK TABLES `email_list` WRITE;
/*!40000 ALTER TABLE `email_list` DISABLE KEYS */;
INSERT INTO `email_list` VALUES (1,'2825','ram','kovalan','jehovaram@gmail.com','DULe5b','Kythera','Americas','2015-12-08 02:17:37','2015-12-31 13:34:17','Edmonton','2015-11-03 04:16:13','2015-11-17 03:00:07','0000-00-00 00:00:00','2016-04-21 11:11:20'),(2,'2836','ajith','krishnan','kovalan@dotsolved.com','DULe5c','Kythera','Americas','2015-12-08 02:17:37','2015-12-18 02:17:37',NULL,NULL,NULL,NULL,'2016-04-21 11:11:14');
/*!40000 ALTER TABLE `email_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `email_template`
--

DROP TABLE IF EXISTS `email_template`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `email_template` (
  `pkey` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `from_name` varchar(255) NOT NULL,
  `from_email` varchar(255) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `body` text NOT NULL,
  `status` int(10) NOT NULL,
  PRIMARY KEY (`pkey`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email_template`
--

LOCK TABLES `email_template` WRITE;
/*!40000 ALTER TABLE `email_template` DISABLE KEYS */;
INSERT INTO `email_template` VALUES (5,'Par Speciality','Veeva','noreply@veeva.com','Maintanence Schedule','Hello {user}<div><br></div><div>Excuse me you have maintenance</div><div><br></div><div>{table_content}</div><div><br></div><div>With Regards,</div><div>Veeva</div><br><br><br>',1),(7,'Test Indication','Kovalan','jehovaram@gmail.com','Test template','Dear sir,',1),(8,'Test Dose','Kovalan teser','jehovaram@gmail.com','Test template jarvis','catch me if yu can',0);
/*!40000 ALTER TABLE `email_template` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mail_content`
--

DROP TABLE IF EXISTS `mail_content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mail_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text,
  `email` text,
  `content` text,
  `import_date` datetime DEFAULT NULL,
  `mail_sent` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4494 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mail_content`
--

LOCK TABLES `mail_content` WRITE;
/*!40000 ALTER TABLE `mail_content` DISABLE KEYS */;
INSERT INTO `mail_content` VALUES (1,'Adrienne Myers','adrienne.myers@veeva.com','<table cellpadding=\'5\' cellspacing=\'0\' style=\'border: solid 1px #000; border-collapse: collapse\'><tr style=\'background:#f89829; text-align:center; vertical-align:bottom;\'><th style=\'border: solid 1px #000; border-collapse: collapse; text-align:center; vertical-align:bottom;\'>Production Org ID</th><th style=\'border: solid 1px #000; text-align:center; vertical-align:bottom;\'>Org Name</th><th style=\'border: solid 1px #000; text-align:center; vertical-align:bottom;\'>Prod Maintenance Start</th><th style=\'border: solid 1px #000; border-collapse: collapse text-align:center; vertical-align:bottom;\'>Prod Maintenance End</th><th style=\'border: solid 1px #000; text-align:center; vertical-align:bottom;\'>Full Sandbox Maintenance Start</th><th style=\'border: solid 1px #000; border-collapse: collapse text-align:center; vertical-align:bottom;\'>Full Sandbox Maintenance End</th></tr><tr><td style=\'border: solid 1px #000; border-collapse: collapse\'>00DU0000000J38w</td><td style=\'border: solid 1px #000; border-collapse: collapse\'>Par Specialty</td><td style=\'border: solid 1px #000; border-collapse: collapse\'>Dec 5, 01:00 UTC (Dec 4, 20:00 US/Eastern)</td><td style=\'border: solid 1px #000; border-collapse: collapse\'>Dec 5, 06:00 UTC (Dec 5, 01:00 US/Eastern)</td><td style=\'border: solid 1px #000; border-collapse: collapse\'>Nov 14, 01:00 UTC (Nov 13, 20:00 US/Eastern)</td><td style=\'border: solid 1px #000; border-collapse: collapse\'>Nov 14, 06:00 UTC (Nov 14, 01:00 US/Eastern)</td></tr></table>','2016-03-28 14:37:32','2016-03-28 18:34:45'),(2,'Prasad Ramakrishnan','prasad.ramakrishnan@veeva.com','<table cellpadding=\'5\' cellspacing=\'0\' style=\'border: solid 1px #000; border-collapse: collapse\'><tr style=\'background:#f89829; text-align:center; vertical-align:bottom;\'><th style=\'border: solid 1px #000; border-collapse: collapse; text-align:center; vertical-align:bottom;\'>Production Org ID</th><th style=\'border: solid 1px #000; text-align:center; vertical-align:bottom;\'>Org Name</th><th style=\'border: solid 1px #000; text-align:center; vertical-align:bottom;\'>Prod Maintenance Start</th><th style=\'border: solid 1px #000; border-collapse: collapse text-align:center; vertical-align:bottom;\'>Prod Maintenance End</th><th style=\'border: solid 1px #000; text-align:center; vertical-align:bottom;\'>Full Sandbox Maintenance Start</th><th style=\'border: solid 1px #000; border-collapse: collapse text-align:center; vertical-align:bottom;\'>Full Sandbox Maintenance End</th></tr><tr><td style=\'border: solid 1px #000; border-collapse: collapse\'>00DF000000052LV</td><td style=\'border: solid 1px #000; border-collapse: collapse\'>Arbor Pharma</td><td style=\'border: solid 1px #000; border-collapse: collapse\'>Dec 5, 01:00 UTC (Dec 4, 20:00 US/Eastern)</td><td style=\'border: solid 1px #000; border-collapse: collapse\'>Dec 5, 06:00 UTC (Dec 5, 01:00 US/Eastern)</td><td style=\'border: solid 1px #000; border-collapse: collapse\'>Nov 14, 01:00 UTC (Nov 13, 20:00 US/Eastern)</td><td style=\'border: solid 1px #000; border-collapse: collapse\'>Nov 14, 06:00 UTC (Nov 14, 01:00 US/Eastern)</td></tr></table>','2016-03-28 14:37:32','2016-03-28 18:34:40'),(3,'Cynthia Davis','cynthia.davis@veeva.com','<table cellpadding=\'5\' cellspacing=\'0\' style=\'border: solid 1px #000; border-collapse: collapse\'><tr style=\'background:#f89829; text-align:center; vertical-align:bottom;\'><th style=\'border: solid 1px #000; border-collapse: collapse; text-align:center; vertical-align:bottom;\'>Production Org ID</th><th style=\'border: solid 1px #000; text-align:center; vertical-align:bottom;\'>Org Name</th><th style=\'border: solid 1px #000; text-align:center; vertical-align:bottom;\'>Prod Maintenance Start</th><th style=\'border: solid 1px #000; border-collapse: collapse text-align:center; vertical-align:bottom;\'>Prod Maintenance End</th><th style=\'border: solid 1px #000; text-align:center; vertical-align:bottom;\'>Full Sandbox Maintenance Start</th><th style=\'border: solid 1px #000; border-collapse: collapse text-align:center; vertical-align:bottom;\'>Full Sandbox Maintenance End</th></tr><tr><td style=\'border: solid 1px #000; border-collapse: collapse\'>00Dd0000000iRJF</td><td style=\'border: solid 1px #000; border-collapse: collapse\'>Antares</td><td style=\'border: solid 1px #000; border-collapse: collapse\'>Dec 5, 01:00 UTC (Dec 4, 20:00 US/Eastern)</td><td style=\'border: solid 1px #000; border-collapse: collapse\'>Dec 5, 06:00 UTC (Dec 5, 01:00 US/Eastern)</td><td style=\'border: solid 1px #000; border-collapse: collapse\'>Nov 14, 01:00 UTC (Nov 13, 20:00 US/Eastern)</td><td style=\'border: solid 1px #000; border-collapse: collapse\'>Nov 14, 06:00 UTC (Nov 14, 01:00 US/Eastern)</td></tr></table>','2016-03-28 14:37:32','2016-03-28 18:34:35'),(4492,'Arun','arun@dotsolved.com','<table cellpadding=\'5\' cellspacing=\'0\' style=\'border: solid 1px #000; border-collapse: collapse\'><tr style=\'background:#f89829; text-align:center; vertical-align:bottom;\'><th style=\'border: solid 1px #000; border-collapse: collapse; text-align:center; vertical-align:bottom;\'>Production Org ID</th><th style=\'border: solid 1px #000; text-align:center; vertical-align:bottom;\'>Org Name</th><th style=\'border: solid 1px #000; text-align:center; vertical-align:bottom;\'>Prod Maintenance Start</th><th style=\'border: solid 1px #000; border-collapse: collapse text-align:center; vertical-align:bottom;\'>Prod Maintenance End</th><th style=\'border: solid 1px #000; text-align:center; vertical-align:bottom;\'>Full Sandbox Maintenance Start</th><th style=\'border: solid 1px #000; border-collapse: collapse text-align:center; vertical-align:bottom;\'>Full Sandbox Maintenance End</th></tr><tr><td style=\'border: solid 1px #000; border-collapse: collapse\'>00DF000000052LV</td><td style=\'border: solid 1px #000; border-collapse: collapse\'>Arbor Pharma</td><td style=\'border: solid 1px #000; border-collapse: collapse\'>Dec 5, 01:00 UTC (Dec 4, 20:00 US/Eastern)</td><td style=\'border: solid 1px #000; border-collapse: collapse\'>Dec 5, 06:00 UTC (Dec 5, 01:00 US/Eastern)</td><td style=\'border: solid 1px #000; border-collapse: collapse\'>Nov 14, 01:00 UTC (Nov 13, 20:00 US/Eastern)</td><td style=\'border: solid 1px #000; border-collapse: collapse\'>Nov 14, 06:00 UTC (Nov 14, 01:00 US/Eastern)</td></tr></table>','2016-03-28 14:37:32','2016-03-28 18:34:30'),(4493,'Kannan','jeyakannan@dotsolved.com','<table cellpadding=\'5\' cellspacing=\'0\' style=\'border: solid 1px #000; border-collapse: collapse\'><tr style=\'background:#f89829; text-align:center; vertical-align:bottom;\'><th style=\'border: solid 1px #000; border-collapse: collapse; text-align:center; vertical-align:bottom;\'>Production Org ID</th><th style=\'border: solid 1px #000; text-align:center; vertical-align:bottom;\'>Org Name</th><th style=\'border: solid 1px #000; text-align:center; vertical-align:bottom;\'>Prod Maintenance Start</th><th style=\'border: solid 1px #000; border-collapse: collapse text-align:center; vertical-align:bottom;\'>Prod Maintenance End</th><th style=\'border: solid 1px #000; text-align:center; vertical-align:bottom;\'>Full Sandbox Maintenance Start</th><th style=\'border: solid 1px #000; border-collapse: collapse text-align:center; vertical-align:bottom;\'>Full Sandbox Maintenance End</th></tr><tr><td style=\'border: solid 1px #000; border-collapse: collapse\'>00Dd0000000iRJF</td><td style=\'border: solid 1px #000; border-collapse: collapse\'>Antares</td><td style=\'border: solid 1px #000; border-collapse: collapse\'>Dec 5, 01:00 UTC (Dec 4, 20:00 US/Eastern)</td><td style=\'border: solid 1px #000; border-collapse: collapse\'>Dec 5, 06:00 UTC (Dec 5, 01:00 US/Eastern)</td><td style=\'border: solid 1px #000; border-collapse: collapse\'>Nov 14, 01:00 UTC (Nov 13, 20:00 US/Eastern)</td><td style=\'border: solid 1px #000; border-collapse: collapse\'>Nov 14, 06:00 UTC (Nov 14, 01:00 US/Eastern)</td></tr></table>','2016-03-28 14:37:32','2016-03-28 18:34:26');
/*!40000 ALTER TABLE `mail_content` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `display_name` varchar(50) DEFAULT NULL,
  `password` varchar(128) NOT NULL,
  `state` smallint(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  KEY `username` (`username`),
  KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'superadmin','superadmin@veeva.com','Super Admin','$2y$14$5np9087hVhiRVYI657CSwuIaxK.iKXd2GnOauBYjw8Leisf/Tyqau',1),(2,'admin','admin1@hotchalk.com','Admin','$2y$14$raCUNUT5hb1RbPhY3VGQhucmTy2bjatzOVjinCQkrJxhklzTv8NUG',1);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_password_reset`
--

DROP TABLE IF EXISTS `user_password_reset`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_password_reset` (
  `request_key` varchar(32) NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `request_time` datetime NOT NULL,
  PRIMARY KEY (`request_key`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_password_reset`
--

LOCK TABLES `user_password_reset` WRITE;
/*!40000 ALTER TABLE `user_password_reset` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_password_reset` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_role`
--

DROP TABLE IF EXISTS `user_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `parent_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_role` (`role_id`),
  KEY `idx_parent_id` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_role`
--

LOCK TABLES `user_role` WRITE;
/*!40000 ALTER TABLE `user_role` DISABLE KEYS */;
INSERT INTO `user_role` VALUES (1,'super-admin',0,NULL),(2,'admin',0,NULL),(3,'applicant',0,NULL),(4,'faculty',0,NULL);
/*!40000 ALTER TABLE `user_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_role_linker`
--

DROP TABLE IF EXISTS `user_role_linker`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_role_linker` (
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `idx_role_id` (`role_id`),
  KEY `idx_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_role_linker`
--

LOCK TABLES `user_role_linker` WRITE;
/*!40000 ALTER TABLE `user_role_linker` DISABLE KEYS */;
INSERT INTO `user_role_linker` VALUES (1,1),(2,2);
/*!40000 ALTER TABLE `user_role_linker` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-04-22 10:42:23
