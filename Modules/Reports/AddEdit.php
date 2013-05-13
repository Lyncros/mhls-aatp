<?
	$WindowID = $_POST["CWindow_ID"];

	$TableObject = $this->Parent->TableObject;

	if($TableObject) {
		CWindow::SetTitle($WindowID, "Edit Report : ".$TableObject->Name);
	}

	CForm::RandomPrefix();
?>
<table class="CForm_Table">
<?
	echo CForm::AddDropdown("Type", "Type", CReports::GetTypeList(), @$TableObject->Type);
	echo CForm::AddTextbox("Name", "Name", @$TableObject->Name, "Please enter a Name");
	echo CForm::AddYesNo("Public", "Public", @$TableObject->Public);
?>
</table>
<br/>
<?
	echo CForm::AddHidden("ID", $TableObject->ID);
?>
<center><input type="button" onClick="MReports.AddEdit(<?=$WindowID;?>, '<?=CForm::GetPrefix();?>');" value="Save"/></center>
