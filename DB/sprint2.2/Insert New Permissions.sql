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