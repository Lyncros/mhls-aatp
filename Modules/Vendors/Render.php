<h1>Vendors</h1>

<div class="ButtonAdd" onClick="MVendors.Window_AddEdit(0);">Add</div>
<?
	function OnInfo($Value, $Row) {
		$Tag = "";

		$Content .= "<h1>".$Row->Name."</h1>";
		
		$Content .= "<div style=\"float: right;\" class='Icon_Delete' title='Delete Vendor' onClick=\"MVendors.Window_Delete(".$Row->ID.");\"></div>";
		$Content .= "<div style=\"float: right; margin-right: 4px;\" title='Edit Vendor' class='Icon_Edit' onClick=\"MVendors.Window_AddEdit(".$Row->ID.");\"></div>";

		return $Content;
	}

	function OnTimestamp($Value, $Row) {
		return date("n/j/Y", $Value);
	}

	$Search = new CSearch("Vendors");

	if(!CSecurity::IsSuperAdmin()) {
		$Search->AddRestriction("SuperAdmin", 0);
	}

	$Search->AddColumn("Name", "Name", "70%", CSEARCHCOLUMN_SEARCHTYPE_LOOSE, "", "", "", "OnInfo");
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
