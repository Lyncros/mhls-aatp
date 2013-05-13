<?
	$WindowID = $_POST["CWindow_ID"];

	$TableObject = $this->Parent->TableObject;

	CForm::RandomPrefix();

	if($TableObject) {
		CWindow::SetTitle($WindowID, "Edit Alert : ".$TableObject->Title);
	}else{
		CBox::Alert("The Admin adding the Alert will always receive a Copy, even if 'Send to Admins' is turned off.");
	}
?>

<table class="CForm_Table">
<?
	echo CForm::AddTextbox("Title", "Title", @$TableObject->Title, "Please enter a Title.");
	echo CForm::AddTextarea("Content", "Content", @$TableObject->Content, "Please enter the Alert Content.");

	$GroupString = "";

	$Groups = new CUsersGroups();
	if($Groups->OnLoadAll("WHERE `Active` = 1 && `Type` = 'Normal' ORDER BY `Name` ASC") !== false) {
		foreach($Groups->Rows as $Row) {
			$GroupString .= "<div class='AvailableGroupsItem' rel='".$Row->ID."'>".$Row->Name."</div>";
		}
	}

	echo CForm::AddStatic("Send to Groups", "<table width='100%'><tr>
	<td width='50%' valign='top' align='center'><b>Available Groups</b><div style='width: 100%; height: 100px; overflow: auto; border: 1px #DDDDDD solid;' id='".CForm::GetPrefix()."AvailableGroups'>$GroupString</div></td>
	<td width='50%' valign='top' align='center'><b>Selected Groups</b><div style='width: 100%; height: 100px; overflow: auto; border: 1px #DDDDDD solid;' id='".CForm::GetPrefix()."SelectedGroups'></div></td>
	</tr></table><input type='hidden' name='".CForm::GetPrefix()."GroupList' id='".CForm::GetPrefix()."GroupList'/>");

	echo CForm::AddYesNo("Send to Providers", "SendToProviders", 1);
	echo CForm::AddYesNo("Send to Admins", "SendToAdmins", 1);

	echo CForm::AddYesNo("Send Email", "SendEmail", 0);

	if($TableObject) {
		echo CForm::AddYesNo("Update Timestamp", "UpdateTimestamp", 1);
		echo CForm::AddYesNo("Mark Unread", "MarkUnread", 1);
	}
?>
</table>

<?
	echo CForm::AddHidden("ID", $TableObject->ID);
?>
<center><input type="button" class="CWindow_Save" onClick="MAlerts.AddEdit(<?=$WindowID;?>, '<?=CForm::GetPrefix();?>');" value="Save"/></center>
<script>
$(function() {
	MAlerts.WatchGroups("<?=CForm::GetPrefix();?>");
});
</script>
