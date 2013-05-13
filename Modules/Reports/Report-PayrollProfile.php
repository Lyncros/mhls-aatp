<?
	$ID = intval($_GET["ID"]);

	$Report = new CReportsPayrollProfile();
	$Report->OnLoad($ID);
?>
<a href="javascript:window.print();"><div class="PrintButton"></div></a>
<h1 class="PageTitle">Payroll Profile Report</h1>
<div style="float: right; margin-right: 10px; text-align: right;">
<?
$GroupName = "All Groups";
$UsersGroups = new CUsersGroups();
if($UsersGroups->OnLoad($Report->GetOptionValue("Group")) !== false) $GroupName = $UsersGroups->Name;
?>
<b>Group:</b> <?=$GroupName;?><br/>
<b>Only Active:</b> <?=$Report->GetOptionValue("OnlyActive");?><br/>
</div>
<b>Created:</b> <?=date("m/d/Y", $Report->Timestamp);?>

<?
	echo "<table width='100%' class='ReportTable'>";
	echo "<tr>";
		echo "<th>Group(s)</th>";
		echo "<th>Name (Username)</th>";
		echo "<th>Address</th>";
		echo "<th>Benefits</th>";
		echo "<th>Pay Rates</th>";
	echo "</tr>\n";

	foreach($Report->GetRows() as $Row) {
		echo "<tr>";
			echo "<td>".$Row["Group"]."</td>";
			echo "<td>".$Row["Name"]."</td>";
			echo "<td>".$Row["Address"]."</td>";
			echo "<td>".nl2br($Row["Benefits"])."</td>";
			echo "<td>".nl2br($Row["PayRates"])."</td>";
		echo "</tr>\n";
	}

	echo "</table>";
?>
<br/><br/>
<a href="/Reports">Back to Reports</a>