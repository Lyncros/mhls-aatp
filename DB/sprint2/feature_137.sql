INSERT INTO `aatp`.`UsersGroups` 
(`Timestamp`, `TimestampUpdated`, `Type`, `Name`, `Active`, `SuperAdmin`, `AssignableToTODO`) 
VALUES (1343325350, 1343325350, 'Normal', 'Creative Contact', 1, 0, 1);

SELECT @CreativeContact:=`ID` FROM `UsersGroups` WHERE `Name` = 'Creative Contact';

INSERT INTO `aatp`.`UsersGroupsPermissions` (`UsersGroupsID`,`Type`,`Name`,`Access`,`Action`)
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

INSERT INTO `aatp`.`ProjectsCreativeContacts` (`ProjectsID`, `UsersID`)
SELECT PCA.`ProjectsID`, PCA.`UsersID`
FROM `aatp`.`ProjectsCreativeAnalysts` AS PCA;

INSERT INTO `aatp`.`ProjectsCreativeContacts` (`ProjectsID`, `UsersID`)
SELECT PCC.`ProjectsID`, PCC.`UsersID`
FROM `aatp`.`ProjectsCreativeConsultants` AS PCC;

INSERT INTO `aatp`.`ProjectsCreativeContacts` (`ProjectsID`, `UsersID`)
SELECT PJCA.`ProjectsID`, PJCA.`UsersID`
FROM `aatp`.`ProjectsJuniorCreativeAnalysts` AS PJCA;


DROP TABLE `aatp`.`ProjectsCreativeAnalysts`;
DROP TABLE `aatp`.`ProjectsCreativeConsultants`;
DROP TABLE `aatp`.`ProjectsJuniorCreativeAnalysts`;

SELECT @CreativeContact:=`ID` FROM `UsersGroups` WHERE `Name` = 'Creative Contact';

UPDATE `Users`
SET `Users`.`UsersGroupsID` = @CreativeContact
WHERE `Users`.`UsersGroupsID` IN (
    SELECT `ID` FROM `aatp`.`UsersGroups` 
    WHERE `Name` IN ('Creative Consultant', 'Junior Creative Analyst', 'Creative Analyst'));


