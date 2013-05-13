<h1 class="PageTitle">System Settings</h1>

<table width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td width="50%" valign="top">

	<table class="CForm_Table">
	<?
		echo CForm::AddTextbox("New Authorizations (Days)", "NewAuthorizationsDays", CSystem::GetValue("NewAuthorizationsDays"));
		echo CForm::AddTextbox("Authorizations Close Expire (Days)", "AuthorizationsCloseExpireDays", CSystem::GetValue("AuthorizationsCloseExpireDays"));

		echo CForm::AddTextbox("Progress Reports Auto Unlock (Minutes)", "ProgressReportsAutoUnlock", CSystem::GetValue("ProgressReportsAutoUnlock"));
	?>
	</table>

	</td>
	<td width="50%" valign="top">

	<table class="CForm_Table">
	<?
		$Temp = new CUsersGroupsPermissions();
		$Temp->OnLoad(6);

		echo CForm::AddYesNo("Progress Reports Open", "ProgressReportsOpen", $Temp->Access);
		echo CForm::AddYesNo("System Locked", "SystemLocked", CSystem::GetValue("SystemLocked"));
	?>
	</table>


	</td>
</tr>
</table>

<br/><br/>
<center><input type="button" onClick="MSettings.Save();" value="Save"/></center>
<br/><br/>
