<?
	$WindowID = $_POST["CWindow_ID"];

	$TableObject = $this->Parent->TableObject;

	CForm::RandomPrefix();

	if($TableObject) {
		CWindow::SetTitle($WindowID, "Edit Vendor : ".$TableObject->Name);
	}
?>

<table class="CForm_Table">
<?
	echo CForm::AddTextbox("Name", "Name", @$TableObject->Name, "Please enter a Name.");	
?>
</table>

<?
	echo CForm::AddHidden("ID", $TableObject->ID);
?>
<center><input type="button" class="CWindow_Save" onClick="MVendors.AddEdit(<?=$WindowID;?>, '<?=CForm::GetPrefix();?>');" value="Save"/></center>

