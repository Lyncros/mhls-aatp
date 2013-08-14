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
	echo CForm::AddTextbox("Main Contact", "MainContact", @$TableObject->MainContact, "Please enter the Main Contact.");	
	echo CForm::AddTextbox("Phone", "Phone", @$TableObject->Phone, "Please enter an Phone.");	
	echo CForm::AddTextbox("Email", "Email", @$TableObject->Email, "Please enter an Email.");
	
?>
</table>

<?
	echo CForm::AddHidden("ID", $TableObject->ID);
?>
<center><input type="button" class="CWindow_Save" onClick="MVendors.AddEdit(<?=$WindowID;?>, '<?=CForm::GetPrefix();?>');" value="Save"/></center>

