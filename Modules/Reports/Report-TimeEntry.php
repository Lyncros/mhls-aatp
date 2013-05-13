<?
	$ID = intval($_GET["ID"]);

	$Report = new CReportsTimeEntry();
	$Report->OnLoad($ID);
?>
<a href="javascript:window.print();"><div class="PrintButton"></div></a>
<h1 class="PageTitle">Time Entry Report</h1>
<div style="float: right; margin-right: 10px; text-align: right;">
<b>Mark Entries:</b> <?=$Report->GetOptionValue("MarkEntries");?><br/>
<b>Show Marked:</b> <?=$Report->GetOptionValue("ShowMarked");?>
</div>
<i>From <?=$Report->GetOptionValue("StartDate");?> to <?=$Report->GetOptionValue("EndDate");?></i><br/>
<b>Created:</b> <?=date("m/d/Y", $Report->Timestamp);?>

<?
	echo "<table width='100%' class='ReportTable'>";
	echo "<tr>";
		echo "<th>Name</th>";
		echo "<th>Date</th>";
		echo "<th>Patients Name</th>";
		echo "<th>Minutes</th>";
		echo "<th>CPT Codes</th>";
		echo "<th>Auth #</th>";
	echo "</tr>\n";

	foreach($Report->GetRows() as $Row) {
		echo "<tr>";
			echo "<td>".$Row["Name"]."</td>";
			echo "<td>".$Row["Date"]."</td>";
			echo "<td>".$Row["PatientsName"]."</td>";
			echo "<td>".$Row["Minutes"]."</td>";
			echo "<td>".$Row["CPTCodes"]."</td>";
			echo "<td>".$Row["AuthorizationNumber"]."</td>";
		echo "</tr>\n";
	}

	echo "</table>";
?>
<br/><br/>
<a href="/Reports">Back to Reports</a>