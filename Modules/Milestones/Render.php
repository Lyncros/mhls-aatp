<?
	$ShowInactive = (@$_GET["ShowInactive"] == 1 ? true : false);
	
	$ToDosLists = new CToDosLists();	
	$ToDosLists->OnLoadAll("ORDER BY `Name`");		
?>
<h1>Default Milestones</h1>

<div class="ButtonAdd" onClick="$('#Add').slideToggle();">Add</div>

<div class='AddResource' id='Add' style='z-index:1001;'>
	<div class='ProjectWrapper'>
		<div class='ProjectContainer'>
			<center>
				<table class='CForm_Table'>
					<?
					$ToDos = new CToDos();
					$ToDos->OnLoadAll("ORDER BY `Name`");
				
					echo CForm::AddTextbox("Name", "AddName", "");
					echo CForm::AddYesNo("Customer Approval", "AddCustomerApproval", "");
					echo CForm::AddTextarea("Summary", "AddSummary", "");
					echo CForm::AddTextbox("Plant Allocated", "AddPlantAllocated", "");
					echo CForm::AddListbox("ToDos Lists", "AddToDosLists", CForm::RowsToArray($ToDosLists->Rows, "Name"));					
					echo CForm::AddYesNo("Active", "AddActive", "");
					?>
					<tr>
						<td colspan='2'>							
							<div class='Button' value='Submit' onClick="MMilestones.Save(0);">Submit</div>
							<div class='Button' value='Cancel' onClick="$('#Add').slideUp();">Cancel</div>
						</td>
					</tr>
				</table>
			</center>
		</div>
	</div>
</div>

<div style="position: absolute; right: 100px; top: 19px; line-height: 26px;">
	Show Inactive: <div class="ToggleOnOff <?=($ShowInactive ? "ToggleOn" : "ToggleOff");?>" onClick="CModule.Load('Milestones', {'ShowInactive' : '<?=((int)(!$ShowInactive));?>' });"></div>
</div>

<?
	function OnInfo($Value, $Row) {
		$Tag = "";
		$ToDosLists = new CToDosLists();	
		$ToDosLists->OnLoadAll("ORDER BY `Name`");	
		
		if($Row->Active == 0)	$Tag = "Inactive";

		if($Tag != "") $Content .= "<div class='Tag'>$Tag</div>";

		
		$Content .= "<h1 id='H1".$Row->ID."'>".$Row->Name."</h1>";
		$Content .= "<p id='P".$Row->ID."'>".$Row->Summary."</p>";
		$Content .= "<form style='display:none;' id='Edit".$Row->ID."' action='MProductTypes.Save(".$Row->ID.");'><table class='CForm_Table'>";
				
		$Content .= CForm::AddTextbox("Name", "Edit".$Row->ID."Name", @$Row->Name, "Please enter a Name.", "", "", "border-top:none;");
		$Content .= CForm::AddYesNo("CustomerApproval", "Edit".$Row->ID."CustomerApproval", $Row->CustomerApproval, "OnOff", "", "border-top:none;");
		$Content .= CForm::AddTextarea("Summary", "Edit".$Row->ID."Summary", $Row->Summary);
		$Content .= CForm::AddTextbox("Plant Allocated", "Edit".$Row->ID."PlantAllocated", $Row->PlantAllocated);
		$Content .= CForm::AddListbox("ToDos Lists", "Edit".$Row->ID."ToDosLists", CForm::RowsToArray($ToDosLists->Rows, "Name"), unserialize(@$Row->ToDosLists), "", "", "", "", "border-top:none;");
		$Content .= CForm::AddYesNo("Active", "Edit".$Row->ID."Active", $Row->Active, "OnOff", "", "border-top:none;");
		$Content .= "<tr>
			<td colspan='2' style='border-top:none;'>
				<!--<div style='position:relative; height:20px;'><input type='button' class='CWindow_Save' style='bottom:2px;' onClick=\"if(MProductTypes.Save(".$Row->ID.")) $('#Edit".$Row->ID."').slideUp();\" value='Save Changes'/></div>-->
				<div class='Button' value='Submit' onClick=\"MMilestones.Save(".$Row->ID.");$('#Edit".$Row->ID."').slideUp();\">Submit</div>
				<div class='Button' value='Cancel' onClick=\"$('#Edit".$Row->ID."').slideUp();\">Cancel</div>
			</td>
		</tr>";
		$Content .= "</table></form>";
		$Content .= "<div style=\"float: right;\" class='Icon_Delete' title='Delete Milestone' onClick=\"MMilestones.Window_Delete(".$Row->ID.");\"></div>";
		$Content .= "<div style=\"float: right; margin-right: 4px;\" title='Edit Milestone' class='Icon_Edit' onClick=\"$('#Edit".$Row->ID."').slideToggle();\"></div>";
				
		return $Content;
	}

	function OnTimestamp($Value, $Row) {
		if(strtotime($Value) > 0) return date("n/j/Y", strtotime($Value));
		
		return "";
	}

	function OnPublic($Value, $Row) {
		if($Value == 0) return "No";
		
		return "<span style='color: green; font-weight: bold'>Yes</span>";
	}

	$Search = new CSearch("Milestones");

	if($ShowInactive == false) {
		$Search->AddRestriction("Active", 1);
	}

	$Search->AddColumn("Name", "Name", "65%", CSEARCHCOLUMN_SEARCHTYPE_LOOSE, "", "", "", "OnInfo");
	$Search->AddColumn("Active", "Active", "5%", CSEARCHCOLUMN_SEARCHTYPE_LOOSE, "", "", "", "OnPublic");	
	$Search->AddColumn("Added", "Created", "15%", CSEARCHCOLUMN_SEARCHTYPE_LOOSE, "", "", "", "OnTimestamp");
	$Search->AddColumn("Updated", "Modified", "15%", CSEARCHCOLUMN_SEARCHTYPE_LOOSE, "", "", "", "OnTimestamp");

	$Search->SetDefaultColumn(0);

	$Search->OnInit();

	$Search->OnRender();
	$Search->OnRenderPages();
?>

<script type="text/javascript">
$(function() {
	$(".Tag").each(function() {
		$(this).parent().parent().find("td").css("background-color", "#EEEEEE");
	});
});
</script>
