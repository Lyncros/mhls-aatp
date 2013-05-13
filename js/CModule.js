//=============================================================================
CModule = {};

//=============================================================================
CModule.Load = function(Name, Parms, Extra) {
	if(Extra == undefined) {
		Extra = "";
	}

	var ParmsString = "";

	if(Parms) {
		ParmsString = "?";

		for(var i in Parms) {
			ParmsString += i + "=" + encodeURIComponent(Parms[i]) + "&";
		}
	}

	document.location.href = "/" + Name + ParmsString + Extra;
}

//=============================================================================
CModule.ToggleHelp = function() {
	if($("#Module_Help").css("display") == "none") {
		$("#Module_Help_Content").html("<br/><br/><br/><br/><b>Loading...</b>");

		CAJAX.Add("Help", "Module", "GetContent", {"Page" : CURL.GetBasePage()}, function(Code, Content) {
			if(Code == 0) {
				$("#Module_Help_Content").html("<br/><br/><br/><br/><center><b>Help Information does not exist for this page. If you require immediate assistance, please visit the <a href='/Support'>Support</a> page.</b></center>");
			}else{
				//$("#Module_Help_Edit").attr("onclick", "MHelp.Window_AddEdit(" + Code + ", '" + CURL.GetBasePage() + "');");

				$("#Module_Help_Content").html(Content);
			}
		});

		$("#Module_Help").css("height", "0px").css("display", "block").animate({
			"height" : "200px"
		});
	}else{
		$("#Module_Help").animate({
			"height" : "0px"
		}, function() {
			$("#Module_Help").css("display", "none");
		});
	}

	//$("#Module_Help").toggle("slow");
}

//=============================================================================
CModule.SaveSettings = function(Prefix) {
	CForm.Submit("CModule", "System", "SaveSettings", Prefix, function(Code, Content) {
		CModule.CloseSettings();
		CRefresh.Suggest();
	});
}

//-----------------------------------------------------------------------------
CModule.ToggleSettings = function() {
	$("#Module_Settings").toggle("slow");
}

//-----------------------------------------------------------------------------
CModule.CloseSettings = function() {
	$("#Module_Settings").hide("slow");
}

//=============================================================================
