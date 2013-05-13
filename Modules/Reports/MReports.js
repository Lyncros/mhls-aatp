//=============================================================================
MReports = {};

//=============================================================================
MReports.AddEdit = function(WindowID, Prefix) {
	if(CForm.Submit("Reports", "Module", "AddEdit", Prefix, function(Code, Content) {
		if(Code > 0) CWindow.Close(WindowID);

		return true;
	}) == false) {
		alert(CForm.GetLastError());

		return false;
	}

	return true;
}

//-----------------------------------------------------------------------------
MReports.Delete = function(WindowID, Prefix) {
	if(CForm.Submit("Reports", "Module", "Delete", Prefix, function(Code, Content) {
		if(Code > 0) CWindow.Close(WindowID);

		return true;
	}) == false) {
		alert(CForm.GetLastError());

		return false;
	}

	return true;
}

//=============================================================================
MReports.Window_AddEdit = function(ID) {
	var Title = "Add Report";

	if(ID > 0) {
		Title = "Edit Report";
	}

	CWindow.Open(Title, 350, 180, "Reports", "Module", "Window_AddEdit", {"ID" : ID});
}

//-----------------------------------------------------------------------------
MReports.Window_Delete = function(ID) {
	CWindow.Open("Delete Report", 300, 150, "Reports", "Module", "Window_Delete", {"ID" : ID});
}

//=============================================================================
MReports.WatchForms = function() {
	$(".Run").bind("click", function() {
		var Parms = {};

		$(this).parent().find("[name]").each(function() {
			Parms[$(this).attr("name")] = $(this).val();
		});

		CModule.Load('Reports', Parms);
	});
}

//=============================================================================
