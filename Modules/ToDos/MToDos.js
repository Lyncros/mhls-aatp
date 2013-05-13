//=============================================================================
MToDos = {};

//=============================================================================
MToDos.AddEdit = function(WindowID, Prefix) {
	if(CForm.Submit("ToDos", "Module", "AddEdit", Prefix, function(Code, Content) {
		if(Code > 0) CWindow.Close(WindowID);

		return true;
	}) == false) {
		alert(CForm.GetLastError());

		return false;
	}

	return true;
}

//-----------------------------------------------------------------------------
MToDos.Delete = function(WindowID, Prefix) {
	if(CForm.Submit("ToDos", "Module", "Delete", Prefix, function(Code, Content) {
		if(Code > 0) CWindow.Close(WindowID);

		return true;
	}) == false) {
		alert(CForm.GetLastError());

		return false;
	}

	return true;
}

//=============================================================================
MToDos.Window_AddEdit = function(ID) {
	var Title = "Add To Do";

	if(ID > 0) {
		Title = "Edit To Do";
	}

	CWindow.Open(Title, 500, 300, "ToDos", "Module", "Window_AddEdit", {"ID" : ID});
}

//-----------------------------------------------------------------------------
MToDos.Window_Delete = function(ID) {
	CWindow.Open("Delete To Do", 300, 150, "ToDos", "Module", "Window_Delete", {"ID" : ID});
}

//=============================================================================
MToDos.Save = function() {
	if(CForm.Submit("ToDos", "Module", "Save", "", function(Code, Content) {
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
