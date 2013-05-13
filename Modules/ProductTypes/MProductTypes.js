//=============================================================================
MProductTypes = {};

//=============================================================================
MProductTypes.AddEdit = function(WindowID, Prefix) {
	if(CForm.Submit("ProductTypes", "Module", "AddEdit", Prefix, function(Code, Content) {
		if(Code > 0) CWindow.Close(WindowID);

		return true;
	}) == false) {
		alert(CForm.GetLastError());

		return false;
	}

	return true;
}

//-----------------------------------------------------------------------------
MProductTypes.Delete = function(WindowID, Prefix) {
	if(CForm.Submit("ProductTypes", "Module", "Delete", Prefix, function(Code, Content) {
		if(Code > 0) CWindow.Close(WindowID);

		return true;
	}) == false) {
		alert(CForm.GetLastError());

		return false;
	}

	return true;
}

//=============================================================================
MProductTypes.Window_AddEdit = function(ID) {
	var Title = "Add Product Type";

	if(ID > 0) {
		Title = "Edit Product Type";
	}

	CWindow.Open(Title, 500, 300, "ProductTypes", "Module", "Window_AddEdit", {"ID" : ID});
}

//-----------------------------------------------------------------------------
MProductTypes.Window_Delete = function(ID) {
	CWindow.Open("Delete Product Type", 300, 150, "ProductTypes", "Module", "Window_Delete", {"ID" : ID});
}

//=============================================================================
MProductTypes.Save = function() {
	if(CForm.Submit("ProductTypes", "Module", "Save", "", function(Code, Content) {
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
