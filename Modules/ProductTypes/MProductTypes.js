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
MProductTypes.Save = function(ID) {
	var Parms		= {};
	Parms["ID"]		= ID;
	if(ID == 0) {
		Parms["Name"]		= $('#AddName').val();
		Parms["Active"]		= $('#AddActive').val();
		Parms["Milestones"]	= $('#AddMilestones').select2("val");		
	} else {
		Parms["Name"]		= $('#Edit' + ID + 'Name').val();
		Parms["Active"]		= $('#Edit' + ID + 'Active').val();
		Parms["Milestones"]	= $('#Edit' + ID + 'Milestones').select2("val");		
	}
	var success = true;
	CAJAX.Add("ProductTypes", "Module", "AddEdit", Parms, function(Code, Content) {
		if(Code == 0) {
			alert(Content);
			success = false;
			return false;
		}else{
			$(document.body).scrollTo(0, 800);
			
			if(ID == 0) {
				$('#AddName').val("");
				$('#AddActive').val("");				
				$('#AddMilestones').select2("val", "");				
				$('#Add').slideUp();
				location.reload();
			}
			
			return true;			
		}
	});
	
	return success;
}

