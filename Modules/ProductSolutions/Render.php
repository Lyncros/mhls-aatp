<?
	$ShowInactive = (@$_GET["ShowInactive"] == 1 ? true : false);
?>
<h1>Product Solutions</h1>

<div class="ButtonAdd" onClick="MProductSolutions.Window_AddEdit(0);">Add</div>

<div style="position: absolute; right: 100px; top: 19px; line-height: 26px;">
	Show Inactive: <div class="ToggleOnOff <?=($ShowInactive ? "ToggleOn" : "ToggleOff");?>" onClick="CModule.Load('ProductSolutions', {'ShowInactive' : '<?=((int)(!$ShowInactive));?>', 'CSearch_Keywords' : $('#CSearch_Keywords').val() });"></div>
</div>

<?
	function OnPrice($Value, $Row) {
		return "$".number_format($Value, 2);
	}

	function OnTimestamp($Value, $Row) {
		return date("n/j/Y", $Value);
	}
	
	function OnPublic($Value, $Row) {
		if($Value == 0) return "No";
		
		return "<span style='color: green; font-weight: bold'>Yes</span>";
	}

	$Search = new CSearch("ProductSolutions");

	if($ShowInactive == false) {
		$Search->AddRestriction("Active", 1);
	}

	$Search->AddColumn("Name", "Name", "25%", CSEARCHCOLUMN_SEARCHTYPE_LOOSE);
	$Search->AddColumn("Institution", "InstitutionsID", "25%", CSEARCHCOLUMN_SEARCHTYPE_LOOSE, "Institutions", "ID", "Name");	
	$Search->AddColumn("Price", "Price", "10%", CSEARCHCOLUMN_SEARCHTYPE_LOOSE, "", "", "", "OnPrice");	
	$Search->AddColumn("Public", "Public", "5%", CSEARCHCOLUMN_SEARCHTYPE_LOOSE, "", "", "", "OnPublic");
	$Search->AddColumn("Active", "Active", "5%", CSEARCHCOLUMN_SEARCHTYPE_LOOSE, "", "", "", "OnPublic");	
	$Search->AddColumn("Created", "Timestamp", "15%", CSEARCHCOLUMN_SEARCHTYPE_LOOSE, "", "", "", "OnTimestamp");
	$Search->AddColumn("Updated", "TimestampUpdated", "15%", CSEARCHCOLUMN_SEARCHTYPE_LOOSE, "", "", "", "OnTimestamp");
	$Search->AddColumn("<div class='Icon_Edit' style='display: none;'></div>", "<div class='Icon_Edit' onClick=\"MProductSolutions.Window_AddEdit([[ID]]);\" title='Edit Product Solution'></div>", "22px");
	$Search->AddColumn("<div class='Icon_Delete' style='display: none;'></div>", "<div class='Icon_Delete' onClick=\"MProductSolutions.Window_Delete([[ID]]);\" title='Delete Product Solution'></div>", "22px");

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
