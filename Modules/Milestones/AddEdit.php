<?
	$WindowID = $_POST["CWindow_ID"];

	$TableObject = $this->Parent->TableObject;

	CForm::RandomPrefix();

	if($TableObject) {
		CWindow::SetTitle($WindowID, "Edit Default Milestone : ".$TableObject->Name);
	}
?>

<table class="CForm_Table">
<?
	echo CForm::AddTextbox("Name", "Name", @$TableObject->Name, "Please enter a Name.");
	echo CForm::AddYesNo("Customer Approval", "CustomerApproval", $TableObject->CustomerApproval);
	echo CForm::AddTextarea("Summary", "Summary", @$TableObject->Summary);
	//echo CForm::AddDate("Expected Delivery Date", "ExpectedDeliveryDate", @$TableObject->ExpectedDeliveryDate);
	//echo CForm::AddDate("Actual Delivery Date", "ActualDeliveryDate", @$TableObject->ActualDeliveryDate);
	echo CForm::AddTextbox("Plant Allocated", "PlantAllocated", @$TableObject->PlantAllocated);
	//echo CForm::AddDropdown("Status", "Status", @$TableObject->Status);
	echo CForm::AddYesNo("Active", "Active", @$TableObject->Active);
	
?>
</table>

<?
	echo CForm::AddHidden("ID", $TableObject->ID);
?>
<center><input type="button" class="CWindow_Save" onClick="MMilestones.AddEdit(<?=$WindowID;?>, '<?=CForm::GetPrefix();?>');" value="Save"/></center>

