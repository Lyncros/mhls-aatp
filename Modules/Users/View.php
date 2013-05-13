<?
	$User = $this->Parent->TableObject;
?>
<h1>User Information</h1>

<table width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td width="50%" valign="top">

	<table class="CForm_Table TableFormGroup">
	<?
		echo CForm::AddTextbox("Title", "Title", $User->Title);	
		echo CForm::AddTextbox("First Name", "FirstName", $User->FirstName, "Please enter a First Name.");
		echo CForm::AddTextbox("Middle Initial", "MiddleInitial", $User->MiddleInitial);
		echo CForm::AddTextbox("Last Name", "LastName", $User->LastName, "Please enter a Last Name.");
		echo CForm::AddTextbox("Address 1", "Address1", $User->Address1);
		echo CForm::AddTextbox("Address 2", "Address2", $User->Address2);
		echo CForm::AddTextbox("City", "City", $User->City);
		echo CForm::AddTextbox("State", "State", $User->State);
		echo CForm::AddTextbox("Zip", "Zip", $User->Zip);
		echo CForm::AddTextbox("Campus", "Campus", $User->Campus);		
		echo CForm::AddTextbox("Office Phone", "OfficePhone", $User->OfficePhone);
		echo CForm::AddTextbox("Mobile Phone", "MobilePhone", $User->MobilePhone);		
		echo CForm::AddTextbox("Email", "Email", $User->Email);
	?>
	</table>

	</td>
	<td><div style="width: 20px"></div></td>
	<td width="50%" valign="top">

	<table class="CForm_Table TableFormGroup">
	<?
		$Groups = new CUsersGroups();
		if($Groups->OnLoadAll("WHERE `Active` = 1 ORDER BY `Name` ASC") === false) $Groups = null;
	
		echo CForm::AddDropdown("Group", "UsersGroupsID", Array(0 => "None") + ($Groups ? CForm::RowsToArray($Groups->Rows, "Name") : Array()), $User->UsersGroupsID);	
	
		$TypeList = Array(
			"User"  		=> "User",
			"Institution"	=> "Institution"
		);
		
		echo CForm::AddDropdown("Type", "Type", $TypeList, $User->Type);
		
		$Inst = new CInstitutions();
		if($Inst->OnLoadAll("WHERE `Active` = 1 ORDER BY `Name` ASC") === false) $Inst = null;
	
		echo CForm::AddDropdown("Institution", "InstitutionsID", Array("0" => "None") + ($Inst ? CForm::RowsToArray($Inst->Rows, "Name") : Array()), $User->InstitutionsID);
		
		echo CForm::AddYesNo("Active", "Active", $User->Active);
		
		echo CForm::AddRow("<br/><br/>");

		echo CForm::AddTextbox("Username", "Username", $User->Username, "Please enter a Username.");
		echo CForm::AddPassword("Reset Password", "Password1", "");
		echo CForm::AddPassword("Confirm Password", "Password2", "");
	?>
	</table>


	</td>
</tr>
<tr>
	<td colspan="3" align="right">
	
	<input type="hidden" value="<?=intval($User->ID);?>" name="ID" id="ID"/>
	<div class="SaveButton" value="Save" onClick="MUsers.Save('', this);"></div>
	
	</td>
</tr>
</table>
