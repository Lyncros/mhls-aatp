<?
	$Search = new CSearch("BannedIPs");

	$Search->AddColumn("IP", "IP", "100%", CSEARCHCOLUMN_SEARCHTYPE_LOOSE);

	if(CSecurity::CanAccess($this->Parent->Name, "AddEdit")) {
		$Search->AddColumn("<div class='Icon_Edit' style='display: none;'></div>", "<div class='Icon_Edit' onClick=\"MBannedIPs.Window_AddEdit([[ID]]);\"></div>", "22px");
	}

	if(CSecurity::CanAccess($this->Parent->Name, "Delete")) {
		$Search->AddColumn("<div class='Icon_Delete' style='display: none;'></div>", "<div class='Icon_Delete' onClick=\"MBannedIPs.Window_Delete([[ID]]);\"></div>", "22px");
	}

	$Search->AddRestriction("BusinessesID", CSecurity::GetBusinessesID());
	$Search->SetDefaultColumn(0); //Name

	$Search->OnInit();

	$Search->OnRenderTitle("Banned IPs", Array("Export" => true, "MaxView" => true));
	$Search->OnRender();
	$Search->OnRenderPages();
?>
