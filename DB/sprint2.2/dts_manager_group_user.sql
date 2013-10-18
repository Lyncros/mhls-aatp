INSERT INTO `UsersGroups`
(`Timestamp`, `TimestampUpdated`, `Type`, `Name`, `Active`, `SuperAdmin`, 
`AssignableToTODO`, `AssignableToMilestoneTODO`,`AssignableToMilestone`)
VALUES
(1343325350, 1343325350, 'Normal', 'DTS Manager', '1', '0', '1', '1', '1');

SELECT @DTSManagerID := `ID` FROM `UsersGroups` WHERE `Name` = 'DTS Manager';

INSERT INTO `Users`
(`InstitutionsID`, `UsersGroupsID`, `Timestamp`, `TimestampUpdated`, `TimestampImported`, `Type`,
`Username`, `Password`, `FirstName`, `MiddleInitial`, `LastName`, `Title`, `Address1`, `Address2`, `City`,
`State`, `Zip`, `Campus`, `OfficePhone`, `MobilePhone`, `Fax`, `Email`, `MaxView`, `ResetTimestamp`,
`ResetPassCode`, `NotificationsMessages`, `NotificationsResources`, `Active`)
VALUES
(0, @DTSManagerID, 1343325350, 1343325350, 0, 'User', 
'jonathon_mullens', '', 'Jonathon', 'S', 'Mullens', '', '', '', '', 
'', '', '', '', '', '', 'jonathon_mullens@mcgraw-hill.com', 0, 0, 
'', 0, 0, 1);

