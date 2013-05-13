//=============================================================================
MProductSolutions = {};

//=============================================================================
MProductSolutions.AddEdit = function(WindowID, Prefix) {
	if(CForm.Submit("ProductSolutions", "Module", "AddEdit", Prefix, function(Code, Content) {
		if(Code > 0) CWindow.Close(WindowID);

		return true;
	}) == false) {
		alert(CForm.GetLastError());

		return false;
	}

	return true;
}

//-----------------------------------------------------------------------------
MProductSolutions.Delete = function(WindowID, Prefix) {
	if(CForm.Submit("ProductSolutions", "Module", "Delete", Prefix, function(Code, Content) {
		if(Code > 0) CWindow.Close(WindowID);

		return true;
	}) == false) {
		alert(CForm.GetLastError());

		return false;
	}

	return true;
}

//=============================================================================
MProductSolutions.Window_AddEdit = function(ID) {
	var Title = "Add Product Solution";

	if(ID > 0) {
		Title = "Edit Product Solution";
	}

	CWindow.Open(Title, 500, 300, "ProductSolutions", "Module", "Window_AddEdit", {"ID" : ID});
}

//-----------------------------------------------------------------------------
MProductSolutions.Window_Delete = function(ID) {
	CWindow.Open("Delete Product Solution", 300, 150, "ProductSolutions", "Module", "Window_Delete", {"ID" : ID});
}

//=============================================================================
