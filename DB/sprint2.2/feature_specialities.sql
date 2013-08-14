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