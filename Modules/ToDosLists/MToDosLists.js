//=============================================================================
MToDosLists = {};

//=============================================================================
MToDosLists.AddEdit = function(WindowID, Prefix) {
	if(CForm.Submit("ToDosLists", "Module", "AddEdit", Prefix, function(Code, Content) {
		if(Code > 0) CWindow.Close(WindowID);

		return true;
	}) == false) {
		alert(CForm.GetLastError());

		return false;
	}

	return true;
}

//-----------------------------------------------------------------------------
MToDosLists.Delete = function(WindowID, Prefix) {
	if(CForm.Submit("ToDosLists", "Module", "Delete", Prefix, function(Code, Content) {
		if(Code > 0) CWindow.Close(WindowID);

		return true;
	}) == false) {
		alert(CForm.GetLastError());

		return false;
	}

	return true;
}

//=============================================================================
MToDosLists.Window_AddEdit = function(ID) {
	var Title = "Add To Do List";

	if(ID > 0) {
		Title = "Edit To Do List";
	}

	CWindow.Open(Title, 500, 300, "ToDosLists", "Module", "Window_AddEdit", {"ID" : ID});
}

//-----------------------------------------------------------------------------
MToDosLists.Window_Delete = function(ID) {
	CWindow.Open("Delete To Do List", 300, 150, "ToDosLists", "Module", "Window_Delete", {"ID" : ID});
}

//=============================================================================
MToDosLists.Save = function(ID) {
	var Parms		= {};
	Parms["ID"]		= ID;
	if(ID == 0) {
		Parms["Name"]		= $('#AddName').val();
		Parms["Members"]	= $('#AddMembers').select2("val");
	} else {
		Parms["Name"]		= $('#Edit' + ID + 'Name').val();
		Parms["Members"]	= $('#Edit' + ID + 'Members').select2("val");
		Parms["Active"]		= $('#Edit' + ID + 'Active').val();
	}
	
	CAJAX.Add("ToDosLists", "Module", "AddEdit", Parms, function(Code, Content) {
		if(Code == 0) {
			alert(Content);
		}else{
			if(ID == 0) {
				$('#AddName').val("");
				$('#AddMembers').select2("val", "");
			}
			$(document.body).scrollTo(0, 800);

			//$("#ID").val(Code);
		}

		return true;
	});

	return true;
}

//=============================================================================
