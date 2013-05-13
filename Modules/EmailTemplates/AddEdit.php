<?
	$WindowID = $_POST["CWindow_ID"];

	$TableObject = $this->Parent->TableObject;

	CForm::RandomPrefix();

	$Tabs = Array(
		"Primary",
		"Content"
	);

	CWindow::TabsInit($WindowID, $Tabs);
?>

<? CWindow::TabStart($WindowID); ?>
<table class="CForm_Table">
<?
	echo CForm::AddDropdown("Type", "Type", CEmailTemplates::GetTypeList(), $TableObject->Type);
	echo CForm::AddTextbox("Name", "Name", @$TableObject->Name);
	echo CForm::AddTextbox("Sub Name", "SubName", @$TableObject->SubName);

	echo CForm::AddTextbox("From Name", "FromName", @$TableObject->FromName);
	echo CForm::AddTextbox("From Email", "FromEmail", @$TableObject->FromEmail);
	echo CForm::AddTextbox("Reply To", "ReplyTo", @$TableObject->ReplyTo);
	echo CForm::AddTextbox("Subject", "Subject", @$TableObject->Subject);

	if(CSecurity::IsSuperAdmin()) {
		echo CForm::AddYesNo("Public", "Public", @$TableObject->Public);
	}
?>
</table>
<? CWindow::TabEnd(); ?>

<? CWindow::TabStart($WindowID); ?>
<center>
<textarea name="<?=CForm::GetPrefix();?>Content" id="<?=CForm::GetPrefix();?>Content" style="width: 95%; height: 400px;"><?=$TableObject->Content;?></textarea>
</center>
<table class="CForm_Table">
<?
	//echo CForm::AddRTE("Content", $TableObject->Content);
?>
</table>
<? CWindow::TabEnd(); ?>

<?
	echo CForm::AddHidden("ID", $TableObject->ID);
?>
<center><input type="button" class="CWindow_Save" onClick="MEmailTemplates.AddEdit(<?=$WindowID;?>, '<?=CForm::GetPrefix();?>');" value="Save"/></center>
