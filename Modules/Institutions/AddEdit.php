<?
	$WindowID = $_POST["CWindow_ID"];

	$TableObject = $this->Parent->TableObject;

	CForm::RandomPrefix();

	if($TableObject) {
		CWindow::SetTitle($WindowID, "Edit Institution : ".$TableObject->Name);
	}
?>

<table class="CForm_Table">
<?
	echo CForm::AddTextbox("Name", "Name", @$TableObject->Name, "Please enter a Name.");
	echo CForm::AddTextbox("Address 1", "Address1", @$TableObject->Address1);	
	echo CForm::AddTextbox("Address 2", "Address2", @$TableObject->Address2);	
	echo CForm::AddTextbox("City", "City", @$TableObject->City);	
	echo CForm::AddTextbox("State", "State", @$TableObject->State);	
	echo CForm::AddTextbox("Zip", "Zip", @$TableObject->Zip);					
	
	echo CForm::AddYesNo("Active", "Active", $TableObject->Active);
?>
</table>

<?
	echo CForm::AddHidden("ID", $TableObject->ID);
?>
<center><input type="button" class="CWindow_Save" onClick="MInstitutions.AddEdit(<?=$WindowID;?>, '<?=CForm::GetPrefix();?>');" value="Save"/></center>

