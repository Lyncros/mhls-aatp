//=============================================================================
MBannedIPs = {};

//=============================================================================
MBannedIPs.AddEdit = function(WindowID, Prefix) {
	if(CForm.Submit("BannedIPs", "Module", "AddEdit", Prefix, function(Code, Content) {
		if(Code > 0) CWindow.Close(WindowID);

		return true;
	}) == false) {
		alert(CForm.GetLastError());

		return false;
	}

	return true;
}

//-----------------------------------------------------------------------------
MBannedIPs.Delete = function(WindowID, Prefix) {
	if(CForm.Submit("BannedIPs", "Module", "Delete", Prefix, function(Code, Content) {
		if(Code > 0) CWindow.Close(WindowID);

		return true;
	}) == false) {
		alert(CForm.GetLastError());

		return false;
	}

	return true;
}

//=============================================================================
MBannedIPs.Window_AddEdit = function(ID) {
	var Title = "Add Banned IP";

	if(ID > 0) {
		Title = "Edit Banned IP";
	}

	CWindow.Open(Title, 350, 280, "BannedIPs", "Module", "Window_AddEdit", {"ID" : ID});
}

//-----------------------------------------------------------------------------
MBannedIPs.Window_Delete = function(ID) {
	CWindow.Open("Delete Banned IP", 300, 150, "BannedIPs", "Module", "Window_Delete", {"ID" : ID});
}

//=============================================================================
