<?
	$WindowID = $_POST["CWindow_ID"];

	$TableObject = $this->Parent->TableObject;

	CForm::RandomPrefix();

	if($TableObject) {
		CWindow::SetTitle($WindowID, "Edit To-Do List: ".$TableObject->Name);
	}
?>

<table class="CForm_Table">
<?
	$ToDosArray = Array();
	$ToDos = new CToDos();
	if($ToDos->OnLoadAll("ORDER BY `Name`") !== false) {
		foreach($ToDos->Rows as $ToDo) {
			$ToDosArray[$ToDo->ID] = $ToDo->Name;
		}
	}

	echo CForm::AddTextbox("Name", "Name", @$TableObject->Name, "Please enter a Name.");
	echo CForm::AddListbox("List Members", "Members", $ToDosArray, unserialize(@$TableObject->Members));
		
?>
</table>

<?
	echo CForm::AddHidden("ID", $TableObject->ID);
?>
<center><input type="button" class="CWindow_Save" onClick="MToDosLists.AddEdit(<?=$WindowID;?>, '<?=CForm::GetPrefix();?>');" value="Save"/></center>

