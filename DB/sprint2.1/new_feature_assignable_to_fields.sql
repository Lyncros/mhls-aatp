ALTER TABLE `UsersGroups` 
ADD COLUMN `AssignableToMilestoneTODO` TINYINT(1) NULL DEFAULT 0  AFTER `AssignableToTODO` , 
ADD COLUMN `AssignableToMilestone` TINYINT(1) NULL DEFAULT 0  AFTER `AssignableToMilestoneTODO` , 
CHANGE COLUMN `AssignableToTODO` `AssignableToTODO` TINYINT(1) NULL DEFAULT 0  ;

UPDATE `UsersGroups`
SET `AssignableToTODO` = 1,
`AssignableToMilestoneTODO` = 1,
`AssignableToMilestone` = 1
WHERE `Name` IN ('Creative Contact', 'Creative Consultant', 'Junior Creative Analyst', 'Creative Analyst', 'Product Manager');


