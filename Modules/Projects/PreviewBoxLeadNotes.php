<?
	$RecordID = $_POST["RecordID"];
	
	$Project = new CProjects();
	$Project->OnLoad($RecordID);
	

?>
<table class="CForm_Table">
<?
	
	echo CForm::AddRow("<p>" . $Project->LeadNotes . "</p>");
	
?>
</table>
