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
  `Created` int(10) NOT NULL,
  `CreatedIPAddress` varchar(64) NOT NULL,
  PRIMARY KEY (`ID`)
);
