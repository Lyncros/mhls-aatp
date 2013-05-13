<?
	$RecordID = $_POST["RecordID"];
	
	$Project = new CProjects();
	$Project->OnLoad($RecordID);
	$LastModified = $Project->Modified;
	$Description = "Project Details updated";
	$User = new CUsers();
	$User->OnLoad($Project->ModifiedUsersID);
	
	$ProjectMessages = new CProjectsMessages();
	$ProjectMessages->OnLoadAll("WHERE `ProjectsID` = $RecordID ORDER BY `Created` DESC LIMIT 1");
	if($ProjectMessages->Created > $LastModified) {
		$LastModified = $ProjectMessages->Created;
		$Description = "Message added";
		$User->OnLoad($ProjectMessages->CreatedUsersID);
	}
	
	$ProjectResources = new CProjectsResources();
	$ProjectResources->OnLoadAll("WHERE `ProjectsID` = $RecordID ORDER BY `Created` DESC LIMIT 1");
	if($ProjectResources->Created > $LastModified) {
		$LastModified = $ProjectResources->Created;
		$Description = "Resource added";
		$User->OnLoad($ProjectResources->CreatedUsersID);
	}
	
	$ProjectMilestones = new CProjectsMilestones();
	$ProjectMilestones->OnLoadAll("WHERE `ProjectsID` = $RecordID");
	foreach($ProjectMilestones->Rows as $Row) {
		if($Row->Created > $LastModified) {
			$LastModified = $Row->Created;
			$Description = "Milestone added";
			$User->OnLoad($Row->CreatedUsersID);
		}
		
		$ProjectMilestoneChanges = new CTable("ProjectsMilestonesChanges");
		$ProjectMilestoneChanges->OnLoadAll("WHERE `ProjectsMilestonesID` = ".$Row->ID." ORDER BY `Timestamp` DESC LIMIT 1");
		foreach($ProjectMilestoneChanges->Rows as $MilestoneChange) {
			if($MilestoneChange->Timestamp > $LastModified) {
				$LastModified = $MilestoneChange->Timestamp;
				$Description = "Milestone updated";
				$User->OnLoad($MilestoneChange->UsersID);
			}
		}
		
		$ProjectMilestoneToDos = new CProjectsMilestonesToDos();
		$ProjectMilestoneToDos->OnLoadAll("WHERE `MilestoneID` = ".$Row->ID);
		foreach($ProjectMilestoneToDos->Rows as $ToDo) {
			if($ToDo->Created > $LastModified) {
				$LastModified = $ToDo->Created;
				$Description = "Milestone To-Do added";
				$User->OnLoad($Todo->CreatedUsersID);
			}
			
			$ProjectMilestoneToDosChanges = new CTable("ProjectsMilestonesToDosChanges");
			$ProjectMilestoneToDosChanges->OnLoadAll("WHERE `ToDoID` = ".$ToDo->ID." ORDER BY `Timestamp` DESC LIMIT 1");
			foreach($ProjectMilestoneToDosChanges->Rows as $ToDoChange) {
				if($ToDoChange->Timestamp > $LastModified) {
					$LastModified = $ToDoChange->Timestamp;
					$Description = "Milestone To-Do updated";
					$User->OnLoad($ToDoChange->UsersID);
				}
			}
		}
	}
	

?>
<table class="CForm_Table">
<?
	
	echo CForm::AddRow("<strong>" . $Description . "</strong><br><strong style='color:#d74c4c; font-style:italic;'>" . $User->LastName . ", " . $User->FirstName . "</strong>");
	
?>
</table>
