//=============================================================================
MResourcesCategories = {};

//=============================================================================
MResourcesCategories.AddEdit = function(WindowID, Prefix) {
	if(CForm.Submit("ResourcesCategories", "Module", "AddEdit", Prefix, function(Code, Content) {
		if(Code > 0) CWindow.Close(WindowID);

		return true;
	}) == false) {
		alert(CForm.GetLastError());

		return false;
	}

	return true;
}

//-----------------------------------------------------------------------------
MResourcesCategories.Delete = function(WindowID, Prefix) {
	if(CForm.Submit("ResourcesCategories", "Module", "Delete", Prefix, function(Code, Content) {
		if(Code > 0) CWindow.Close(WindowID);

		return true;
	}) == false) {
		alert(CForm.GetLastError());

		return false;
	}

	return true;
}

//=============================================================================
MResourcesCategories.Window_AddEdit = function(ID) {
	var Title = "Add Resource Category";

	if(ID > 0) {
		Title = "Edit Resource Category";
	}

	CWindow.Open(Title, 500, 300, "ResourcesCategories", "Module", "Window_AddEdit", {"ID" : ID});
}

//-----------------------------------------------------------------------------
MResourcesCategories.Window_Delete = function(ID) {
	CWindow.Open("Delete Resource Category", 300, 150, "ResourcesCategories", "Module", "Window_Delete", {"ID" : ID});
}

//=============================================================================
MResourcesCategories.Save = function() {
	if(CForm.Submit("ResourcesCategories", "Module", "Save", "", function(Code, Content) {
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
