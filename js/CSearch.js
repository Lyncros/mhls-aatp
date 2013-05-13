//=============================================================================
CSearch = {};

//=============================================================================
CSearch.AltDown		= false;

CSearch.Element		= null;
CSearch.ElementText = null;

//=============================================================================
CSearch.OnInit = function() {
	$(document).bind('keydown', 'ctrl+`', CSearch.ToggleBox);

	var Content = "<div id='CSearch_Box'><input type='text' class='CSearch_Box_Textbox' id='CSearch_Box_Textbox' name='CSearch_Keywords'/></div>";

	$("body").append(Content);

	CSearch.Element = $("#CSearch_Box").get(0);

	CSearch.Element.className = "CSearch_Box";

	CSearch.Element.style.left = (($(document.window).width() / 2) - ($(CSearch.Element).width() / 2)) + "px";
	CSearch.Element.style.top  = (($(document.window).height() / 2) - ($(CSearch.Element).height() / 2)) + "px";

	CSearch.Element.style.display = "none";

	CSearch.ElementText = $("#CSearch_Box_Textbox").get(0);

	$(CSearch.Element).bind("keydown", "return", CSearch.Go);
}

//-----------------------------------------------------------------------------
CSearch.Go = function() {	
	CURL.Redirect(CURL.FormatURL("", {"CSearch_Keywords" : $("#CSearch_Box_Textbox").attr("value")}, true, true));
}

//=============================================================================
CSearch.ToggleBox = function() {
	$("#CSearch_Box").toggle("normal", function() {
		if($("#CSearch_Box").css("display") == "none") {
			$("#CSearch_Box_Textbox").blur();
		}else{
			$("#CSearch_Box_Textbox").focus();
		}
	});
}

//=============================================================================
$(CSearch.OnInit);

//=============================================================================
