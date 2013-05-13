<?
	$WindowID = $_POST["CWindow_ID"];

	$FormPost = $this->Parent->TableObject;
?>
<center><br/><br/>Are you sure you want to delete this Group?<br/><br/><b><?=$FormPost->Name;?></b></center>
<?
	CForm::RandomPrefix();

	echo CForm::AddHidden("ID", $FormPost->ID);
?>
<br/>
<center><input type="button" onClick="MUsersGroups.Delete(<?=$WindowID;?>, '<?=CForm::GetPrefix();?>');" value="Delete"/></center>
