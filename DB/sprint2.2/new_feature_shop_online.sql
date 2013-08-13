DROP TABLE IF EXISTS `ProjectsShopOnline`;

CREATE TABLE `ProjectsShopOnline` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `ISBN10` varchar(10) DEFAULT NULL,
  `Author` varchar(255) DEFAULT NULL,
  `RequesterName` varchar(255) DEFAULT NULL,
  `RequesterEmail` varchar(255) DEFAULT NULL,
  `DateNeeded` int(10) DEFAULT NULL,
  `UsersID` int(10) DEFAULT NULL,
  `Comments` text,
  `CustomCoverURL` varchar(255) DEFAULT NULL,
  `ISBNType` enum('PPK','Physical','COMBO','Virtual/ECOM') NOT NULL,
  `VirtualECOMInstructionsShop` varchar(255) DEFAULT NULL,
  `VirtualECOMInstructionsEmail` varchar(255) DEFAULT NULL,
  `Status` tinyint NOT NULL DEFAULT 1,
  `CompleteDate` int(10) NULL,
  `Created` int(10) NOT NULL,
  `CreatedIPAddress` varchar(64) NOT NULL,
  `Deleted` int(10) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`)
);

DROP TABLE IF EXISTS `ProjectsShopOnlineMilestones`;

CREATE TABLE `ProjectsShopOnlineMilestones` (
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
  `Deleted` int(10) unsigned NOT NULL,
  `DeletedUsersID` int(10) unsigned NOT NULL,
  `DeletedIPAddress` varchar(64) NOT NULL,
  PRIMARY KEY (`ID`)
);

