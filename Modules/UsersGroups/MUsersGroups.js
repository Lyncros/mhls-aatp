//=============================================================================
MUsersGroups = {};

//=============================================================================
MUsersGroups.AddEdit = function(WindowID, Prefix) {
	if(CForm.Submit("UsersGroups", "Module", "AddEdit", Prefix, function(Code, Content) {
		if(Code > 0) CWindow.Close(WindowID);

		return true;
	}) == false) {
		alert(CForm.GetLastError());

		return false;
	}

	return true;
}

//-----------------------------------------------------------------------------
MUsersGroups.Delete = function(WindowID, Prefix) {
	if(CForm.Submit("UsersGroups", "Module", "Delete", Prefix, function(Code, Content) {
		if(Code > 0) CWindow.Close(WindowID);

		return true;
	}) == false) {
		alert(CForm.GetLastError());

		return false;
	}

	return true;
}

//=============================================================================
MUsersGroups.Window_AddEdit = function(ID) {
	var Title = "Add User Group";

	if(ID > 0) {
		Title = "Edit User Group";
	}

	CWindow.Open(Title, 640, 400, "UsersGroups", "Module", "Window_AddEdit", {"ID" : ID});
}

//-----------------------------------------------------------------------------
MUsersGroups.Window_Delete = function(ID) {
	CWindow.Open("Delete User Group", 300, 160, "UsersGroups", "Module", "Window_Delete", {"ID" : ID});
}

//=============================================================================
MUsersGroups.Save = function() {
	if(CForm.Submit("UsersGroups", "Module", "Save", "", function(Code, Content) {
		$(document.body).scrollTo(0, 800);

		$("#ID").val(Code);

		return true;
	}) == false) {
		alert(CForm.GetLastError());

		return false;
	}

	return true;
}

//=============================================================================
MUsersGroups.AddUser = function(ID, Name) {
	ID = parseInt(ID);

	if(ID <= 0 || isNaN(ID)) return;
	if($(".UserTag[rel=" + ID + "]").get(0) != undefined) return;

	var HTML = "<div class='UserTag' rel='" + ID + "'>" + Name + "</div>";

	var Element = $(HTML).appendTo("#UserListBox");

	$(Element).bind("click", function() {
		if(confirm("Are you sure you want to remove this User?") == false) return;

		$(this).remove();

		MUsersGroups.RebuildUserList();
	});

	MUsersGroups.RebuildUserList();
}

//-----------------------------------------------------------------------------
MUsersGroups.RebuildUserList = function() {
	var UserList = Array();

	$(".UserTag").each(function() {
		UserList.push($(this).attr("rel"));
	});

	$("#UserList").val(JSON.stringify(UserList));
}

//=============================================================================
