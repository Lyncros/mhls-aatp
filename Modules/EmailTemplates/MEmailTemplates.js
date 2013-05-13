//=============================================================================
MEmailTemplates = {};

//=============================================================================
MEmailTemplates.AddEdit = function(WindowID, Prefix) {
	if(CForm.Submit("EmailTemplates", "Module", "AddEdit", Prefix, function(Code, Content) {
		if(Code > 0) CWindow.Close(WindowID);

		return true;
	}) == false) {
		alert(CForm.GetLastError());

		return false;
	}

	return true;
}

//-----------------------------------------------------------------------------
MEmailTemplates.Delete = function(WindowID, Prefix) {
	if(CForm.Submit("EmailTemplates", "Module", "Delete", Prefix, function(Code, Content) {
		if(Code > 0) CWindow.Close(WindowID);

		return true;
	}) == false) {
		alert(CForm.GetLastError());

		return false;
	}

	return true;
}

//=============================================================================
MEmailTemplates.Window_AddEdit = function(ID) {
	var Title = "Add Email Template";

	if(ID > 0) {
		Title = "Edit Email Template";
	}

	CWindow.Open(Title, 800, 500, "EmailTemplates", "Module", "Window_AddEdit", {"ID" : ID});
}

//-----------------------------------------------------------------------------
MEmailTemplates.Window_Delete = function(ID) {
	CWindow.Open("Delete Email Template", 300, 170, "EmailTemplates", "Module", "Window_Delete", {"ID" : ID});
}

//=============================================================================
