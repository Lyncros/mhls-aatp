<?
	$ShowInactive = (@$_GET["ShowInactive"] == 1 ? true : false);
?>
<h1>Default Milestones</h1>

<div class="ButtonAdd" onClick="MMilestones.Window_AddEdit(0);">Add</div>

<div style="position: absolute; right: 100px; top: 19px; line-height: 26px;">
	Show Inactive: <div class="ToggleOnOff <?=($ShowInactive ? "ToggleOn" : "ToggleOff");?>" onClick="CModule.Load('Milestones', {'ShowInactive' : '<?=((int)(!$ShowInactive));?>' });"></div>
</div>

<?
	function OnInfo($Value, $Row) {
		$Tag = "";

		if($Row->Active == 0)	$Tag = "Inactive";

		if($Tag != "") $Content .= "<div class='Tag'>$Tag</div>";

		$Content .= "<h1>".$Row->Name."</h1>";
		$Content .= "<p>".$Row->Summary."</p>";
		$Content .= "<div style=\"float: right;\" class='Icon_Delete' title='Delete Institution' onClick=\"MMilestones.Window_Delete(".$Row->ID.");\"></div>";
		$Content .= "<div style=\"float: right; margin-right: 4px;\" title='Edit Institution' class='Icon_Edit' onClick=\"MMilestones.Window_AddEdit(".$Row->ID.");\"></div>";

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
