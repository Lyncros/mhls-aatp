<?
	$WindowID = $_POST["CWindow_ID"];

	$FormPost = $this->Parent->TableObject;
?>
<center><br/><br/>Are you sure you want to delete this entry (<?=$FormPost->Username;?>)?</center>
<?
	CForm::RandomPrefix();

	echo CForm::AddHidden("ID", $FormPost->ID);
?>
<br/>
<center><input type="button" onClick="MUsers.Delete(<?=$WindowID;?>, '<?=CForm::GetPrefix();?>');" value="Delete"/></center>
