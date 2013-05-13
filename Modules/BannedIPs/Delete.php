<?
	$WindowID = $_POST["CWindow_ID"];

	$TableObject = $this->Parent->TableObject;

	if($TableObject) {
		CWindow::SetTitle($WindowID, "Delete Banned IP : ".$TableObject->IP);
	}

	CForm::RandomPrefix();
?>
<br/><br/>
<center>Are you sure you want to delete this IP?</center>
fire<br/>
<?
	echo CForm::AddHidden("ID", $TableObject->ID);
?>
<br/>
<center><input type="button" onClick="MBannedIPs.Delete(<?=$WindowID;?>, '<?=CForm::GetPrefix();?>');" value="Delete"/></center>
