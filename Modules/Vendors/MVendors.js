//=============================================================================
MVendors = {};

//=============================================================================
MVendors.AddEdit = function(WindowID, Prefix) {
	if(CForm.Submit("Vendors", "Module", "AddEdit", Prefix, function(Code, Content) {
		if(Code > 0) CWindow.Close(WindowID);
		
		return true;
	}) == false) {
		alert(CForm.GetLastError());

		return false;
	}

	return true;
}

//-----------------------------------------------------------------------------
MVendors.Delete = function(WindowID, Prefix) {
	if(CForm.Submit("Vendors", "Module", "Delete", Prefix, function(Code, Content) {
		if(Code > 0) CWindow.Close(WindowID);

		return true;
	}) == false) {
		alert(CForm.GetLastError());

		return false;
	}

	return true;
}

//=============================================================================
MVendors.Window_AddEdit = function(ID) {
	var Title = "Add Vendor";

	if(ID > 0) {
		Title = "Edit Vendor";
	}
	
	CWindow.Open(Title, 500, 300, "Vendors", "Module", "Window_AddEdit", {"ID" : ID});
}

//-----------------------------------------------------------------------------
MVendors.Window_Delete = function(ID) {
	CWindow.Open("Delete Vendor", 300, 150, "Vendors", "Module", "Window_Delete", {"ID" : ID});
}

//=============================================================================
MVendors.Save = function() {
	if(CForm.Submit("Vendors", "Module", "Save", "", function(Code, Content) {
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
