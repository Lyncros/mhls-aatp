//=============================================================================
CTooltip = {};

//=============================================================================
CTooltip.Show = function(Event, Content) {
	if(Content == undefined || Content.length <= 0) return;

	var Offset = 15;

	X = Event.pageX + Offset;
	Y = Event.pageY + Offset;

	if($("#CTooltip").get(0) == null) {
		$(document.body).append("<div id='CTooltip' class='CTooltip'>" + Content + "</div>");
	}else{
		$("#CTooltip").show().html(Content);
	}

	$("#CTooltip").css("left", X + "px");
	$("#CTooltip").css("top", Y + "px");
}

//-----------------------------------------------------------------------------
CTooltip.Hide = function() {
	$("#CTooltip").hide();
}

//=============================================================================
