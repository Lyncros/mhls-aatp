<?
	$WindowID = $_POST["CWindow_ID"];

	$FormPost = $this->Parent->TableObject;
?>
<center><br/><br/>Are you sure you want to delete this default To Do List (<?=$FormPost->Name;?>)?</center>
<?
	CForm::RandomPrefix();

	echo CForm::AddHidden("ID", $FormPost->ID);
?>
<br/>
<center><input type="button" onClick="MToDosLists.Delete(<?=$WindowID;?>, '<?=CForm::GetPrefix();?>');" value="Delete"/></center>
