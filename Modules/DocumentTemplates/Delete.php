<?
	$WindowID = $_POST["CWindow_ID"];

	$TableObject = $this->Parent->TableObject;

	CForm::RandomPrefix();
?>
<table class="CForm_Table">
<tr>
	<td class="CForm_Name" align="center"><br/>Are you sure you want to delete this entry (<?=$TableObject->Name." ".$TableObject->SubName;?>)?</td>
</tr>
</table>
<?
	echo CForm::AddHidden("ID", $TableObject->ID);
?>
<br/>
<center><input type="button" onClick="MDocumentTemplates.Delete(<?=$WindowID;?>, '<?=CForm::GetPrefix();?>');" value="Delete"/></center>
