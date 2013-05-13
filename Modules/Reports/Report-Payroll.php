<?
	$ID = intval($_GET["ReportID"]);

	$Report = new CReportsPayroll();
	$Report->OnLoad($ID);

	echo "<table width='100%'>";
	echo "<tr>";
		echo "<td>Discipline</td>";
		echo "<td>Employee</td>";
		echo "<td>IEA Hours</td>";
		echo "<td>Therapy Hours</td>";
		echo "<td>Rate</td>";
	echo "</tr>";

	$StartDate	= date("Y-m-d", strtotime($_GET["StartDate"]));
	$EndDate	= date("Y-m-d", strtotime($_GET["EndDate"]));

	echo $StartDate." to ".$EndDate."<br/><br/>";

	$Users = new CUsers();
	$Users->OnLoadAll("WHERE `Type` = 'Provider' && `Active` = 1");

	foreach($Users->Rows as $Row) {
		$Provider = $Users->GetProvider();

		echo "<tr>";
			echo "<td>".$Provider->Certification."</td>";
			echo "<td>".$Row->FirstName." ".$Row->LastName."</td>";
		echo "<tr>";
	}

	echo "</table>";
?>
