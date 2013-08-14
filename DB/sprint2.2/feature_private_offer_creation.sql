CREATE DATABASE IF NOT EXISTS `aatp`;
USE `aatp`;

DROP TABLE IF EXISTS `ProjectsPrivateOffer`;

CREATE TABLE IF NOT EXISTS `ProjectsPrivateOffer` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ProjectNumber` int(10) unsigned NOT NULL,
  `ISBN` varchar(10) NOT NULL,
  `ConnectPlusISBN` varchar(10) DEFAULT NULL,
  `RequesterName` varchar(255) NOT NULL,
  `RequesterEmail` varchar(255) NOT NULL,
  `LscID` int(10) unsigned NOT NULL,
  `DateNeeded` int(10) unsigned NOT NULL,
  `CreativeContactID` int(10) unsigned NOT NULL,
  `ConnectionType` varchar(255) DEFAULT NULL,
  `SchoolName` varchar(255) DEFAULT NULL,
  `SchoolCity` varchar(255) DEFAULT NULL,
  `CampusName` varchar(255) DEFAULT NULL,
  `Duration` int(10) unsigned DEFAULT NULL,
  `PriceType` varchar(255) NOT NULL,
  `Price` float unsigned NOT NULL,
  `ScreenshotLink` varchar(255) DEFAULT NULL,
  `Status` tinyint NOT NULL DEFAULT 1,
  `Created` int(10) unsigned NOT NULL,
  `CreatedUsersID` int(10) unsigned NOT NULL,
  `CreatedIPAddress` varchar(64) NOT NULL,
  `Modified` int(10) unsigned DEFAULT NULL,
  `ModifiedUsersID` int(10) unsigned DEFAULT NULL,
  `ModifiedIPAddress` varchar(64) DEFAULT NULL,
  `Deleted` int(10) unsigned DEFAULT NULL,
  `DeletedUsersID` int(10) unsigned DEFAULT NULL,
  `DeletedIPAddress` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ProjectsPrivateOfferMilestones`;

CREATE TABLE `ProjectsPrivateOfferMilestones` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ProjectsID` int(10) unsigned NOT NULL,
  `Name` varchar(255) NOT NULL,
  `CustomerApproval` tinyint(1) NOT NULL,
  `Summary` text NOT NULL,
  `ExpectedDeliveryDate` int(10) unsigned NOT NULL,
  `ActualDeliveryDate` int(10) unsigned NOT NULL,
  `EstimatedStartDate` int(11) DEFAULT NULL,
  `PlantAllocated` varchar(255) NOT NULL,
  `AssignedTo` int(10) DEFAULT NULL,
  `Status` enum('Active','Complete') NOT NULL,
  `Created` int(10) unsigned NOT NULL,
  `CreatedUsersID` int(10) unsigned NOT NULL,
  `CreatedIPAddress` varchar(64) NOT NULL,
  `Modified` int(10) unsigned DEFAULT NULL,
  `ModifiedUsersID` int(10) unsigned DEFAULT NULL,
  `ModifiedIPAddress` varchar(64) DEFAULT NULL,
  `Deleted` int(10) unsigned DEFAULT NULL,
  `DeletedUsersID` int(10) unsigned DEFAULT NULL,
  `DeletedIPAddress` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`ID`)
);

DROP TABLE IF EXISTS `ProjectsPrivateOfferMilestonesToDos`;

CREATE TABLE `ProjectsPrivateOfferMilestonesToDos` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `MilestoneID` int(10) unsigned NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Comment` text NOT NULL,
  `CommentRequired` tinyint(1) NOT NULL,
  `Complete` int(10) unsigned NOT NULL,
  `Active` tinyint(1) NOT NULL,
  `AssignedTo` int(10) DEFAULT NULL,
  `Created` int(10) unsigned NOT NULL,
  `CreatedUsersID` int(10) unsigned NOT NULL,
  `CreatedIPAddress` varchar(64) NOT NULL,
  `Modified` int(10) unsigned DEFAULT NULL,
  `ModifiedUsersID` int(10) unsigned DEFAULT NULL,
  `ModifiedIPAddress` varchar(64) DEFAULT NULL,
  `Deleted` int(10) unsigned DEFAULT NULL,
  `DeletedUsersID` int(10) unsigned DEFAULT NULL,
  `DeletedIPAddress` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`ID`)
);

