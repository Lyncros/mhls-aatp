<?
	$WindowID = $_POST["CWindow_ID"];

	$TableObject = $this->Parent->TableObject;

	if($TableObject) {
		CWindow::SetTitle($WindowID, "Edit Banned IP : ".$TableObject->IP);
	}

	CForm::RandomPrefix();
?>
<table class="CForm_Table">
<?
	echo CForm::AddTextbox("IP", "IP", @$TableObject->IP, "Please enter an IP");

	if($TableObject->Timestamp) {
		echo CForm::AddStatic("Timestamp", date("F j, Y | h:i a", @$TableObject->Timestamp));
	}else{
		echo CForm::AddStatic("Timestamp", "<i>Current Time</i>");
	}

	echo CForm::AddTextbox("Expire Minutes", "ExpireMinutes", @$TableObject->ExpireMinutes);
	echo CForm::AddTextarea("Reason", "Reason", @$TableObject->Reason);
?>
</table>
<br/>
<?
	echo CForm::AddHidden("ID", $TableObject->ID);
?>
<center><input type="button" onClick="MBannedIPs.AddEdit(<?=$WindowID;?>, '<?=CForm::GetPrefix();?>');" value="Save"/></center>
