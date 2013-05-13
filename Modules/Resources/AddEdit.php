<?
	$WindowID = $_POST["CWindow_ID"];

	$TableObject = $this->Parent->TableObject;

	CForm::RandomPrefix();

	if($TableObject) {
		CWindow::SetTitle($WindowID, "Edit Resource : ".$TableObject->Title);
	}
?>

<table class="CForm_Table">
<?
	echo CForm::AddTextbox("Title", "Title", @$TableObject->Title, "Please enter a Title.");

	echo CForm::AddUpload("Upload ".($TableObject ? "New" : "")." File", "Filename");

	echo CForm::AddYesNo("Active", "Active", ($TableObject ? $TableObject->Active : 1));
?>
</table>

<?
	echo CForm::AddHidden("ID", $TableObject->ID);
?>
<center><input type="button" class="CWindow_Save" onClick="MResources.AddEdit(<?=$WindowID;?>, '<?=CForm::GetPrefix();?>');" value="Save"/></center>
