//=============================================================================
CMenu = {};

//=============================================================================
CMenu.Show = function(ID) {
	CMenu.Hide();

	$(".CMenu_Item_Over").attr("class", "CMenu_Item");

	$("#" + ID).attr("class", "CMenu_Item_Over");

	$("#" + ID + "_Children").show("fast");
}

//-----------------------------------------------------------------------------
CMenu.Hide = function() {
	$(".CMenu_Item_Children").hide("fast");
}

//=============================================================================