<?
	CForm::SetPrefix("");

	$User = CSecurity::$User;

	if($User->Email == "") {
		echo CBox::Alert("Please enter your Email Address below before continuing.")."<br/>";
	}
?>
<h1>My Account</h1>

<table width="100%" cellspacing="0" cellpadding="0" style='margin-top:50px;'>
<tr>
	<td width="50%" valign="top">

		<table class="CForm_Table TableFormGroup" style="margin-bottom: 20px;">
		<?
			echo CForm::AddRow("<h2>My Info</h2>");
			echo CForm::AddTextbox("Title", "Title", $User->Title);
			echo CForm::AddTextbox("First Name", "FirstName", $User->FirstName, "Please enter your First Name");
			echo CForm::AddTextbox("Middle Initial", "MiddleInitial", $User->MiddleInitial);
			echo CForm::AddTextbox("Last Name", "LastName", $User->LastName, "Please enter your Last Name");
			echo CForm::AddTextbox("Address", "Address", $User->Address);
			echo CForm::AddTextbox("Address 2", "Address2", $User->Address2);
			echo CForm::AddTextbox("City", "City", $User->City);
			echo CForm::AddTextbox("State", "State", $User->State);
			echo CForm::AddTextbox("Zip", "Zip", $User->Zip);
	
			echo CForm::AddTextbox("Office Phone", "OfficePhone", $User->OfficePhone);
			echo CForm::AddTextbox("Mobile Phone", "MobilePhone", $User->MobilePhone);
					
			echo CForm::AddTextbox("Email", "Email", $User->Email, "Please enter your Email Address");
		?>
		</table>
		
		<table class="CForm_Table TableFormGroup">
		<?
			echo CForm::AddRow("<h2>Notification Preferences</h2>");
			
			CForm::SetFormat("None");
			
			$ModuleNotifications = CModule::GetAllNotifications();
			//echo CForm::AddYesNo("Notify me of new messages", "NotificationsMessages", $User->NotificationsMessages);
			//echo CForm::AddYesNo("Notify me of new resources", "NotificationsResources", $User->NotificationsResources);
			
			$Type			= "Module";
			$NoticeCount	= 0;
			foreach($ModuleNotifications as $Name => $NoticeList) {
				if(CSecurity::CanAccess($Name) == false) continue;
				
				$i = 0;
				foreach($NoticeList as $Notice) {
					$BGColor = "";

					if($i % 2 == 1) {
						$BGColor = "#EEEEEE";
					}

					//$Popup	= 0;
					$Email	= 0;
					//$SMS	= 0;

					$NSubClass = new CNotifierSubscriptions();
					if($NSubClass->OnLoadByUsersID(CSecurity::GetUsersID(), $Type, $Name, $Notice) !== false) {
						//$Popup	= $NSubClass->Popup;
						$Email	= $NSubClass->Email;
						//$SMS	= $NSubClass->SMS;
					}

					$JID = "Notice_".$NoticeCount;

					echo "<tr>";
						echo "<td style='background-color: $BGColor;'>".$Notice."<input type='hidden' value=\"".CForm::MakeSafe(json_encode(Array("Type" => $Type, "Name" => $Name, "SubName" => $Notice)))."\" id='".CForm::GetPrefix().$JID."_Values'/></td>";
						//echo "<td align='center' style='background-color: $BGColor;'>".CForm::AddYesNo("", $JID."_Popup", $Popup)."</td>";
						echo "<td align='center' style='background-color: $BGColor;'>".CForm::AddYesNo("", $JID."_Email", $Email)."</td>";
						//echo "<td align='center' style='background-color: $BGColor;'>".CForm::AddYesNo("", $JID."_SMS", $SMS)."</td>";
					echo "<tr>";

					$i++;
					$NoticeCount++;
				}
				
			}
			
			CForm::SetFormat("Table");
		?>
		</table>

	</td>
	<td><div style="clear: both; width: 20px;"></div></td>
	<td width="50%" valign="top">

		<table class="CForm_Table TableFormGroup" style="margin-bottom: 20px;">
		<?
			echo CForm::AddRow("<h2>Type</h2>");
			
			echo CForm::AddStatic("Type", $User->Type);
				
			$Institution = new CInstitutions();
			if($Institution->OnLoad($User->InstitutionsID) !== false) {
				echo CForm::AddStatic("Institution", $Institution->Name);		
			}
			
			echo CForm::AddStatic("Username", $User->Username);
		?>
		</table>
	
		<table class="CForm_Table TableFormGroup">
		<?
			echo CForm::AddRow("<h2>Update Your Password</h2>");
	
			echo CForm::AddPassword("Current Password", "CurrentPassword", "");
			echo CForm::AddPassword("New Password", "Password1", "");
			echo CForm::AddPassword("New Password (again)", "Password2", "");
		?>
		</table>

	</td>
</tr>
<tr>
	<td colspan="3" align="right">
	
	<div class="SaveButton" value="Save" onClick="MMyAccount.Save('', this);"></div>
	
	</td>
</tr>
</table>

