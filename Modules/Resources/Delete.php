<?
	$WindowID	= $_POST["CWindow_ID"];

	$TableObject = $this->Parent->TableObject;
?>
<center>
<br/><br/>Are you sure you want to delete this Resource?
<br/><br/><b><?=$TableObject->Title;?></b>
</center>
<?
	CForm::RandomPrefix();

	echo CForm::AddHidden("ID", $TableObject->ID);
?>
<br/>
<center><input type="button" onClick="MResources.Delete(<?=$WindowID;?>, '<?=CForm::GetPrefix();?>');" value="Delete"/></center>
