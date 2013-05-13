<?
	function OnTimestamp($Value) {
		return date("m/d/y h:i a", $Value);
	}

	$Search = new CSearch("Debug");

	$Search->AddColumn("Timestamp", "Timestamp", "15%", CSEARCHCOLUMN_SEARCHTYPE_EXACT, "", "", "", "OnTimestamp");
	$Search->AddColumn("File", "File", "15%", CSEARCHCOLUMN_SEARCHTYPE_LOOSE);
	$Search->AddColumn("Line", "Line", "10%", CSEARCHCOLUMN_SEARCHTYPE_LOOSE);
	$Search->AddColumn("Error", "Error", "60%", CSEARCHCOLUMN_SEARCHTYPE_LOOSE);

	$Search->AddColumn("<div class='Icon_Edit' style='display: none;'></div>", "<div class='Icon_Edit' onClick=\"M[[Table]].Window_AddEdit([[ItemID]]);\"></div>", "22px", CSEARCHCOLUMN_SEARCHTYPE_NOSEARCH, "", "", "", "OnView");

	if(CSecurity::CanAccess($this->Parent->Name, "Delete")) {
		$Search->AddColumn("<div class='Icon_Delete' style='display: none;'></div>", "<div class='Icon_Delete' onClick=\"MDebug.Window_Delete([[ID]]);\"></div>", "22px");
	}

	$Search->SetDefaultColumn(0, 1);

	$Search->OnInit();

	$Options = Array(
		"Export"	=> true, 
		"MaxView"	=> true,
		"Buttons"	=> Array(
			Array(
				"Name"		=> "Clear All",
				"OnClick"	=> "MDebug.ClearAll();"
			)
		)
	);

	$Search->OnRenderTitle("Debug", $Options);
	$Search->OnRender();
	$Search->OnRenderPages();
?>
