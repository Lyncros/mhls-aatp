<?
	$ShowInactive = (@$_GET["ShowInactive"] == 1 ? true : false);
?>
<h1>Users</h1>

<div class="ButtonAdd" onClick="CModule.Load('Users', {'Page' : 'Add'});">Add</div>

<div style="position: absolute; right: 100px; top: 19px; line-height: 26px;">
	Show Inactive: <div class="ToggleOnOff <?=($ShowInactive ? "ToggleOn" : "ToggleOff");?>" onClick="CModule.Load('Users', {'ShowInactive' : '<?=((int)(!$ShowInactive));?>' });"></div>
</div>

<?
	function OnInfo($Value, $Row) {
		$Tag = "";

		if($Row->Active == 0)	$Tag = "Inactive";

		if($Tag != "") $Content .= "<div class='Tag'>$Tag</div>";

		$Content .= "<h1 style='margin:0px;'>".$Row->LastName.", ".$Row->FirstName."</h1>";
		//$Content .= $Row->Address1."<br/>";
		
		$Group = new CUsersGroups();
		$Group->OnLoad($Row->UsersGroupsID);
		$Content .= $Group->Name."<br/>";

		//if($Row->Address2 != "") $Content .= $Row->Address2."<br/>";

		//$Content .= $Row->City.", ".$Row->State." ".$Row->Zip."<br/>";
		$Content .= "<a href=\"mailto:".$Row->Email."\">".$Row->Email."</a>";

		if($Row->Phone != "") $Content .= "<br/>".$Row->Phone;

		$Content .= "<div style=\"float: right;\" class='Icon_Delete' title='Delete User' onClick=\"MUsers.Window_Delete(".$Row->ID.");\"></div>";
		$Content .= "<div style=\"float: right; margin-right: 4px;\" title='Edit User' class='Icon_Edit' onClick=\"CModule.Load('Users', {'ID' : ".$Row->ID."});\"></div>";

		return $Content;
	}

	function OnTimestamp($Value, $Row) {
		return date("n/j/Y", $Value);
	}

	$Search = new CSearch("Users");

	if(!CSecurity::IsSuperAdmin()) {
		$Search->AddRestriction("SuperAdmin", 0);
	}

	if($ShowInactive == false) {
		$Search->AddRestriction("Active", 1);
	}

	$Search->AddColumn("Users", "LastName", "70%", CSEARCHCOLUMN_SEARCHTYPE_LOOSE, "", "", "", "OnInfo");
	//$Search->AddColumn("Username", "Username", "15%", CSEARCHCOLUMN_SEARCHTYPE_LOOSE);
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
