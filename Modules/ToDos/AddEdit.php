<?
	$WindowID = $_POST["CWindow_ID"];

	$TableObject = $this->Parent->TableObject;

	CForm::RandomPrefix();

	if($TableObject) {
		CWindow::SetTitle($WindowID, "Edit To-Do : ".$TableObject->Name);
	}
?>

<table class="CForm_Table">
<?
	echo CForm::AddTextbox("Name", "Name", @$TableObject->Name, "Please enter a Name.");
	//echo CForm::AddYesNo("Complete", "Complete", $TableObject->Complete);
	echo CForm::AddTextarea("Comment", "Comment", @$TableObject->Comment);
	echo CForm::AddYesNo("Comment Required", "CommentRequired", $TableObject->CommentRequired);
	//echo CForm::AddDate("Expected Delivery Date", "ExpectedDeliveryDate", @$TableObject->ExpectedDeliveryDate);
	//echo CForm::AddDate("Actual Delivery Date", "ActualDeliveryDate", @$TableObject->ActualDeliveryDate);
	//echo CForm::AddTextbox("Plant Allocated", "PlantAllocated", @$TableObject->PlantAllocated);
	//echo CForm::AddDropdown("Status", "Status", @$TableObject->Status);
	echo CForm::AddYesNo("Active", "Active", @$TableObject->Active);
	
?>
</table>

<?
	echo CForm::AddHidden("ID", $TableObject->ID);
?>
<center><input type="button" class="CWindow_Save" onClick="MToDos.AddEdit(<?=$WindowID;?>, '<?=CForm::GetPrefix();?>');" value="Save"/></center>

