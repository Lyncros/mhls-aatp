<?
	$_SESSION["CSearch_Attention_TableList"] = Array();

	function OnTimestamp($Value, $Row) {
		return date("m/d/Y h:ia", $Value);
	}

	function OnContent($Value, $Row) {
		return nl2br($Value);
	}

	function OnView($Value, $Row) {
		if(CSecurity::CanAccess($Row->Table, "AddEdit") == false) return "";

		$_SESSION["CSearch_Attention_TableList"][$Row->Table] = $Row->Table;

		return $Value;
	}

	$Search = new CSearch("Attention");

	$Search->AddColumn("Timestamp", "Timestamp", "15%", CSEARCHCOLUMN_SEARCHTYPE_NOSEARCH, "", "", "", "OnTimestamp");
	$Search->AddColumn("Table", "Table", "15%", CSEARCHCOLUMN_SEARCHTYPE_EXACT);
	$Search->AddColumn("Item ID", "ItemID", "10%", CSEARCHCOLUMN_SEARCHTYPE_LOOSE);
	$Search->AddColumn("Content", "[[Content]]", "75%", CSEARCHCOLUMN_SEARCHTYPE_LOOSE, "", "", "", "OnContent");

	$Search->AddRestriction("BusinessesID", CSecurity::GetBusinessesID());

	$Search->AddColumn("<div class='Icon_Edit' style='display: none;'></div>", "<div class='Icon_Edit' onClick=\"M[[Table]].Window_AddEdit([[ItemID]]);\"></div>", "22px", CSEARCHCOLUMN_SEARCHTYPE_NOSEARCH, "", "", "", "OnView");

	if(CSecurity::CanAccess($this->Parent->Name, "Delete")) {
		$Search->AddColumn("<div class='Icon_Delete' style='display: none;'></div>", "<div class='Icon_Delete' onClick=\"MAttention.Window_Delete([[ID]]);\"></div>", "22px");
	}

	$Search->SetDefaultColumn(0); //Name

	$Search->OnInit();

	$Search->OnRenderTitle("Attention", Array("Export" => true, "MaxView" => true));
	$Search->OnRender();
	$Search->OnRenderPages();

	foreach($_SESSION["CSearch_Attention_TableList"] as $TableName) {
		CFile::LoadExternFile("Module", $TableName, "M".$TableName.".js", CFILE_TYPE_JS);
	}
?>
