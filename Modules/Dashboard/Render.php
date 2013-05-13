<?
	header("Location: /Projects");
	exit;

	$Settings = $this->Parent->Settings;

	$OneDay		= 60 * 60 * 24;

	$DayOfWeek	= date("w", $Date);
	$Date		= mktime(2, 0, 0, date("m", $Date), date("d", $Date) - $DayOfWeek, date("Y", $Date));
?>



<!--<div style="clear: both;"></div>-->

<table cellspacing='0'>
	<tr>
		<th>Number</th>
		<th>Date Created</th>
		<th>Quick Note</th>
		<th>Status</th>
	</tr>
<?
	$AuditBills = new CAuditBills();
	if($AuditBills->OnLoadAll("ORDER BY `Created`") !== false) {
		foreach($AuditBills->Rows as $Row) {
			$Status = $Row->Step4Timestamp > 0 ? "<strong style='color:#546b1c;'>complete</strong>" : "<strong style='color:#961f1d;'>incomplete</strong>";
			echo "
			<tr>
				<td><strong>".$Row->Number."</strong></td>
				<td><strong>".date('n/j/Y', strtotime($Row->Created))."</strong></td>
				<td>".$Row->Note."</td>
				<td>".$Status."</td>
			</tr>
			";
		}
	}

?>
</table>