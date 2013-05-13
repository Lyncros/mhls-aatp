<?
	$RecordID = $_POST["RecordID"];
	
	$Institution = new CInstitutions();
	$Institution->OnLoad($RecordID);	
?>
<table class="CForm_Table">
<?
	echo CForm::AddStatic("Name", $Institution->Name);
	echo CForm::AddStatic("Address 1", $Institution->Address1);
	echo CForm::AddStatic("Address 2", $Institution->Address2);
	echo CForm::AddStatic("City", $Institution->City);
	echo CForm::AddStatic("State", $Institution->State);
	echo CForm::AddStatic("Zip", $Institution->Zip);						
?>
</table>
