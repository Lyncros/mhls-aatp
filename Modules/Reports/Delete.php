<?
	$WindowID = $_POST["CWindow_ID"];

	$TableObject = $this->Parent->TableObject;

	if($TableObject) {
		CWindow::SetTitle($WindowID, "Delete Report : ".$TableObject->Name);
	}

	CForm::RandomPrefix();
?>
<br/><br/>
<center>Are you sure you want to delete this Report?</center>
<br/>
<?
	echo CForm::AddHidden("ID", $TableObject->ID);
?>
<br/>
<center><input type="button" onClick="MReports.Delete(<?=$WindowID;?>, '<?=CForm::GetPrefix();?>');" value="Delete"/></center>
