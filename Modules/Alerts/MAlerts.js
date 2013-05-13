//=============================================================================
MAlerts = {};

//=============================================================================
MAlerts.AddEdit = function(WindowID, Prefix) {
	if(CForm.Submit("Alerts", "Module", "AddEdit", Prefix, function(Code, Content) {
		if(Code > 0) CWindow.Close(WindowID);

		return true;
	}) == false) {
		alert(CForm.GetLastError());

		return false;
	}

	return true;
}

//-----------------------------------------------------------------------------
MAlerts.Delete = function(WindowID, Prefix) {
	if(CForm.Submit("Alerts", "Module", "Delete", Prefix, function(Code, Content) {
		if(Code > 0) CWindow.Close(WindowID);

		return true;
	}) == false) {
		alert(CForm.GetLastError());

		return false;
	}

	return true;
}

//=============================================================================
MAlerts.Window_AddEdit = function(ID) {
	var Title = "Add Alert";

	if(ID > 0) {
		Title = "Edit Alert";
	}

	CWindow.Open(Title, 600, 500, "Alerts", "Module", "Window_AddEdit", {"ID" : ID});
}

//-----------------------------------------------------------------------------
MAlerts.Window_Delete = function(ID) {
	CWindow.Open("Delete User", 300, 180, "Alerts", "Module", "Window_Delete", {"ID" : ID});
}

//=============================================================================
MAlerts.OnInit = function() {
	if(window.location.hash != "") {
		setTimeout(function() {
			$(window.location.hash).data("BGColor", $(window.location.hash).css("background-color"));

			$(window.location.hash).animate({"backgroundColor" : "#C2EFB4"}, 250, "linear", function() {
				setTimeout(function() {
					$(window.location.hash).animate({"backgroundColor" : $(window.location.hash).data("BGColor")}, 4000);
				}, 500);
			});
		}, 500);
	}

	$("[rel=AlertRow]").bind("click", function(e) {
		if(e.target.id == "") return;

		MAlerts.SetRead($(this).attr("id"), 1);
	});
}

//=============================================================================
MAlerts.SetRead = function(ID, Read) {
	if(Read == 0) {
		$("#" + ID).find("a[rel=Read]").fadeOut("fast");
		$("#" + ID).animate({"backgroundColor" : "#FFFFFF"}, 500);
	}else{
		$("#" + ID).find("a[rel=Read]").fadeIn("fast");
		$("#" + ID).animate({"backgroundColor" : "#EEEEEE"}, 500);
	}

	ID = ID.replace(/Alert/g, '');

	CAJAX.Add("Alerts", "Module", "SetRead", {"ID" : ID, "Read" : Read}, function(Code, Content) {
		if(Code <= 0) alert(Content);
	});
}

//=============================================================================
MAlerts.ToggleHide = function(ID) {
	var Hidden		= 0;

	var Current		= $("#" + ID).find("a[rel=Hide]").html();
	var ShowHidden	= parseInt($("#ShowHidden").val());

	if(Current == "Hide") {
		if(ShowHidden) {
			$("#" + ID).find("a[rel=Hide]").html("Unhide");
			$("#" + ID).find(".AlertsCellHiddenTag").fadeIn("fast");
		}else{
			$("#" + ID).parent().fadeOut("fast");
		}

		Hidden = 1;
	}else{
		$("#" + ID).find("a[rel=Hide]").html("Hide");
		$("#" + ID).find(".AlertsCellHiddenTag").fadeOut("fast");
	}

	ID = ID.replace(/Alert/g, '');

	CAJAX.Add("Alerts", "Module", "SetHidden", {"ID" : ID, "Hidden" : Hidden}, function(Code, Content) {
		if(Code <= 0) alert(Content);
	});
}

//=============================================================================
MAlerts.WatchGroups = function(Prefix) {
	$("#" + Prefix + "AvailableGroups > .AvailableGroupsItem").bind("click", function() {
		var HasItem		= false;

		var GroupID		= $(this).attr("rel");
		var GroupHTML	= $(this).html();

		$("#" + Prefix + "SelectedGroups > .SelectedGroupsItem").each(function() {
			if($(this).attr("rel") == GroupID) HasItem = true;
		});

		if(HasItem == false) {
			var Item = $("<div class='SelectedGroupsItem' rel='" + GroupID + "'>" + GroupHTML + "</div>").appendTo("#" + Prefix + "SelectedGroups");

			$(Item).bind("click", function() {
				if(confirm("Are you sure you want to remove this Group?") == false) return;

				$(this).remove();
				MAlerts.UpdateGroupList(Prefix);
			});
		}

		MAlerts.UpdateGroupList(Prefix);
	});
}

//-----------------------------------------------------------------------------
MAlerts.UpdateGroupList = function(Prefix) {
	var GroupList = [];

	$("#" + Prefix + "SelectedGroups > .SelectedGroupsItem").each(function() {
		GroupList.push($(this).attr("rel"));
	});

	$("#" + Prefix + "GroupList").val(JSON.stringify(GroupList));
}

//=============================================================================
$(MAlerts.OnInit);

//=============================================================================
