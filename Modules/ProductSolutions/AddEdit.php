<?
	$WindowID = $_POST["CWindow_ID"];

	$TableObject = $this->Parent->TableObject;

	CForm::RandomPrefix();

	if($TableObject) {
		CWindow::SetTitle($WindowID, "Edit Product Solution : ".$TableObject->Name);
	}
?>

<table class="CForm_Table">
<?
	$InstList = Array(0 => "None");
	
	$Inst = new CInstitutions();
	if($Inst->OnLoadAll("WHERE `Active` = 1 ORDER BY `Name` ASC") !== false) {
		$InstList += CForm::RowsToArray($Inst->Rows, "Name");
	}

	echo CForm::AddDropdown("Institution", "InstitutionsID", $InstList, @$TableObject->InstitutionsID);	

	echo CForm::AddTextbox("Name", "Name", @$TableObject->Name, "Please enter a Name.");
	echo CForm::AddTextbox("Description", "Description", @$TableObject->Description);	
	echo CForm::AddTextbox("Price", "Price", @$TableObject->Price);					
	
	echo CForm::AddYesNo("Public", "Public", $TableObject->Public);
	echo CForm::AddYesNo("Active", "Active", ($TableObject ? $TableObject->Active : 1));	
?>
</table>

<?
	echo CForm::AddHidden("ID", $TableObject->ID);
?>
<center><input type="button" class="CWindow_Save" onClick="MProductSolutions.AddEdit(<?=$WindowID;?>, '<?=CForm::GetPrefix();?>');" value="Save"/></center>

