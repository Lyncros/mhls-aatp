/* *********************** */
/*  ADD INFO TO USERGROUPS */
/* *********************** */
  
ALTER TABLE `UsersGroups` 
ADD COLUMN `AssignableToTODO` TINYINT NULL DEFAULT 0  AFTER `SuperAdmin` ;

UPDATE UsersGroups SET AssignableToTODO = 1 WHERE name IN ('Junior Creative Analyst', 'Creative Analyst', 'Creative Consultant', 'SuperAdmin');

ALTER TABLE `ProjectsMilestonesToDos` 
ADD COLUMN `AssignedTo` INT(10) NULL DEFAULT NULL  AFTER `DeletedIPAddress` ;

/* *********** */
/*  ISSUE 137  */
/* *********** */

INSERT INTO `UsersGroups` 
(`Timestamp`, `TimestampUpdated`, `Type`, `Name`, `Active`, `SuperAdmin`, `AssignableToTODO`) 
VALUES (1343325350, 1343325350, 'Normal', 'Creative Contact', 1, 0, 1);

SELECT @CreativeContact:=`ID` FROM `UsersGroups` WHERE `Name` = 'Creative Contact';

INSERT INTO `UsersGroupsPermissions` (`UsersGroupsID`,`Type`,`Name`,`Access`,`Action`)
SELECT @CreativeContact, UGP.`Type`, UGP.`Name`, UGP.`Access`, UGP.`Action`
FROM `UsersGroupsPermissions` AS UGP 
JOIN `UsersGroups` as UG ON UGP.`UsersGroupsID` = UG.`ID`
WHERE UG.`Name` = 'Creative Consultant';

CREATE TABLE `ProjectsCreativeContacts` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ProjectsID` int(10) unsigned NOT NULL,
  `UsersID` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ID`)
);

INSERT INTO `ProjectsCreativeContacts` (`ProjectsID`, `UsersID`)
SELECT PCA.`ProjectsID`, PCA.`UsersID`
FROM `ProjectsCreativeAnalysts` AS PCA;

INSERT INTO `ProjectsCreativeContacts` (`ProjectsID`, `UsersID`)
SELECT PCC.`ProjectsID`, PCC.`UsersID`
FROM `ProjectsCreativeConsultants` AS PCC;

INSERT INTO `ProjectsCreativeContacts` (`ProjectsID`, `UsersID`)
SELECT PJCA.`ProjectsID`, PJCA.`UsersID`
FROM `ProjectsJuniorCreativeAnalysts` AS PJCA;


DROP TABLE `ProjectsCreativeAnalysts`;
DROP TABLE `ProjectsCreativeConsultants`;
DROP TABLE `ProjectsJuniorCreativeAnalysts`;

SELECT @CreativeContact:=`ID` FROM `UsersGroups` WHERE `Name` = 'Creative Contact';

UPDATE `Users`
SET `Users`.`UsersGroupsID` = @CreativeContact
WHERE `Users`.`UsersGroupsID` IN (
    SELECT `ID` FROM `UsersGroups` 
    WHERE `Name` IN ('Creative Consultant', 'Junior Creative Analyst', 'Creative Analyst'));


/* *********** */
/*  ISSUE 160  */
/* *********** */

ALTER TABLE `ProjectsMilestones` 
ADD COLUMN `Order` INT(10) NOT NULL DEFAULT 0  AFTER `EstimatedStartDate` ;

/* ******************************** */
/*  ISSUE NEW PROJECTS SHOP ONLINE  */
/* ******************************** */

DROP TABLE IF EXISTS `ProjectsShopOnline`;

CREATE TABLE `ProjectsShopOnline` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ISBN10` varchar(10) DEFAULT NULL,
  `Author` varchar(255) DEFAULT NULL,
  `RequesterName` varchar(255) DEFAULT NULL,
  `RequesterEmail` varchar(255) DEFAULT NULL,
  `DateNeeded` int(10) DEFAULT NULL,
  `UsersID` int(10) unsigned DEFAULT NULL,
  `Comments` text,
  `CustomCoverURL` varchar(255) DEFAULT NULL,
  `ISBNType` enum('PPK','Physical','COMBO','Virtual/ECOM') NOT NULL,
  `VirtualECOMInstructionsShop` varchar(255) DEFAULT NULL,
  `VirtualECOMInstructionsEmail` varchar(255) DEFAULT NULL,
  `Status` tinyint NOT NULL DEFAULT 1,
  `CompleteDate` int(10) NULL,
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
  `Modified` int(10) unsigned DEFAULT NULL,
  `ModifiedUsersID` int(10) unsigned DEFAULT NULL,
  `ModifiedIPAddress` varchar(64) DEFAULT NULL,
  `Deleted` int(10) unsigned DEFAULT NULL,
  `DeletedUsersID` int(10) unsigned DEFAULT NULL,
  `DeletedIPAddress` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`ID`)
);

DROP TABLE IF EXISTS `ProjectsShopOnlineMilestonesToDos`;

CREATE TABLE `ProjectsShopOnlineMilestonesToDos` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `MilestoneID` int(10) unsigned NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Comment` text NOT NULL,
  `CommentRequired` tinyint(1) NOT NULL,
  `Complete` int(10) unsigned NOT NULL,
  `Active` tinyint(1) NOT NULL,
  `Created` int(10) unsigned NOT NULL,
  `CreatedUsersID` int(10) unsigned NOT NULL,
  `CreatedIPAddress` varchar(64) NOT NULL,
  `Modified` int(10) unsigned DEFAULT NULL,
  `ModifiedUsersID` int(10) unsigned DEFAULT NULL,
  `ModifiedIPAddress` varchar(64) DEFAULT NULL,
  `Deleted` int(10) unsigned DEFAULT NULL,
  `DeletedUsersID` int(10) unsigned DEFAULT NULL,
  `DeletedIPAddress` varchar(64) DEFAULT NULL,
  `AssignedTo` int(10) DEFAULT NULL,
  PRIMARY KEY (`ID`)
);

DROP TABLE IF EXISTS `ProjectsShopOnlineStoreFrontItems`;

CREATE TABLE IF NOT EXISTS `ProjectsShopOnlineStoreFrontItems` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `ProjectsID` int(10) NOT NULL DEFAULT '0',
  `ISBN` varchar(10) NOT NULL DEFAULT '0',
  `Author` varchar(255) NOT NULL DEFAULT '0',
  `Virtual` varchar(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
);

/* ************************************ */
/*  CREATES NEW PROJECTS PRIVATE OFFER  */
/* ************************************ */

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

/* ***************** */
/*  ADD SPECIALITIES */
/* ***************** */

CREATE  TABLE `Specialities` (
  `ID` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `Name` VARCHAR(64) NULL ,
  `Active` TINYINT(1) NOT NULL ,
  `Created` INT(10) NOT NULL ,
  `CreatedUsersID` INT(10) NOT NULL ,
  `CreatedIPAddress` VARCHAR(64) NOT NULL ,
  `Modified` INT(10) NOT NULL ,
  `ModifiedUsersID` INT(10) NOT NULL ,
  `ModifiedIPAddress` VARCHAR(64) NOT NULL ,
  PRIMARY KEY (`ID`) );

CREATE  TABLE `ProjectsSpecialities` (
  `ID` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `ProjectsID` INT(10) UNSIGNED NOT NULL ,
  `SpecialitiesID` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`ID`) );

/* ******************** */
/* FIX PROJECST PROBLEM */
/* ******************** */
  
UPDATE Projects 
SET Created = Modified 
WHERE Created = 0;

/* *********************** */
/*  ADD INFO TO USERGROUPS */
/* *********************** */

ALTER TABLE `UsersGroups` 
ADD COLUMN `AssignableToMilestoneTODO` TINYINT(1) NULL DEFAULT 0  AFTER `AssignableToTODO` , 
ADD COLUMN `AssignableToMilestone` TINYINT(1) NULL DEFAULT 0  AFTER `AssignableToMilestoneTODO` , 
CHANGE COLUMN `AssignableToTODO` `AssignableToTODO` TINYINT(1) NULL DEFAULT 0  ;

UPDATE `UsersGroups`
SET `AssignableToTODO` = 1,
`AssignableToMilestoneTODO` = 1,
`AssignableToMilestone` = 1
WHERE `Name` IN ('Creative Contact', 'Creative Consultant', 'Junior Creative Analyst', 'Creative Analyst', 'Product Manager');

/* ******************** */
/*  ADD PROJECT VENDORS */
/* ******************** */

CREATE TABLE `Vendors` (
	`ID` INT(10) NOT NULL AUTO_INCREMENT,
	`Name` VARCHAR(50) NOT NULL,
	PRIMARY KEY (`ID`),
	INDEX `ID` (`ID`)
);

ALTER TABLE `Vendors`
ADD COLUMN `Created` INT(10) NOT NULL AFTER `Name`,
ADD COLUMN `CreatedUsersID` INT(10) NOT NULL AFTER `Created`,
ADD COLUMN `CreatedIPAddress` INT(10) NOT NULL AFTER `CreatedUsersID`,
ADD COLUMN `Modified` INT(10) NOT NULL AFTER `CreatedIPAddress`,
ADD COLUMN `ModifiedUsersID` INT(10) NOT NULL AFTER `Modified`,
ADD COLUMN `ModifiedIPAddress` INT(10) NOT NULL AFTER `ModifiedUsersID`;

CREATE TABLE `ProjectsVendors` (
	`ID` INT(10) NOT NULL AUTO_INCREMENT,
	`ProjectsID` INT(10) NOT NULL DEFAULT '0',
	`VendorsID` INT(10) NOT NULL DEFAULT '0',
	PRIMARY KEY (`ID`),
	INDEX `ID` (`ID`)
);

CREATE TABLE `ProjectsProductSolutions` (
	`ID` INT(10) NOT NULL AUTO_INCREMENT,
	`ProjectsID` INT(10) NOT NULL DEFAULT '0',
	`ProductSolutionsID` INT(10) NOT NULL DEFAULT '0',
	PRIMARY KEY (`ID`),
	INDEX `ID` (`ID`)
);

ALTER TABLE `Vendors` 
ADD COLUMN `MainContact` varchar(255) DEFAULT NULL AFTER `Name` , 
ADD COLUMN `Email` varchar(255) DEFAULT NULL AFTER `MainContact` , 
ADD COLUMN `Phone` varchar(255) DEFAULT NULL AFTER `Email`;

/* ***************** */
/*  ADD PROJECT TAGS */
/* ***************** */

CREATE  TABLE `Tags` (
  `ID` INT(10) NOT NULL ,
  `Name` VARCHAR(64) NULL ,
  `Active` TINYINT(1) NOT NULL ,
  `Created` INT(10) NOT NULL ,
  `CreatedUsersID` INT(10) NOT NULL ,
  `CreatedIPAddress` VARCHAR(64) NOT NULL ,
  `Modified` INT(10) NOT NULL ,
  `ModifiedUsersID` INT(10) NOT NULL ,
  `ModifiedIPAddress` VARCHAR(64) NOT NULL ,
  PRIMARY KEY (`ID`) );

ALTER TABLE `Tags` 
CHANGE COLUMN `ID` `ID` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT  ;

CREATE  TABLE `ProjectsTags` (
  `ID` INT(10) NOT NULL ,
  `ProjectsID` INT(10) NOT NULL ,
  `TagsID` INT(10) NOT NULL ,
  PRIMARY KEY (`ID`) );

ALTER TABLE `ProjectsTags` 
CHANGE COLUMN `ID` `ID` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT  , 
CHANGE COLUMN `ProjectsID` `ProjectsID` INT(10) UNSIGNED NOT NULL  , 
CHANGE COLUMN `TagsID` `TagsID` INT(10) UNSIGNED NOT NULL  ;

/* ******************* */
/*  ADD PROJECT FIELDS */
/* ******************* */

ALTER TABLE `Projects` 
ADD COLUMN `SpecDocLink` VARCHAR(255) NULL  AFTER `DeletedIPAddress` , 
ADD COLUMN `ConnectRequestIDLink` VARCHAR(255) NULL  AFTER `SpecDocLink` ;

/* ************************ */
/*  CREATES A PROJECTS VIEW */
/* ************************ */

CREATE VIEW ProjectsView AS 
	SELECT `Projects`.*, 
		GROUP_CONCAT(DISTINCT `Users`.`LastName` ORDER BY `Users`.`LastName` ASC SEPARATOR ', ') AS LSCs,
		GROUP_CONCAT(`Tags`.`Name` SEPARATOR ' ') as Tags
	FROM `Projects`
	LEFT JOIN `ProjectsLSCs` ON `Projects`.`ID` = `ProjectsLSCs`.`ProjectsID`
	LEFT JOIN `Users` ON `Users`.`ID` = `ProjectsLSCs`.`UsersID`
    LEFT JOIN `ProjectsTags` ON `Projects`.`ID` = `ProjectsTags`.`ProjectsID`
   	LEFT JOIN `Tags` ON `ProjectsTags`.`TagsID` = `Tags`.`ID` 
	GROUP BY `Projects`.`ID` 
	ORDER BY IF(ISNULL(`Users`.`LastName`), 1, 0), `Users`.`LastName`, `Projects`.`ID`;

/* ********************************************** */
/*  INSERT NEW USER GROUPS AND THEIR PERMISSIONS  */
/* ********************************************** */

INSERT INTO `UsersGroups` 
(`Timestamp`, `TimestampUpdated`, `Type`, `Name`, `Active`, `SuperAdmin`, `AssignableToTODO`,`AssignableToMilestoneTODO`, `AssignableToMilestone`) 
VALUES (1343325350, 1343325350, 'Normal', 'Private Offer Manager', 1, 0, 0, 0, 0);

INSERT INTO `UsersGroupsPermissions` (`UsersGroupsID`, `Type`, `Name`, `Access`, `Action`) VALUES
	(15, 'Module', 'VendorsManagement', 1, 'Access'),
	(15, 'Module', 'ProjectsShopOnline', 1, 'Access'),
	(15, 'Module', 'ProjectsShopOnline', 1, 'Edit'),
	(15, 'Module', 'ProjectsShopOnline', 1, 'Add'),
	(15, 'Module', 'ProjectsShopOnline', 1, 'Delete'),
	(15, 'Module', 'ProjectsPrivateOffer', 1, 'Access'),
	(15, 'Module', 'ProjectsPrivateOffer', 1, 'Edit'),
	(15, 'Module', 'ProjectsPrivateOffer', 1, 'Add'),
	(15, 'Module', 'ProjectsPrivateOffer', 1, 'Delete'),
	(16, 'Module', 'ProjectsPrivateOffer', 1, 'Access'),
	(16, 'Module', 'ProjectsPrivateOffer', 1, 'Edit'),
	(16, 'Module', 'ProjectsPrivateOffer', 1, 'Add'),
	(16, 'Module', 'ProjectsPrivateOffer', 1, 'Delete');

INSERT INTO `UsersGroupsPermissions` (`UsersGroupsID`, `Type`, `Name`, `Access`, `Action`) 
VALUES (1, 'Module', 'MilestonesImAssignedTo', 1, 'View');

INSERT INTO `UsersGroupsPermissions` (`UsersGroupsID`, `Type`, `Name`, `Access`, `Action`) 
VALUES (15, 'Module', 'MilestonesImAssignedTo', 1, 'View');
	
/* ******************* */
/*  INSERT NEW EMAILS  */
/* ******************* */

INSERT INTO `EmailTemplates` (`Type`, `Name`, `SubName`, `Content`, `FromName`, `FromEmail`, `ReplyTo`, `Subject`) VALUES ('Module', 'Projects', 'Assigned To-Do', '<html>\r\n	<body>\r\n		<center>\r\n			<table width=\'640\'>\r\n				<tr>\r\n					<td style=\"padding:20px; padding-top:0px; text-align:center; background-color:black;\">\r\n						<img src=\'http://[[System Action=\"GetDomain\"]]/Theme/Default/Default/Logo.png\' alt=\'The Almighty App For All Things Project\' style=\'margin-bottom:20px;\'>\r\n						<table width=\'640\' style=\'background:#ffffff; border:1px solid #E1E1E1;\' cellspacing=\'0\'>\r\n							<tr>\r\n								<th colspan=\'2\' style=\"width:33%; line-height:29px; padding-left:16px; color:#212121; white-space:nowrap; vertical-align:top; text-align:left; background-image:url(\'http://[[System Action=\"GetDomain\"]]/Theme/Default/Default/Email_TableHeaderBackground.png\');\">Project #</th>\r\n								<th colspan=\'2\' style=\"width:33%; line-height:29px; padding-left:16px; color:#212121; white-space:nowrap; vertical-align:top; text-align:left; background-image:url(\'http://[[System Action=\"GetDomain\"]]/Theme/Default/Default/Email_TableHeaderBackground.png\');\">Date/Time</th>\r\n								<th colspan=\'2\' style=\"width:33%; line-height:29px; padding-left:16px; color:#212121; white-space:nowrap; vertical-align:top; text-align:left; background-image:url(\'http://[[System Action=\"GetDomain\"]]/Theme/Default/Default/Email_TableHeaderBackground.png\');\">User</th>\r\n							</tr>\r\n							<tr>\r\n								<td colspan=\'2\' style=\'text-align:left; line-height:29px; padding-left:16px; vertical-align:top; border-top:1px solid #E1E1E1;\'>[[Data Name=\"ProjectNumber\"]]</td>\r\n								<td colspan=\'2\' style=\'text-align:left; line-height:29px; padding-left:16px; vertical-align:top; border-top:1px solid #E1E1E1;\'>[[Data Name=\"DateTime\"]]</td>\r\n								<td colspan=\'2\' style=\'text-align:left; line-height:29px; padding-left:16px; vertical-align:top; border-top:1px solid #E1E1E1;\'>[[Data Name=\"User\"]]</td>\r\n							</tr>\r\n							<tr>\r\n								<th colspan=\'6\' style=\"line-height:29px; padding-left:16px; color:#212121; white-space:nowrap; vertical-align:top; text-align:left; border-top:1px solid #E1E1E1; background-image:url(\'http://[[System Action=\"GetDomain\"]]/Theme/Default/Default/Email_TableHeaderBackground.png\');\">Name</th>\r\n							</tr>\r\n							<tr>\r\n								<td colspan=\'6\' style=\'text-align:left; line-height:29px; padding-left:16px; vertical-align:top; border-top:1px solid #E1E1E1;\'>[[Data Name=\"Name\"]]</td>\r\n							</tr>\r\n							<tr>\r\n								<th colspan=\'6\' style=\"line-height:29px; padding-left:16px; color:#212121; white-space:nowrap; vertical-align:top; text-align:left; border-top:1px solid #E1E1E1; background-image:url(\'http://[[System Action=\"GetDomain\"]]/Theme/Default/Default/Email_TableHeaderBackground.png\');\">Comment</th>\r\n							</tr>\r\n							<tr>\r\n								<td colspan=\'6\' style=\'text-align:left; padding:16px; vertical-align:top; border-top:1px solid #E1E1E1;\'>[[Data Name=\"Comment\" Format=\"nl2br\"]]</td>\r\n							</tr>\r\n							<tr>\r\n								<th colspan=\'6\' style=\"line-height:29px; padding-left:16px; color:#212121; white-space:nowrap; vertical-align:top; text-align:left; border-top:1px solid #E1E1E1; background-image:url(\'http://[[System Action=\"GetDomain\"]]/Theme/Default/Default/Email_TableHeaderBackground.png\');\">Complete</th>\r\n							</tr>\r\n							<tr>\r\n								<td colspan=\'6\' style=\'text-align:left; line-height:29px; padding-left:16px; vertical-align:top; border-top:1px solid #E1E1E1;\'>[[Data Name=\"Complete\"]]</td>\r\n							</tr>\r\n						</table>\r\n					</td>\r\n				</tr>\r\n			</table>\r\n		</center>\r\n	</body>\r\n</html>', 'McGraw Hill - AATP', 'no-reply@[[System Action=\"GetShortDomain\"]]', 'no-reply@[[System Action=\"GetShortDomain\"]]', 'You have been assigned to a Project To-Do - Project [[Data Name=\"ProjectNumber\"]]');

INSERT INTO `EmailTemplates` (`Type`, 
										`Name`, 
										`SubName`, 
										`Content`, 
										`FromName`, 
										`FromEmail`, 
										`ReplyTo`, 
										`Subject`) 
VALUES (	'Module', 
			'Projects', 
			'Request PO', 
			'<html>\r\n	<body>\r\n		<center>\r\n			<table width=\'640\'>\r\n				<tr>\r\n					<td style=\"padding:20px; padding-top:0px; text-align:center; background-color:black;\">\r\n						<img src=\'http://[[System Action=\"GetDomain\"]]/Theme/Default/Default/Logo.png\' alt=\'The Almighty App For All Things Project\' style=\'margin-bottom:20px;\'>\r\n						<table width=\'640\' style=\'background:#ffffff; border:1px solid #E1E1E1;\' cellspacing=\'0\'>\r\n							<tr>\r\n								<th colspan=\'2\' style=\"width:33%; line-height:29px; padding-left:16px; color:#212121; white-space:nowrap; vertical-align:top; text-align:left; background-image:url(\'http://[[System Action=\"GetDomain\"]]/Theme/Default/Default/Email_TableHeaderBackground.png\');\">Project #</th>\r\n								<th colspan=\'2\' style=\"width:33%; line-height:29px; padding-left:16px; color:#212121; white-space:nowrap; vertical-align:top; text-align:left; background-image:url(\'http://[[System Action=\"GetDomain\"]]/Theme/Default/Default/Email_TableHeaderBackground.png\');\">Date Requested</th>\r\n								<th colspan=\'2\' style=\"width:33%; line-height:29px; padding-left:16px; color:#212121; white-space:nowrap; vertical-align:top; text-align:left; background-image:url(\'http://[[System Action=\"GetDomain\"]]/Theme/Default/Default/Email_TableHeaderBackground.png\');\">Request By</th>\r\n							</tr>\r\n							<tr>								<td colspan=\'2\' style=\'text-align:left; line-height:29px; padding-left:16px; vertical-align:top; border-top:1px solid #E1E1E1;\'>[[Data Name=\"ProjectNumber\"]]</td>\r\n								<td colspan=\'2\' style=\'text-align:left; line-height:29px; padding-left:16px; vertical-align:top; border-top:1px solid #E1E1E1;\'>[[Data Name=\"DateTime\"]]</td>\r\n								<td colspan=\'2\' style=\'text-align:left; line-height:29px; padding-left:16px; vertical-align:top; border-top:1px solid #E1E1E1;\'>[[Data Name=\"User\"]]</td>\r\n							</tr>\r\n							<tr>\r\n								<th colspan=\'6\' style=\"line-height:29px; padding-left:16px; color:#212121; white-space:nowrap; vertical-align:top; text-align:left; border-top:1px solid #E1E1E1; background-image:url(\'http://[[System Action=\"GetDomain\"]]/Theme/Default/Default/Email_TableHeaderBackground.png\');\">Budget</th>\r\n							</tr>\r\n							<tr>\r\n								<td colspan=\'6\' style=\'text-align:left; line-height:29px; padding-left:16px; vertical-align:top; border-top:1px solid #E1E1E1;\'>[[Data Name=\"Budget\"]]</td>\r\n							</tr>\r\n							<tr>\r\n								<th colspan=\'6\' style=\"line-height:29px; padding-left:16px; color:#212121; white-space:nowrap; vertical-align:top; text-align:left; border-top:1px solid #E1E1E1; background-image:url(\'http://[[System Action=\"GetDomain\"]]/Theme/Default/Default/Email_TableHeaderBackground.png\');\">School</th>\r\n							</tr>\r\n							<tr>\r\n								<td colspan=\'6\' style=\'text-align:left; line-height:29px; padding-left:16px; vertical-align:top; border-top:1px solid #E1E1E1;\'>[[Data Name=\"School\"]]</td>\r\n							</tr>\r\n							<tr>\r\n								<th colspan=\'6\' style=\"line-height:29px; padding-left:16px; color:#212121; white-space:nowrap; vertical-align:top; text-align:left; border-top:1px solid #E1E1E1; background-image:url(\'http://[[System Action=\"GetDomain\"\']]/Theme/Default/Default/Email_TableHeaderBackground.png\');\"\'>ISBN</th>\r\n							</tr>\r\n							<tr>\r\n								<td colspan=\'6\' style=\'text-align:left; line-height:29px; padding-left:16px; vertical-align:top; border-top:1px solid #E1E1E1;\'>[[Data Name=\"ISBN\"]]</td>\r\n							</tr>\r\n							<tr>\r\n								<th colspan=\'6\' style=\'line-height:29px; padding-left:16px; color:#212121; white-space:nowrap; vertical-align:top; text-align:left; border-top:1px solid #E1E1E1; background-image:url(\'http://[[System Action=\"GetDomain\"]]/Theme/Default/Default/Email_TableHeaderBackground.png\');\'>Vendors</th>\r\n							</tr>\r\n							<tr>\r\n								<td colspan=\'6\' style=\'text-align:left; line-height:29px; padding-left:16px; vertical-align:top; border-top:1px solid #E1E1E1;\'>[[Data Name=\"Vendors\"]]</td>\r\n							</tr>\r\n							<tr>\r\n								<th colspan=\'6\' style=\'line-height:29px; padding-left:16px; color:#212121; white-space:nowrap; vertical-align:top; text-align:left; border-top:1px solid #E1E1E1; background-image:url(\'http://[[System Action=\"GetDomain\"]]/Theme/Default/Default/Email_TableHeaderBackground.png\');\'>Scope</th>\r\n							</tr>\r\n							<tr>\r\n								<td colspan=\'6\' style=\'text-align:left; line-height:29px; padding-left:16px; vertical-align:top; border-top:1px solid #E1E1E1;\'>[[Data Name=\"Scope\"]]</td>\r\n							</tr>\r\n						</table>\r\n					</td>\r\n				</tr>\r\n			</table>\r\n		</center>\r\n	</body>\r\n</html>',
			'McGraw Hill - AATP', 
			'no-reply@[[System Action=\"GetShortDomain\"]]', 
			'no-reply@[[System Action=\"GetShortDomain\"]]', 
			'Request PO - Project [[Data Name=\"ProjectNumber\"]]');

			INSERT INTO `EmailTemplates` (`Type`, `Name`, `SubName`, `FromName`, `FromEmail`, `ReplyTo`, `Subject`, `Content`) 
VALUES ('Module', 'ProjectsShopOnline', 'Project created', 'McGraw Hill - AATP', 'no-reply@[[System Action="GetShortDomain"]]', 'no-reply@[[System Action="GetShortDomain"]]', 'A new Project Shop Online was created- ISBN-10 [[Data Name="ISBN10"]]',
'<html>
	<body>
		<center>
			<table width="640">
				<tr>
					<td style="padding:20px; padding-top:0px; text-align:center; background-color:black;">
						<img src="http://[[System Action="GetDomain"]]/Theme/Default/Default/Logo.png" alt="The Almighty App For All Things Project" style="margin-bottom:20px;">
						<table width="640" style="background:#ffffff; border:1px solid #E1E1E1;" cellspacing="0">
							<tr>
								<th colspan="2" style="width:33%; line-height:29px; padding-left:16px; color:#212121; white-space:nowrap; vertical-align:top; text-align:left; background-image:url(\'http://[[System Action="GetDomain"]]/Theme/Default/Default/Email_TableHeaderBackground.png\');">ISBN-10</th>
							</tr>
							<tr>
								<td colspan="2" style="text-align:left; line-height:29px; padding-left:16px; vertical-align:top; border-top:1px solid #E1E1E1;">[[Data Name="ISBN10"]]</td>
							</tr>
							<tr>
								<th colspan="2" style="width:33%; line-height:29px; padding-left:16px; color:#212121; white-space:nowrap; vertical-align:top; text-align:left; border-top:1px solid #E1E1E1; background-image:url(\'http://[[System Action="GetDomain"]]/Theme/Default/Default/Email_TableHeaderBackground.png\');">Date needed</th>
							</tr>
							<tr>
								<td colspan="2" style="text-align:left; line-height:29px; padding-left:16px; vertical-align:top; border-top:1px solid #E1E1E1;">[[Data Name="DateNeeded"]]</td>
							</tr>
							<tr>
								<th colspan="6" style="line-height:29px; padding-left:16px; color:#212121; white-space:nowrap; vertical-align:top; text-align:left; border-top:1px solid #E1E1E1; background-image:url(\'http://[[System Action="GetDomain"]]/Theme/Default/Default/Email_TableHeaderBackground.png\');">Type of ISBN</th>
							</tr>
							<tr>
								<td colspan="6" style="text-align:left; line-height:29px; padding-left:16px; vertical-align:top; border-top:1px solid #E1E1E1;">[[Data Name="ISBNType"]]</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</center>
	</body>
</html>');

INSERT INTO `EmailTemplates` (`Type`, `Name`, `SubName`, `FromName`, `FromEmail`, `ReplyTo`, `Subject`, `Content`) 
VALUES ('Module', 'ProjectsShopOnline', 'Project created requester', 'McGraw Hill - AATP', 'no-reply@[[System Action="GetShortDomain"]]', 'no-reply@[[System Action="GetShortDomain"]]', 'You have created a new Project Shop Online - ISBN-10 [[Data Name="ISBN10"]]',
'<html>
	<body>
		<center>
			<table width="640">
				<tr>
					<td style="padding:20px; padding-top:0px; text-align:center; background-color:black;">
						<img src="http://[[System Action="GetDomain"]]/Theme/Default/Default/Logo.png" alt="The Almighty App For All Things Project" style="margin-bottom:20px;">
						<table width="640" style="background:#ffffff; border:1px solid #E1E1E1;" cellspacing="0">
							<tr>
								<th colspan="2" style="width:33%; line-height:29px; padding-left:16px; color:#212121; white-space:nowrap; vertical-align:top; text-align:left; background-image:url(\'http://[[System Action="GetDomain"]]/Theme/Default/Default/Email_TableHeaderBackground.png\');">ISBN-10</th>
							</tr>
							<tr>
								<td colspan="2" style="text-align:left; line-height:29px; padding-left:16px; vertical-align:top; border-top:1px solid #E1E1E1;">[[Data Name="ISBN10"]]</td>
							</tr>
							<tr>
								<th colspan="2" style="width:33%; line-height:29px; padding-left:16px; color:#212121; white-space:nowrap; vertical-align:top; text-align:left; border-top:1px solid #E1E1E1; background-image:url(\'http://[[System Action="GetDomain"]]/Theme/Default/Default/Email_TableHeaderBackground.png\');">Date needed</th>
							</tr>
							<tr>
								<td colspan="2" style="text-align:left; line-height:29px; padding-left:16px; vertical-align:top; border-top:1px solid #E1E1E1;">[[Data Name="DateNeeded"]]</td>
							</tr>
							<tr>
								<th colspan="6" style="line-height:29px; padding-left:16px; color:#212121; white-space:nowrap; vertical-align:top; text-align:left; border-top:1px solid #E1E1E1; background-image:url(\'http://[[System Action="GetDomain"]]/Theme/Default/Default/Email_TableHeaderBackground.png\');">Type of ISBN</th>
							</tr>
							<tr>
								<td colspan="6" style="text-align:left; line-height:29px; padding-left:16px; vertical-align:top; border-top:1px solid #E1E1E1;">[[Data Name="ISBNType"]]</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</center>
	</body>
</html>');

INSERT INTO `EmailTemplates` (`Type`, `Name`, `SubName`, `Content`, `FromName`, `FromEmail`, `ReplyTo`, `Subject`) VALUES ('Module', 'ProjectsShopOnline', 'MilestoneCompleted', '<html>\n	<body>\n		<center>\n			<table width=\'640\'>\n				<tr>\n					<td style=\"padding:20px; padding-top:0px; text-align:center; background-color:black;\">\n						<img src=\'http://[[System Action=\"GetDomain\"]]/Theme/Default/Default/Logo.png\' alt=\'The Almighty App For All Things Project\' style=\'margin-bottom:20px;\'>\n						<table width=\'640\' style=\'background:#ffffff; border:1px solid #E1E1E1;\' cellspacing=\'0\'>\n							<tr>\n								<th colspan=\'2\' style=\"width:33%; line-height:29px; padding-left:16px; color:#212121; white-space:nowrap; vertical-align:top; text-align:left; background-image:url(\'http://[[System Action=\"GetDomain\"]]/Theme/Default/Default/Email_TableHeaderBackground.png\');\">ISBN</th>\n							</tr>\n							<tr>\n								<td colspan=\'2\' style=\'text-align:left; line-height:29px; padding-left:16px; vertical-align:top; border-top:1px solid #E1E1E1;\'>[[Data Name=\"ISBN\"]]</td>\n							</tr>\n							<tr>\n								<th colspan=\'2\' style=\"width:33%; line-height:29px; padding-left:16px; color:#212121; white-space:nowrap; vertical-align:top; text-align:left; background-image:url(\'http://[[System Action=\"GetDomain\"]]/Theme/Default/Default/Email_TableHeaderBackground.png\');\">Shop link</th>\n							</tr>\n							<tr>\n								<td colspan=\'2\' style=\'text-align:left; line-height:29px; padding-left:16px; vertical-align:top; border-top:1px solid #E1E1E1;\'>[[Data Name=\"ShopLink\"]]</td>\n							</tr>\n							<tr>\n								<th colspan=\'6\' style=\"line-height:29px; padding-left:16px; color:#212121; white-space:nowrap; vertical-align:top; text-align:left; border-top:1px solid #E1E1E1; background-image:url(\'http://[[System Action=\"GetDomain\"]]/Theme/Default/Default/Email_TableHeaderBackground.png\');\">Milestone</th>\n							</tr>\n							<tr>\n								<td colspan=\'6\' style=\'text-align:left; line-height:29px; padding-left:16px; vertical-align:top; border-top:1px solid #E1E1E1;\'>[[Data Name=\"Milestone\"]]</td>\n							</tr>\n							<tr>\n								<th colspan=\'6\' style=\"line-height:29px; padding-left:16px; color:#212121; white-space:nowrap; vertical-align:top; text-align:left; border-top:1px solid #E1E1E1; background-image:url(\'http://[[System Action=\"GetDomain\"]]/Theme/Default/Default/Email_TableHeaderBackground.png\');\">Summary</th>\n							</tr>\n							<tr>\n								<td colspan=\'6\' style=\'text-align:left; padding:16px; vertical-align:top; border-top:1px solid #E1E1E1;\'>[[Data Name=\"Summary\" Format=\"nl2br\"]]</td>\n							</tr>\n						</table>\n					</td>\n				</tr>\n			</table>\n		</center>\n	</body>\n</html>', 'McGraw Hill - AATP', 'no-reply@[[System Action=\"GetShortDomain\"]]', 'no-reply@[[System Action=\"GetShortDomain\"]]', 'Milestone complete - Project [[Data Name=\"ISBN\"]]'); 

INSERT INTO `EmailTemplates` (`Type`, `Name`, `SubName`, `Content`, `FromName`, `FromEmail`, `ReplyTo`, `Subject`) VALUES ('Module', 'Projects', 'Assigned Milestone To-Do', '<html>\r\n	<body>\r\n		<center>\r\n			<table width=\'640\'>\r\n				<tr>\r\n					<td style=\"padding:20px; padding-top:0px; text-align:center; background-color:black;\">\r\n						<img src=\'http://[[System Action=\"GetDomain\"]]/Theme/Default/Default/Logo.png\' alt=\'The Almighty App For All Things Project\' style=\'margin-bottom:20px;\'>\r\n						<table width=\'640\' style=\'background:#ffffff; border:1px solid #E1E1E1;\' cellspacing=\'0\'>\r\n							<tr>\r\n								<th colspan=\'2\' style=\"width:33%; line-height:29px; padding-left:16px; color:#212121; white-space:nowrap; vertical-align:top; text-align:left; background-image:url(\'http://[[System Action=\"GetDomain\"]]/Theme/Default/Default/Email_TableHeaderBackground.png\');\">Project #</th>\r\n								<th colspan=\'2\' style=\"width:33%; line-height:29px; padding-left:16px; color:#212121; white-space:nowrap; vertical-align:top; text-align:left; background-image:url(\'http://[[System Action=\"GetDomain\"]]/Theme/Default/Default/Email_TableHeaderBackground.png\');\">Date/Time</th>\r\n								<th colspan=\'2\' style=\"width:33%; line-height:29px; padding-left:16px; color:#212121; white-space:nowrap; vertical-align:top; text-align:left; background-image:url(\'http://[[System Action=\"GetDomain\"]]/Theme/Default/Default/Email_TableHeaderBackground.png\');\">User</th>\r\n							</tr>\r\n							<tr>\r\n								<td colspan=\'2\' style=\'text-align:left; line-height:29px; padding-left:16px; vertical-align:top; border-top:1px solid #E1E1E1;\'>[[Data Name=\"ProjectNumber\"]]</td>\r\n								<td colspan=\'2\' style=\'text-align:left; line-height:29px; padding-left:16px; vertical-align:top; border-top:1px solid #E1E1E1;\'>[[Data Name=\"DateTime\"]]</td>\r\n								<td colspan=\'2\' style=\'text-align:left; line-height:29px; padding-left:16px; vertical-align:top; border-top:1px solid #E1E1E1;\'>[[Data Name=\"User\"]]</td>\r\n							</tr>\r\n							<tr>\r\n								<th colspan=\'6\' style=\"line-height:29px; padding-left:16px; color:#212121; white-space:nowrap; vertical-align:top; text-align:left; border-top:1px solid #E1E1E1; background-image:url(\'http://[[System Action=\"GetDomain\"]]/Theme/Default/Default/Email_TableHeaderBackground.png\');\">Name</th>\r\n							</tr>\r\n							<tr>\r\n								<td colspan=\'6\' style=\'text-align:left; line-height:29px; padding-left:16px; vertical-align:top; border-top:1px solid #E1E1E1;\'>[[Data Name=\"Name\"]]</td>\r\n							</tr>\r\n							<tr>\r\n								<th colspan=\'6\' style=\"line-height:29px; padding-left:16px; color:#212121; white-space:nowrap; vertical-align:top; text-align:left; border-top:1px solid #E1E1E1; background-image:url(\'http://[[System Action=\"GetDomain\"]]/Theme/Default/Default/Email_TableHeaderBackground.png\');\">Comment</th>\r\n							</tr>\r\n							<tr>\r\n								<td colspan=\'6\' style=\'text-align:left; padding:16px; vertical-align:top; border-top:1px solid #E1E1E1;\'>[[Data Name=\"Comment\" Format=\"nl2br\"]]</td>\r\n							</tr>\r\n							<tr>\r\n								<th colspan=\'6\' style=\"line-height:29px; padding-left:16px; color:#212121; white-space:nowrap; vertical-align:top; text-align:left; border-top:1px solid #E1E1E1; background-image:url(\'http://[[System Action=\"GetDomain\"]]/Theme/Default/Default/Email_TableHeaderBackground.png\');\">Complete</th>\r\n							</tr>\r\n							<tr>\r\n								<td colspan=\'6\' style=\'text-align:left; line-height:29px; padding-left:16px; vertical-align:top; border-top:1px solid #E1E1E1;\'>[[Data Name=\"Complete\"]]</td>\r\n							</tr>\r\n						</table>\r\n					</td>\r\n				</tr>\r\n			</table>\r\n		</center>\r\n	</body>\r\n</html>', 'McGraw Hill - AATP', 'no-reply@[[System Action=\"GetShortDomain\"]]', 'no-reply@[[System Action=\"GetShortDomain\"]]', 'You have been assigned to a Milestone To-Do - Project [[Data Name=\"ProjectNumber\"]]');

/* *************************** */
/*  INSERT DEFAULT MILESTONES  */
/* *************************** */
 
INSERT INTO `Milestones`
(`Name`,`CustomerApproval`,`Summary`,`ExpectedDeliveryDate`,`ActualDeliveryDate`,`PlantAllocated`,
`Status`,`Active`,`CreatedUsersID`,`CreatedIPAddress`)
VALUES ('Cover Loaded', 0, 'Cover Loaded in MPD', 0, 0, 0, 'Active', 1, 1, 'localhost');

INSERT INTO `Milestones`
(`Name`,`CustomerApproval`,`Summary`,`ExpectedDeliveryDate`,`ActualDeliveryDate`,`PlantAllocated`,
`Status`,`Active`,`CreatedUsersID`,`CreatedIPAddress`)
VALUES ('Flags Flipped', 0, 'Flags Flipped in MPD', 0, 0, 0, 'Active', 1, 1, 'localhost');

INSERT INTO `Milestones`
(`Name`,`CustomerApproval`,`Summary`,`ExpectedDeliveryDate`,`ActualDeliveryDate`,`PlantAllocated`,
`Status`,`Active`,`CreatedUsersID`,`CreatedIPAddress`)
VALUES ('Email notification Shop', 0, 'Email notification sent to requestor Shop', 0, 0, 0, 'Active', 1, 1, 'localhost');

INSERT INTO `Milestones`
(`Name`,`CustomerApproval`,`Summary`,`ExpectedDeliveryDate`,`ActualDeliveryDate`,`PlantAllocated`,
`Status`,`Active`,`CreatedUsersID`,`CreatedIPAddress`)
VALUES ('Private Offer setup using the tool', 0, 'Private Offer setup using the tool', 0, 0, 0, 'Active', 1, 1, 'localhost');

INSERT INTO `Milestones`
(`Name`,`CustomerApproval`,`Summary`,`ExpectedDeliveryDate`,`ActualDeliveryDate`,`PlantAllocated`,
`Status`,`Active`,`CreatedUsersID`,`CreatedIPAddress`)
VALUES ('Screenshot of solution taken', 0, 'Screenshot of solution taken', 0, 0, 0, 'Active', 1, 1, 'localhost');

INSERT INTO `Milestones`
(`Name`,`CustomerApproval`,`Summary`,`ExpectedDeliveryDate`,`ActualDeliveryDate`,`PlantAllocated`,
`Status`,`Active`,`CreatedUsersID`,`CreatedIPAddress`)
VALUES ('Email notification Private', 0, 'Email notification sent to requestor Private', 0, 0, 0, 'Active', 1, 1, 'localhost');
