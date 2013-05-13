<div class="ReportBox"> 
	<h1>Payroll Report</h1> 

	<table cellspacing="1" cellpadding="0" style="margin-top: 50px;" align="center">
	<tr>
		<td><b>Start Date:</b> </td>
		<td valign="top" align="right"><input type="text" name="StartDate" value="<?=date("m/d/Y");?>" style="width: 80%" placeholder="mm/dd/YYYY"></td>
	</tr>
	<tr>
		<td><b>End Date:</b> </td>
		<td valign="top" align="right"><input type="text" name="EndDate" value="<?=date("m/d/Y");?>" style="width: 80%" placeholder="mm/dd/YYYY"></td>
	</tr>
	</table>
	<input type="hidden" name="Report" value="Payroll"/>
	<!-- <div class="Run"></div> -->
</div>

<div class="ReportBox"> 
	<h1>Payroll Profile Report</h1> 

	<table cellspacing="1" cellpadding="0" style="margin-top: 50px;" align="center">
	<tr>
		<td><b>Group:</b> </td>
		<td valign="top" align="right" style='width:70%;'>
		<select name="Group">
			<option value="0">All Groups</option>
			<?
			$UserGroups = new CUsersGroups();
			if($UserGroups->OnLoadAll("WHERE `Type` = 'Normal' && `Active` = 1 ORDER BY `Name` ASC") !== false) {
				foreach($UserGroups->Rows as $Group) {
					echo "<option value='".$Group->ID."'>".$Group->Name."</option>";
				}
			}
			?>
		</select>
		</td>
	</tr>
	<tr>
		<td><b>Only Active:</b> </td>
		<td valign="top" align="right">
		<select name="OnlyActive">
			<option value="Yes">Yes</option>
			<option value="No">No</option>
		</select>
		</td>
	</tr>
	</table>
	<input type="hidden" name="Report" value="PayrollProfile"/>
	<div class="Run"></div>
</div>

<div class="ReportBox"> 
	<h1>Time Entry Report</h1> 

	<table cellspacing="1" cellpadding="0" style="margin-top: 50px;" align="center">
	<tr>
		<td><b>Start Date:</b> </td>
		<td valign="top" align="right"><input type="text" name="StartDate" value="<?=date("m/d/Y");?>" style="width: 60%" placeholder="mm/dd/YYYY"></td>
	</tr>
	<tr>
		<td><b>End Date:</b> </td>
		<td valign="top" align="right"><input type="text" name="EndDate" value="<?=date("m/d/Y");?>" style="width: 60%" placeholder="mm/dd/YYYY"></td>
	</tr>
	<tr>
		<td><b>Mark Entries:</b> </td>
		<td valign="top" align="right">
		<select name="MarkEntries">
			<option value="Yes">Yes</option>
			<option value="No">No</option>
		</select>
		</td>
	</tr>
	<tr>
		<td><b>Show Marked:</b> </td>
		<td valign="top" align="right">
		<select name="ShowMarked">
			<option value="No">No</option>
			<option value="Yes">Yes</option>
		</select>
		</td>
	</tr>
	</table>
	<input type="hidden" name="Report" value="TimeEntry"/>
	<div class="Run"></div>
</div>
<div style="clear: both"></div>
<br/>

<?
	function OnInfo($Value, $Row) {
		$Tag = "";

		if($Tag != "") $Content .= "<div class='Tag'>$Tag</div>";

		$Content .= "<h1>".$Row->Name."</h1>";

		$User = new CUsers();
		if($User->OnLoadByID($Row->UsersID) !== false) {
			$Content .= "<br/><br/><b>Ran By:</b> ".$User->GetName();
		}

		$Content .= "<br/>";
		$Content .= "<div style=\"float: right;\" class='Icon_Delete' onClick=\"MReports.Window_Delete(".$Row->ID.");\"></div>";
		$Content .= "<div style=\"float: right; margin-right: 4px;\" class='Icon_View' onClick=\"CModule.Load('Reports', {'ID' : ".$Row->ID.", 'Report' : '".$Row->Type."'});\"></div>";

		return $Content;
	}

	function OnTimestamp($Value, $Row) {
		return date("n/j/Y", $Value);
	}

	function OnOptions($Value, $Row) {
		$Report = new CReports();
		$Report->OnLoad($Value);

		foreach($Report->Options as $Option) {
			if($Option->Key == "Group") {
				$GroupName = "All Groups";
				$UsersGroups = new CUsersGroups();
				if($UsersGroups->OnLoad($Option->Value) !== false) $GroupName = $UsersGroups->Name;
				$Content .= "<b>".CFormat::SplitByCaps($Option->Key).":</b> ".$GroupName."<br/>";
			} else {
				$Content .= "<b>".CFormat::SplitByCaps($Option->Key).":</b> ".$Option->Value."<br/>";
			}
		}

		return $Content;
	}

	$Search = new CSearch("Reports");

	$Search->AddRestriction("Deleted", 0);

	$Search->AddColumn("Report", "Name", "40%", CSEARCHCOLUMN_SEARCHTYPE_LOOSE, "", "", "", "OnInfo");
	$Search->AddColumn("Created", "Timestamp", "20%", CSEARCHCOLUMN_SEARCHTYPE_LOOSE, "", "", "", "OnTimestamp");
	$Search->AddColumn("# of Rows", "NumberOfRows", "20%", CSEARCHCOLUMN_SEARCHTYPE_LOOSE);
	$Search->AddColumn("Options", "ID", "20%", CSEARCHCOLUMN_SEARCHTYPE_NOSEARCH, "", "", "", "OnOptions");

	$Search->SetDefaultColumn(1, 1);

	$Search->OnInit();

	$Search->OnRender();
	$Search->OnRenderPages();
?>

<script>$(MReports.WatchForms);</script>