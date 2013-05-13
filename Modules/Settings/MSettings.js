//=============================================================================
MSettings = {};

//=============================================================================
MSettings.Save = function() {
	if(CForm.Submit("Settings", "Module", "Save", "", function(Code, Content) {
		$(document.body).scrollTo(0, 800);

		return true;
	}) == false) {
		alert(CForm.GetLastError());

		return false;
	}

	return true;
}

//=============================================================================
