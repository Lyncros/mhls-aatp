<?
	$Step = intval(str_replace("Step", "", $_GET["Page"]));
?>
<h1>Step <?=$Step;?></h1>

<?
	if(CSecurity::IsSuperAdmin() && $Step == 1) {
		echo "<div class='ButtonAdd' onMousedown=\"$(this).addClass('ButtonAddPressed');\" onMouseup=\"$(this).removeClass('ButtonAddPressed');\" onClick=\"CModule.Load('AuditBills', {'Page' : 'New'});\">Add</div>";
	}

	function OnBold($Value, $Row) {
		return "<b>$Value</b>";
	}

	function OnTimestamp($Value, $Row) {
		return "<b>".date("m/d/Y", strtotime($Row->Created))."</b>";
	}
	
	function OnLSC($Value, $Row) {
		$AuditBill = new CAuditBills();
		$AuditBill->OnLoad($Row->ID);
			
		$LSCName 	 = "-";
		$LSCID		 = 0;
			
		$LSCs = $AuditBill->GetLSCs();
		foreach($LSCs as $LSC) {
			$LSCName = $LSC->GetName();
			$LSCID	 = $LSC->ID;
		}
		
		return "<div style='cursor: pointer;' onClick=\"MAuditBills.ShowPreviewBox(this, 'LSC', ".$LSCID.");\">".$LSCName."</div>";
	}
	
	function OnInstitutionContact($Value, $Row) {	
		$User = new CUsers();
		if($User->OnLoad($Row->InstitutionsUsersID) === false) return "-";

		return "<div style='cursor: pointer;' onClick=\"MAuditBills.ShowPreviewBox(this, 'InstitutionContact', ".$User->ID.");\">".$User->GetName()."</div>";		
	}
	
	function OnInstitution($Value, $Row) {
		if($Value == "") return "-";

		return "<div style='cursor: pointer;' onClick=\"MAuditBills.ShowPreviewBox(this, 'Institution', ".$Row->InstitutionsID.");\">".$Value."</div>";		
	}

	$Search = new CSearch("AuditBills");
	
	//$Search->SetOnClick("CModule.Load('AuditBills', {'ID' : [[ID]]})");	
	
	$Search->AddRestriction("Step", $Step);

	$Search->AddColumn("Acct. #", "Number", "14%", CSEARCHCOLUMN_SEARCHTYPE_LOOSE, "", "", "", "OnBold");
	$Search->AddColumn("Created", "Created", "13%", CSEARCHCOLUMN_SEARCHTYPE_LOOSE, "", "", "", "OnTimestamp");
	$Search->AddColumn("LSC", "[[ID]]", "14%", CSEARCHCOLUMN_SEARCHTYPE_NOSEARCH, "", "", "", "OnLSC");
	$Search->AddColumn("Contact", "InstitutionsUsersID", "14%", CSEARCHCOLUMN_SEARCHTYPE_LOOSE, "Users", "ID", "LastName", "OnInstitutionContact");
	$Search->AddColumn("Institution", "InstitutionsID", "20%", CSEARCHCOLUMN_SEARCHTYPE_LOOSE, "Institutions", "ID", "Name", "OnInstitution");	
	$Search->AddColumn("Step", "Step", "5%", CSEARCHCOLUMN_SEARCHTYPE_LOOSE);
	$Search->AddColumn("Status", "Status", "20%", CSEARCHCOLUMN_SEARCHTYPE_LOOSE);	
	$Search->AddColumn("&nbsp;", "<div class='Icon_View' onClick=\"CModule.Load('AuditBills', {'ID' : [[ID]]})\"></div>", "16px", CSEARCHCOLUMN_SEARCHTYPE_NOSEARCH);		

	$Search->SetDefaultColumn(1, 1);

	$Search->OnInit();

	$Search->OnRender();
	$Search->OnRenderPages();
?>
