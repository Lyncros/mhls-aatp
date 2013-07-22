CREATE TABLE `Vendors` (
	`ID` INT(10) NOT NULL AUTO_INCREMENT,
	`Name` VARCHAR(50) NOT NULL,
	PRIMARY KEY (`ID`),
	INDEX `ID` (`ID`)
)

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
)

CREATE TABLE `ProjectsProductSolutions` (
	`ID` INT(10) NOT NULL AUTO_INCREMENT,
	`ProjectsID` INT(10) NOT NULL DEFAULT '0',
	`ProductSolutionsID` INT(10) NOT NULL DEFAULT '0',
	PRIMARY KEY (`ID`),
	INDEX `ID` (`ID`)
)
