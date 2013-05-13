<?
	$RecordID = $_POST["RecordID"];
	
	$User = new CUsers();
	$User->OnLoad($RecordID);	
?>
<table class="CForm_Table">
<?
	echo CForm::AddStatic("Title", $User->Title);	
	echo CForm::AddStatic("First Name", $User->FirstName);
	echo CForm::AddStatic("Middle Initial", $User->MiddleInitial);
	echo CForm::AddStatic("Last Name", $User->LastName);
	echo CForm::AddStatic("Address 1", $User->Address1);
	echo CForm::AddStatic("Address 2", $User->Address2);
	echo CForm::AddStatic("City", $User->City);
	echo CForm::AddStatic("State", $User->State);
	echo CForm::AddStatic("Zip", $User->Zip);
	echo CForm::AddStatic("Campus", $User->Campus);		
	echo CForm::AddStatic("Office Phone", $User->OfficePhone);
	echo CForm::AddStatic("Mobile Phone", $User->MobilePhone);		
	echo CForm::AddStatic("Email", $User->Email);							
?>
</table>
