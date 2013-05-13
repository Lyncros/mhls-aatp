<?
	function OnPublic($Value, $Row) {
		if($Value == 1) {
			return "<span style='color: green; font-weight: bold;'>Yes</span>";
		}

		return "No";
	}

	$Search = new CSearch("EmailTemplates");

	if(CSecurity::IsSuperAdmin()) {
		$Search->AddColumn("Type", "Type", "25%", CSEARCHCOLUMN_SEARCHTYPE_EXACT);
		$Search->AddColumn("Name", "Name", "25%", CSEARCHCOLUMN_SEARCHTYPE_LOOSE);
		$Search->AddColumn("Sub Name", "SubName", "25%", CSEARCHCOLUMN_SEARCHTYPE_LOOSE);
		$Search->AddColumn("Public", "Public", "25%", CSEARCHCOLUMN_SEARCHTYPE_NOSEARCH, "", "", "", "OnPublic");
	}else{
		$Search->AddColumn("Type", "Type", "33%", CSEARCHCOLUMN_SEARCHTYPE_EXACT);
		$Search->AddColumn("Name", "Name", "34%", CSEARCHCOLUMN_SEARCHTYPE_LOOSE);
		$Search->AddColumn("Sub Name", "SubName", "33%", CSEARCHCOLUMN_SEARCHTYPE_LOOSE);
	}

	$Search->AddRestriction("BusinessesID", CSecurity::GetBusinessesID());

	if(CSecurity::CanAccess($this->Parent->Name, "AddEdit")) {
		$Search->AddColumn("<div class='Icon_Edit' style='display: none;'></div>", "<div class='Icon_Edit' onClick=\"MEmailTemplates.Window_AddEdit([[ID]]);\"></div>", "22px");
	}

	if(CSecurity::CanAccess($this->Parent->Name, "Delete")) {
		$Search->AddColumn("<div class='Icon_Delete' style='display: none;'></div>", "<div class='Icon_Delete' onClick=\"MEmailTemplates.Window_Delete([[ID]]);\"></div>", "22px");
	}

	$Search->SetDefaultColumn(2);

	$Search->OnInit();

	$Search->OnRenderTitle("Email Templates", Array("Export" => true, "MaxView" => true));
	$Search->OnRender();
	$Search->OnRenderPages();

	unset($Search);
?>
