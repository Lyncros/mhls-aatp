//=============================================================================
CLoading = {};

//=============================================================================
CLoading.Toggle = function(Toggle) {
	if(Toggle) {
//		$(document.body).css("cursor", "wait");
	}else{
//		$(document.body).css("cursor", "default");
	}

	if(Toggle) {
		$("#CLoading").fadeIn("fast");
	}else{
		$("#CLoading").fadeOut("fast");
	}
}

//=============================================================================
