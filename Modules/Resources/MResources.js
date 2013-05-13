//=============================================================================
MResources = {};

//=============================================================================
MResources.AddEdit = function(WindowID, Prefix) {
	if(CForm.Submit("Resources", "Module", "AddEdit", Prefix, function(Code, Content) {
		if(Code > 0) CWindow.Close(WindowID);

		return true;
	}) == false) {
		Resource(CForm.GetLastError());

		return false;
	}

	return true;
}

//-----------------------------------------------------------------------------
MResources.Delete = function(WindowID, Prefix) {
	if(CForm.Submit("Resources", "Module", "Delete", Prefix, function(Code, Content) {
		if(Code > 0) CWindow.Close(WindowID);

		return true;
	}) == false) {
		Resource(CForm.GetLastError());

		return false;
	}

	return true;
}

//=============================================================================
MResources.Window_AddEdit = function(ID) {
	var Title = "Add Resource";

	if(ID > 0) {
		Title = "Edit Resource";
	}

	CWindow.Open(Title, 350, 200, "Resources", "Module", "Window_AddEdit", {"ID" : ID});
}

//-----------------------------------------------------------------------------
MResources.Window_Delete = function(ID) {
	CWindow.Open("Delete Resource", 300, 180, "Resources", "Module", "Window_Delete", {"ID" : ID});
}

//=============================================================================
