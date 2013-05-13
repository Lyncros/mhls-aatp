//=============================================================================
CWindow = {};

//=============================================================================
CWindow.Count = 0;

CWindow.WinList = Array();

CWindow.MinimizedCount = 0;

//=============================================================================
CWindow.New = function(Title, Content, Width, Height) {
	var WinID = "CWindow_" + CWindow.Count;

	$("#CWindow_Area").append("<div id='" + WinID + "' title='" + Title + "'><div class='CWindow_Minimize' id='" + WinID + "_Minimize'></div>" + Content + "</div>");

	$("#" + WinID).dialog({
		autoOpen: false,
		width: Width,
		height: Height,
		show: "slide"
	});

	$("#" + WinID).dialog("open");

	$("#CWindow_" + CWindow.Count + "_Tab_Area").tabs({fx: {opacity: "toggle"} });

	var NewTitle = $("#" + WinID + "_NewTitle").attr("value");

	if(NewTitle) {
		$("#ui-dialog-title-" + WinID).html(NewTitle);
		Title = NewTitle;
	}

	$("#" + WinID + "_Minimize").bind("click", function() {
		CWindow.Minimize(WinID, Title, Content, Width, Height, -1);
	});

	CWindow.Count++;
}

//-----------------------------------------------------------------------------
CWindow.Open = function(Title, Width, Height, Request, Type, Action, Parms, Callback) {
	function OnDone(Code, Content) {
		if(Code != 0) {
			CWindow.New(Title, Content, Width, Height);
		}else{
			try {
				PConsole.AddNotice("CWindow.Open :: " + Request + " :: Unable to open Window : " + Content, "Error");
			}
			catch(e) {}
		}

		if(Callback) {
			Callback(Code, Content);
		}
	}

	Parms["CWindow_ID"] = CWindow.Count;

	CAJAX.Add(Request, Type, Action, Parms, OnDone);
}

//-----------------------------------------------------------------------------
CWindow.Minimize = function(WindowID, Title, Content, Width, Height, SaveWindowID) {
	if(WindowID == "") {
		CWindow.MinimizeCreate(WindowID, Title, Content, Width, Height, SaveWindowID);
	}else{
		var Parms = {
			"WindowID"	: SaveWindowID,
			"Title"		: Title,
			"Content"	: Content,
			"Width"		: Width,
			"Height"	: Height
		};

		CAJAX.Add("CWindow", "System", "SaveWindow", Parms, function(Code, SaveWindowID) {
			if(Code == 1) {
				CWindow.MinimizeCreate(WindowID, Title, Content, Width, Height, SaveWindowID);
			}else{
				alert(SaveWindowID);
			}
		});
	}
}

//-----------------------------------------------------------------------------
CWindow.MinimizeCreate = function(WindowID, Title, Content, Width, Height, SaveWindowID) {
	if(WindowID) $("#" + WindowID).dialog("close");

	$("#CTray_MinimizedWindowArea").append("<div class='CTray_MinimizedWindow' id='CTray_Window_" + CWindow.MinimizedCount + "'>" + Title + "</div>");

	if(WindowID) {
		$("#CTray_Window_" + CWindow.MinimizedCount).bind("click", function() {
			$("#" + WindowID).dialog("open");

			$(this).remove();

			CAJAX.Add("CWindow", "System", "UnsaveWindow", {"WindowID" : SaveWindowID}, function(Code2, Content2) {});
		});
	}else{
		$("#CTray_Window_" + CWindow.MinimizedCount).bind("click", function() {
			CWindow.New(Title, htmlspecialchars_decode(Content), Width, Height);

			$(this).remove();

			CAJAX.Add("CWindow", "System", "UnsaveWindow", {"WindowID" : SaveWindowID}, function(Code2, Content2) {});
		});
	}			

	CWindow.MinimizedCount++;
}

//-----------------------------------------------------------------------------
CWindow.Close = function(ID) {
	$("#CWindow_" + ID).dialog("close");
}

//=============================================================================
CWindow.ArrangeWindows = function(TopElement) {
	var zIndex = 100;

	for(var i = 0;i < CWindow.WinList.length;i++) {
		if(CWindow.WinList[i] == TopElement) {
			CWindow.WinList.splice(i, 1);
			break;
		}
	}

	CWindow.WinList = XArray.Push_Front(CWindow.WinList, TopElement);

	for(var i = CWindow.WinList.length - 1;i >= 0;i--) {
		var Element = CWindow.WinList[i];

		if(Element.style.display == "none") continue;

		Element.style.zIndex = zIndex;

		zIndex++;
	}
}

//=============================================================================
