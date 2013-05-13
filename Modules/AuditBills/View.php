<?
	$AuditBill = $this->Parent->TableObject;
	
	$ID = intval($_GET["ID"]);
	
	CForm::SetPrefix("");
?>
<h1><?=($ID > 0 ? "Edit" : "New");?> Audit Bill</h1>

<table width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td width="50%" valign="top">
	
	<table class="CForm_Table TableFormGroup" style="margin-bottom: 20px;">
	<tr>
		<td colspan="2"><h2>Primary Information</h2></td>
	</tr>
	<?
		if($AuditBill) {
			echo CForm::AddDropdown("Status", "Status", CAuditBills::GetStatusList(), $AuditBill->Status);			
		}
	
		$Freq = new CFrequencies();
		$Freq->OnLoadAll("ORDER BY `Order` ASC");
		
		$StartDate = date("Y-m-d");
		if($ID > 0) $StartDate = $AuditBill->StartDate;
		
		$EndDate = "";
		if($ID > 0) $EndDate = $AuditBill->EndDate;		
	
		echo CForm::AddTextbox("Inst. Account Number", "Number", $AuditBill->Number, "Please enter an Audit Bill Number");
		echo CForm::AddTextbox("PO Number", "PONumber", $AuditBill->PONumber);		
		echo CForm::AddDatePicker("Start Date", "StartDate", $StartDate);
		echo CForm::AddDatePicker("End Date", "EndDate", $EndDate);		
		echo CForm::AddDropdown("Term Frequency", "Frequency", CForm::RowsToArray($Freq->Rows, "Frequency", "Frequency"), $AuditBill->Frequency);
	?>
	</table>

	<table class="CForm_Table TableFormGroup" style="margin-bottom: 20px;">
	<tr>
		<td colspan="2"><h2>Assigned Users</h2></td>
	</tr>
	<?
		$ABManagerID = 0;
		$APContactID = 0;
		$LSCID		 = 0;
		
		if($ABManagerID == 0) $ABManagerID = CSecurity::GetUsersID();
		
		if($ID > 0) {
			$Managers = $AuditBill->GetABManagers();
			foreach($Managers as $Manager) {
				$ABManagerID = $Manager->ID;
			}
			
			$Contacts = $AuditBill->GetAPContacts();
			foreach($Contacts as $Contact) {
				$APContactID = $Contact->ID;
			}
			
			$LSCs = $AuditBill->GetLSCs();
			foreach($LSCs as $LSC) {
				$LSCID = $LSC->ID;
			}			
		}	
		
		$UserList = Array();
		
		$Group = new CUsersGroups();
		if($Group->OnLoadAll("WHERE `Active` = 1 && `Name` = 'AB Manager'") !== false) {
			$Users = new CUsers();
			if($Users->OnLoadAll("WHERE `Active` = 1 && `UsersGroupsID` = ".$Group->ID." ORDER BY `LastName`, `FirstName` ASC") !== false) {
				foreach($Users->Rows as $Row) {
					$UserList[$Row->ID] = $Row->LastName.", ".$Row->FirstName;
				}
			}
		}
	
		echo CForm::AddDropdown("Finance Director", "ABManagerID", $UserList, $ABManagerID);
		
		$UserList = Array();
		
		$Group = new CUsersGroups();
		if($Group->OnLoadAll("WHERE `Active` = 1 && `Name` = 'AP Contact'") !== false) {
			$Users = new CUsers();
			if($Users->OnLoadAll("WHERE `Active` = 1 && `UsersGroupsID` = ".$Group->ID." ORDER BY `LastName`, `FirstName` ASC") !== false) {
				foreach($Users->Rows as $Row) {
					$UserList[$Row->ID] = $Row->LastName.", ".$Row->FirstName;
				}
			}
		}		
		
		echo CForm::AddDropdown("AP Contact", "APContactID", $UserList, $APContactID);		
		
		$UserList = Array();
		
		$Group = new CUsersGroups();
		if($Group->OnLoadAll("WHERE `Active` = 1 && `Name` = 'LSC'") !== false) {
			$Users = new CUsers();
			if($Users->OnLoadAll("WHERE `Active` = 1 && `UsersGroupsID` = ".$Group->ID." ORDER BY `LastName`, `FirstName` ASC") !== false) {
				foreach($Users->Rows as $Row) {
					$UserList[$Row->ID] = $Row->LastName.", ".$Row->FirstName;
				}
			}
		}		
		
		echo CForm::AddDropdown("LSC Contact", "LSCID", $UserList, $LSCID);			
	?>	
	<!-- <tr>
		<td style="border: none">
		<select id="AddUserID">
		<?
			$Users = new CUsers();
			if($Users->OnLoadAll("WHERE `Active` = 1 ORDER BY `LastName`, `FirstName`") !== false) {
				foreach($Users->Rows as $Row) {
					echo "<option value='".$Row->ID."'>".$Row->LastName.", ".$Row->FirstName."</option>";
				}
			}
		?>		
		</select>
		<input type="button" id="AddUserButton" value="Add"/>
		</td>
	</tr>
	<tr>
		<td style="border: none"><div class="AssignedUserList"></div></td>
	</tr> -->
	</table>	

	<table class="CForm_Table TableFormGroup" style="margin-bottom: 20px;">
	<tr>
		<td colspan="2"><h2>Institution Audit Bill Details</h2></td>
	</tr>	
	<?
		echo CForm::AddTextbox("Author", "Author", $AuditBill->Author);
	
		echo CForm::AddTextbox("Course", "Course", $AuditBill->Course);
		echo CForm::AddTextbox("Course Number", "CourseNumber", $AuditBill->CourseNumber);
		echo CForm::AddStatic("<br/>ISBNs", "<br/><div class='ISBNList' style='margin-bottom: 20px;'></div><div onClick=\"MAuditBills.AddISBN('');\" class='ButtonAdd' style='margin-bottom: 20px; display: inline-block; position: static;'>Add ISBN</div>");
		echo CForm::AddTextbox("Quantity", "Quantity", $AuditBill->Quantity);
		echo CForm::AddTextbox("Student Enrollment", "StudentEnrollment", $AuditBill->StudentEnrollment);
	?>
	</table>

	<table class="CForm_Table TableFormGroup">
	<tr>
		<td colspan="2"><h2>Product Solutions</h2></td>
	</tr>	
	<tr>
		<td colspan="2">
		<div class="ProductSolutionsList" style="margin-bottom: 20px;"></div>
		
		<div onClick="MAuditBills.AddProductSolution(0);" class='ButtonAdd' style='margin-bottom: 20px; display: inline-block; position: static;'>Add Product Solution</div>
		</td>
	</tr>
	</table>

	</td>
	<td><div style="width: 20px"></div></td>
	<td width="50%" valign="top">

	<table class="CForm_Table TableFormGroup" id="InstitutionInputs" style="margin-bottom: 20px;">
	<tr>
		<td colspan="2"><h2>Institution</h2></td>
	</tr>
	<?
		$Inst = new CInstitutions();
		if($Inst->OnLoadAll("WHERE `Active` = 1 ORDER BY `Name` ASC") === false) $Inst = null;
	
		echo CForm::AddDropdown("Institution", "InstitutionsID", Array("0" => "Add New") + ($Inst ? CForm::RowsToArray($Inst->Rows, "Name") : Array()), $AuditBill->InstitutionsID);
	
		echo CForm::AddTextbox("Name", "InstitutionName", "");
		echo CForm::AddTextbox("Address", "InstitutionAddress1", "");
		echo CForm::AddTextbox("Address", "InstitutionAddress2", "");
		echo CForm::AddTextbox("City", "InstitutionCity", "");
		echo CForm::AddTextbox("State", "InstitutionState", "");
		echo CForm::AddTextbox("Zip", "InstitutionZip", "");
	?>
	</table>

	<table class="CForm_Table TableFormGroup">
	<tr>
		<td colspan="2"><h2>Institution Contact</h2></td>
	</tr>	
	<?
		$ContactList = Array();
		
		$Users = new CUsers();
		if($Users->OnLoadAll("WHERE `Active` = 1 && `Type` = 'Institution' && `InstitutionsID` = ".intval($AuditBill->InstitutionsID)) !== false) {
			foreach($Users->Rows as $Row) {
				$ContactList[$Row->ID] = $Users->GetName();
			}		
		}
	
		echo CForm::AddDropdown("Contact", "InstitutionsUsersID", Array(0 => "Add New") + $ContactList, $AuditBill->InstitutionsUsersID);		
	
		echo CForm::AddTextbox("Title", "InstitutionContactTitle", "");			
		echo CForm::AddTextbox("First Name", "InstitutionContactFirstName", "");
		echo CForm::AddTextbox("Last Name", "InstitutionContactLastName", "");		
		echo CForm::AddTextbox("Address", "InstitutionContactAddress1", "");
		echo CForm::AddTextbox("Address", "InstitutionContactAddress2", "");
		echo CForm::AddTextbox("City", "InstitutionContactCity", "");
		echo CForm::AddTextbox("State", "InstitutionContactState", "");
		echo CForm::AddTextbox("Zip", "InstitutionContactZip", "");
		
		echo CForm::AddTextbox("Campus", "InstitutionContactCampus", "");		
		
		echo CForm::AddTextbox("Office Phone", "InstitutionContactOfficePhone", "");
		echo CForm::AddTextbox("Mobile Phone", "InstitutionContactMobilePhone", "");
		
		echo CForm::AddTextbox("Email", "InstitutionContactEmail", "");		
	?>
	</table>

	</td>
</tr>
<tr>
	<td style="border-top: none;">
	<?
		if($ID > 0) {
	?>
	<div class="SaveButton"></div>
	<?
		}
	?>
	</td>
	<td colspan="2" style="border-top: none;" align="right"><div class="ABAdvanceButton"></div></td>
</tr>
</table>
<input type="hidden" name="ISBNs" id="ISBNs" value=""/>
<input type="hidden" name="ProductSolutions" id="ProductSolutions" value=""/>

<input type="hidden" name="ID" id="ID" value="<?=$ID;?>"/>

<script>
MAuditBills.AssignedUsersRoles = {
	"Test" : "Test",
	"Rar" : "Rar"
};

<?
	if($AuditBill) {
		$AuditBill->OnLoadISBNs();
	
		foreach($AuditBill->ISBNs as $ISBN) {
			echo "MAuditBills.AddISBN(\"".$ISBN->ISBN."\");\n";
		}
		
		foreach($AuditBill->ProductSolutions as $ProductSolution) {
			echo "MAuditBills.AddProductSolution(\"".$ProductSolution->ProductSolutionsID."\");\n";		
		}
	}else{
		echo "MAuditBills.AddISBN(\"\");\n";	
	}	
?>

$(MAuditBills.WatchInputs);
</script>
