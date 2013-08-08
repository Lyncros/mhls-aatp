CREATE VIEW ProjectsView AS 
	SELECT `Projects`.*, GROUP_CONCAT(DISTINCT `Users`.`LastName` ORDER BY `Users`.`LastName` ASC SEPARATOR ', ') AS LSCs
	FROM `Projects`
	LEFT JOIN `ProjectsLSCs` ON `Projects`.`ID` = `ProjectsLSCs`.`ProjectsID`
	LEFT JOIN `Users` ON `Users`.`ID` = `ProjectsLSCs`.`UsersID`
	GROUP BY `Projects`.`ID` 
	ORDER BY `Projects`.`ID`;