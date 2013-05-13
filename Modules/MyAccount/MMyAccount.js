//=============================================================================
MMyAccount = {};

//=============================================================================
MMyAccount.Save = function(Prefix, Button) {
	$(Button).attr("disabled", "disabled");

	if(CForm.Submit("MyAccount", "Module", "Save", Prefix, function(Code, Content) { 
		$(Button).attr("disabled", "");

		if(Code == 2) {
			CModule.Load("Dashboard");
		}else
		if(Code == 1) {
			$(document.body).scrollTo(0, 800);

			CPageNotice.Add("Success", "Your Account information has been saved.");
			CRefresh.Suggest();
		}else{
			alert(Content);
		}
	}) == false) {
		$(Button).attr("disabled", "");
		alert(CForm.GetLastError());

		return false;
	}

	return true;
}

//=============================================================================
