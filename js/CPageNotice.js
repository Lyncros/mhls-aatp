//=============================================================================
CPageNotice = {};

//=============================================================================
CPageNotice.TimerHandler = null;

//=============================================================================
CPageNotice.Add = function(Type, Content) {
	$("#CPageNotice").removeClass("CPageNoticeSuccess");
	$("#CPageNotice").removeClass("CPageNoticeError");

	$("#CPageNotice").addClass("CPageNotice" + Type);

	$("#CPageNotice").html(Content);

	$("#CPageNotice").slideUp(500);
	$("#CPageNotice").slideDown(500);

	if(CPageNotice.TimerHandler) clearTimeout(CPageNotice.TimerHandler);

	CPageNotice.TimerHandler = setTimeout(function() {
		$("#CPageNotice").slideUp(500);
	}, 10000);
}

//=============================================================================
