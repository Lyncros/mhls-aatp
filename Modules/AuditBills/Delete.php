<?
	$WindowID = $_POST["CWindow_ID"];

	$TableObject = $this->Parent->TableObject;

	CForm::RandomPrefix();
?>
<center><br/><br/>Are you sure you want to delete this entry (<?=$TableObject->Name." ".$TableObject->SubName;?>)?</center>
<?
	echo CForm::AddHidden("ID", $TableObject->ID);
?>
<br/>
<center><input type="button" onClick="MEmailTemplates.Delete(<?=$WindowID;?>, '<?=CForm::GetPrefix();?>');" value="Delete"/></center>
