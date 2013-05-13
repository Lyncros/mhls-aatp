//=============================================================================
MInstitutions = {};

//=============================================================================
MInstitutions.AddEdit = function(WindowID, Prefix) {
	if(CForm.Submit("Institutions", "Module", "AddEdit", Prefix, function(Code, Content) {
		if(Code > 0) CWindow.Close(WindowID);

		return true;
	}) == false) {
		alert(CForm.GetLastError());

		return false;
	}

	return true;
}

//-----------------------------------------------------------------------------
MInstitutions.Delete = function(WindowID, Prefix) {
	if(CForm.Submit("Institutions", "Module", "Delete", Prefix, function(Code, Content) {
		if(Code > 0) CWindow.Close(WindowID);

		return true;
	}) == false) {
		alert(CForm.GetLastError());

		return false;
	}

	return true;
}

//=============================================================================
MInstitutions.Window_AddEdit = function(ID) {
	var Title = "Add Institution";

	if(ID > 0) {
		Title = "Edit Institution";
	}

	CWindow.Open(Title, 500, 300, "Institutions", "Module", "Window_AddEdit", {"ID" : ID});
}

//-----------------------------------------------------------------------------
MInstitutions.Window_Delete = function(ID) {
	CWindow.Open("Delete Institution", 300, 150, "Institutions", "Module", "Window_Delete", {"ID" : ID});
}

//=============================================================================
MInstitutions.Save = function() {
	if(CForm.Submit("Institutions", "Module", "Save", "", function(Code, Content) {
		if(Code == 0) {
			//alert(Content);
		}else{
			$(document.body).scrollTo(0, 800);

			$("#ID").val(Code);
		}

		return true;
	}) == false) {
		alert(CForm.GetLastError());

		return false;
	}

	return true;
}

//=============================================================================
