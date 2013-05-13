//=============================================================================
MDocumentTemplates = {};

//=============================================================================
MDocumentTemplates.AddEdit = function(WindowID, Prefix) {
	if(CForm.Submit("DocumentTemplates", "Module", "AddEdit", Prefix, function(Code, Content) {
		if(Code > 0) CWindow.Close(WindowID);

		return true;
	}) == false) {
		alert(CForm.GetLastError());

		return false;
	}

	return true;
}

//-----------------------------------------------------------------------------
MDocumentTemplates.Delete = function(WindowID, Prefix) {
	if(CForm.Submit("DocumentTemplates", "Module", "Delete", Prefix, function(Code, Content) {
		if(Code > 0) CWindow.Close(WindowID);

		return true;
	}) == false) {
		alert(CForm.GetLastError());

		return false;
	}

	return true;
}

//=============================================================================
MDocumentTemplates.Window_AddEdit = function(ID) {
	var Title = "Add Document Template";

	if(ID > 0) {
		Title = "Edit Document Template";
	}

	CWindow.Open(Title, 800, 500, "DocumentTemplates", "Module", "Window_AddEdit", {"ID" : ID});
}

//-----------------------------------------------------------------------------
MDocumentTemplates.Window_Delete = function(ID) {
	CWindow.Open("Delete Document Template", 300, 150, "DocumentTemplates", "Module", "Window_Delete", {"ID" : ID});
}

//=============================================================================
