<?
	if(CSecurity::IsAdmin()) {
		$ShowInactive = (@$_GET["ShowInactive"] == 1 ? true : false);
	}else{
		$ShowInactive = false;
	}
?>

<?
	if(CSecurity::IsAdmin()) {
?>
<table width="100%" style="padding-right: 20px; padding-left: 10px; padding-top: 10px;" cellspacing="0" cellpadding="0">
<tr>
	<td valign="top" align="left" style="line-height: 19px;">
		Show Inactive: <div class="ToggleOnOff <?=($ShowInactive ? "ToggleOn" : "ToggleOff");?>" onClick="CModule.Load('Resources', {'ShowInactive' : '<?=((int)(!$ShowInactive));?>'});"></div>
	</td>
	<td valign="top" align="right">
		<div class="ButtonAdd" onClick="MResources.Window_AddEdit(0);"></div>
	</td>
</tr>
</table>
<?
	}
?>

<?
	function OnInfo($Value, $Row) {
		$Icon = "";

		if(stripos($Row->FilenameOriginal, ".doc") !== false)	$Icon = "ResourcesCellIconDOC";
		if(stripos($Row->FilenameOriginal, ".docx") !== false)	$Icon = "ResourcesCellIconDOC";

		if(stripos($Row->FilenameOriginal, ".xls") !== false)	$Icon = "ResourcesCellIconXLS";
		if(stripos($Row->FilenameOriginal, ".xlsx") !== false)	$Icon = "ResourcesCellIconXLS";

		if(stripos($Row->FilenameOriginal, ".pdf") !== false)	$Icon = "ResourcesCellIconPDF";

		$Content .= "<div class='ResourcesCellInactiveTag' style='".($Row->Active == 0 ? "display: block" : "display: none")."'>Inactive</div>";
		$Content .= "<div class='ResourcesCellIcon ".$Icon."'></div>";

		$Content .= "<h1>".$Row->Title."</h1>";
		$Content .= "<a href='/Resources?Action=DownloadFile&ID=".$Row->ID."'>Download File</a>";

		if(CSecurity::IsAdmin()) {
			$Content .= "<div class='Icon_Delete' onClick='MResources.Window_Delete(".$Row->ID.");' style='float: right;'></div>";
			$Content .= "<div class='Icon_Edit' onClick='MResources.Window_AddEdit(".$Row->ID.");' style='float: right; margin-right: 4px;'></div>";
		}

		return $Content;
	}

	function OnTimestamp($Value, $Row) {
		return date("n/j/Y", $Value);
	}

	$Search = new CSearch("Resources");
 
	if($ShowInactive == false) {
		$Search->AddRestriction("Active", 1);
	}

	$Search->AddColumn("Resources", "Title", "50%", CSEARCHCOLUMN_SEARCHTYPE_LOOSE, "", "", "", "OnInfo");
	$Search->AddColumn("Added", "Timestamp", "25%", CSEARCHCOLUMN_SEARCHTYPE_LOOSE, "", "", "", "OnTimestamp");
	$Search->AddColumn("Updated", "TimestampUpdated", "25%", CSEARCHCOLUMN_SEARCHTYPE_LOOSE, "", "", "", "OnTimestamp");

	$Search->SetDefaultColumn(0);

	$Search->OnInit();

	$Search->OnRender();
	$Search->OnRenderPages();
?>
