-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.5.20-log - MySQL Community Server (GPL)
-- Server OS:                    Win32
-- HeidiSQL version:             7.0.0.4093
-- Date/time:                    2013-08-12 20:04:09
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET FOREIGN_KEY_CHECKS=0 */;

-- Dumping database structure for aatp
CREATE DATABASE IF NOT EXISTS `aatp` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `aatp`;


-- Dumping structure for table aatp.projectsprivateoffer
CREATE TABLE IF NOT EXISTS `ProjectsPrivateOffer` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ProjectNumber` int(10) unsigned NOT NULL,
  `ISBN` varchar(10) NOT NULL,
  `ConnectPlusISBN` varchar(10) DEFAULT NULL,
  `RequesterName` varchar(50) NOT NULL,
  `RequesterEmail` varchar(50) NOT NULL,
  `LscID` int(10) unsigned NOT NULL,
  `DateNeeded` int(10) unsigned NOT NULL,
  `CreativeContactID` int(10) unsigned NOT NULL,
  `ConnectionType` varchar(50) DEFAULT NULL,
  `SchoolName` varchar(50) DEFAULT NULL,
  `SchoolCity` varchar(50) DEFAULT NULL,
  `CampusName` varchar(50) DEFAULT NULL,
  `Duration` int(10) unsigned DEFAULT NULL,
  `PriceType` varchar(50) NOT NULL,
  `Price` float unsigned NOT NULL,
  `Created` int(10) unsigned NOT NULL,
  `CreatedIPAddress` varchar(64) NOT NULL,
  `Modified` int(10) unsigned DEFAULT NULL,
  `ModifiedIPAddress` varchar(64) DEFAULT NULL,
  `Deleted` int(10) unsigned DEFAULT NULL,
  `DeletedIPAddress` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;