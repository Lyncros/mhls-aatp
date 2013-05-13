//=============================================================================
MMilestones = {};

//=============================================================================
MMilestones.AddEdit = function(WindowID, Prefix) {
	if(CForm.Submit("Milestones", "Module", "AddEdit", Prefix, function(Code, Content) {
		if(Code > 0) CWindow.Close(WindowID);

		return true;
	}) == false) {
		alert(CForm.GetLastError());

		return false;
	}

	return true;
}

//-----------------------------------------------------------------------------
MMilestones.Delete = function(WindowID, Prefix) {
	if(CForm.Submit("Milestones", "Module", "Delete", Prefix, function(Code, Content) {
		if(Code > 0) CWindow.Close(WindowID);

		return true;
	}) == false) {
		alert(CForm.GetLastError());

		return false;
	}

	return true;
}

//=============================================================================
MMilestones.Window_AddEdit = function(ID) {
	var Title = "Add Milestone";

	if(ID > 0) {
		Title = "Edit Milestone";
	}

	CWindow.Open(Title, 500, 300, "Milestones", "Module", "Window_AddEdit", {"ID" : ID});
}

//-----------------------------------------------------------------------------
MMilestones.Window_Delete = function(ID) {
	CWindow.Open("Delete Milestone", 300, 150, "Milestones", "Module", "Window_Delete", {"ID" : ID});
}

//=============================================================================
MMilestones.Save = function() {
	if(CForm.Submit("Milestones", "Module", "Save", "", function(Code, Content) {
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
