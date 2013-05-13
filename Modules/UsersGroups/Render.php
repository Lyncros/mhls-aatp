<?
	$ShowInactive = (@$_GET["ShowInactive"] == 1 ? true : false);
?>
<h2>User Groups</h2>

<div class="ButtonAdd" onClick="CModule.Load('UsersGroups', {'Page' : 'Add'});">Add</div>

<table width="100%" style="border: none; margin-top: 20px; margin-bottom: 20px;" cellspacing="0" cellpadding="0">
<tr>
	<td valign="middle" align="left" style="background-color: transparent; border: none;">
		<div style="display: inline-block; line-height: 26px;">
			Show Inactive: <div class="ToggleOnOff <?=($ShowInactive ? "ToggleOn" : "ToggleOff");?>" onClick="CModule.Load('UsersGroups', {'ShowInactive' : '<?=((int)(!$ShowInactive));?>', 'CSearch_Keywords' : $('#CSearch_Keywords').val() });"></div>
		</div>

		<div style="margin-left: 20px; display: inline-block; line-height: 26px;">
			Search: <input type="text" name="CSearch_Keywords" id="CSearch_Keywords" value="<?=CForm::MakeSafe($_GET["CSearch_Keywords"]);?>" onKeyDown="if(event.keyCode == 13) { CModule.Load('UsersGroups', {'ShowInactive' : '<?=((int)($ShowInactive));?>', 'CSearch_Keywords' : this.value}); }"/>
		</div>
	</td>
</tr>
</table>

<?
	function OnInfo($Value, $Row) {
		$Tag = "";

		if($Tag != "") $Content .= "<div class='Tag'>$Tag</div>";

		$Content .= "<h1>".$Row->Name."</h1>";

		$Count = 0;

		$UGC = new CTable("Users");
		if($UGC->OnLoadAll("WHERE `UsersGroupsID` = ".$Row->ID) !== false) {
			$Count = count($UGC->Rows);
		}

		$Content .= "<b>Number of Users:</b> $Count <br/>";

		$Content .= "<br/>";
		$Content .= "<div style=\"float: right;\" class='Icon_Delete' onClick=\"MUsersGroups.Window_Delete(".$Row->ID.");\"></div>";
		$Content .= "<div style=\"float: right; margin-right: 4px;\" class='Icon_Edit' onClick=\"CModule.Load('UsersGroups', {'ID' : ".$Row->ID."});\"></div>";

		return $Content;
	}

	function OnTimestamp($Value, $Row) {
		return date("n/j/Y", $Value);
	}

	$Search = new CSearch("UsersGroups");

	$Search->AddRestriction("Type", "Normal");

	if($ShowInactive == false) {
		$Search->AddRestriction("Active", 1);
	}

	$Search->AddColumn("Group", "Name", "70%", CSEARCHCOLUMN_SEARCHTYPE_LOOSE, "", "", "", "OnInfo");
	$Search->AddColumn("Added", "Timestamp", "15%", CSEARCHCOLUMN_SEARCHTYPE_LOOSE, "", "", "", "OnTimestamp");
	$Search->AddColumn("Updated", "TimestampUpdated", "15%", CSEARCHCOLUMN_SEARCHTYPE_LOOSE, "", "", "", "OnTimestamp");

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
