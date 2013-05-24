<?
	$WindowID = $_POST["CWindow_ID"];

	$TableObject = $this->Parent->TableObject;

	CForm::RandomPrefix();

	if($TableObject) {
		CWindow::SetTitle($WindowID, "Edit Product Type : ".$TableObject->Name);
	}
?>

<table class="CForm_Table">
<?
	echo CForm::AddTextbox("Name", "Name", @$TableObject->Name, "Please enter a Name.");
	echo CForm::AddYesNo("Active", "Active", $TableObject->Active);
	$ToDosListsArray = Array();
	$ToDosLists = new CToDosLists();
	$MilestonesArray = Array();
	$Milestones = new CMilestones();
	if($Milestones->OnLoadAll("ORDER BY `Name`") !== false) {
		foreach($Milestones->Rows as $Milestone) {
			$MilestonesArray[$Milestone->ID] = $Milestone->Name;
		}
	}
	echo CForm::AddListbox("Milestones", "Milestones", $MilestonesArray, unserialize(@$TableObject->Milestones));
	if($ToDosLists->OnLoadAll("ORDER BY `Name`") !== false) {
		foreach($ToDosLists->Rows as $ToDosList) {
			$ToDosListsArray[$ToDosList->ID] = $ToDosList->Name;
		}
	}
	echo CForm::AddListbox("ToDos lists", "ToDosLists", $ToDosListsArray, unserialize(@$TableObject->ToDosLists));	
?>
</table>

<?
	echo CForm::AddHidden("ID", $TableObject->ID);
?>
<center><input type="button" class="CWindow_Save" onClick="MProductTypes.AddEdit(<?=$WindowID;?>, '<?=CForm::GetPrefix();?>');" value="Save"/></center>

