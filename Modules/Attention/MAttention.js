//=============================================================================
MAttention = {};

//=============================================================================
MAttention.AddEdit = function(WindowID, Prefix) {
	if(CForm.Submit("Attention", "Module", "AddEdit", Prefix, function(Code, Content) {
		if(Code == 1) CWindow.Close(WindowID);

		return true;
	}) == false) {
		alert(CForm.GetLastError());

		return false;
	}

	return true;
}

//-----------------------------------------------------------------------------
MAttention.Delete = function(WindowID, Prefix) {
	if(CForm.Submit("Attention", "Module", "Delete", Prefix, function(Code, Content) {
		if(Code == 1) CWindow.Close(WindowID);

		return true;
	}) == false) {
		alert(CForm.GetLastError());

		return false;
	}

	return true;
}

//=============================================================================
MAttention.Window_AddEdit = function(ID) {
	var Title = "Add Attention";

	if(ID > 0) {
		Title = "Edit Attention";
	}

	var Parms = Array();

	Parms["ID"] = ID;

	CWindow.Open(Title, 425, 250, "Attention", "Module", "Window_AddEdit", Parms);
}

//-----------------------------------------------------------------------------
MAttention.Window_Delete = function(ID) {
	var Parms = Array();

	Parms["ID"] = ID;

	CWindow.Open("Delete Attention", 300, 200, "Attention", "Module", "Window_Delete", Parms);
}

//=============================================================================
