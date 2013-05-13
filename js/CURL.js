//=============================================================================
CURL = {};

//=============================================================================
CURL.GetBasePage = function() {
	var Page		= "";
	var PathParts	= window.location.pathname.split("/");

	Page = PathParts[PathParts.length - 1];

	return Page;
}

//-----------------------------------------------------------------------------
CURL.GetLocation = function() {
	var Path		= "";
	var PathParts	= window.location.pathname.split("/");

	for(var i = 0;i < PathParts.length - 1;i++) {
		Path += PathParts[i] + "/";
	}

	return window.location.protocol + "//" + window.location.host + Path;
}

//-----------------------------------------------------------------------------
CURL.FormatURL = function(URL, Parms, UseGetParms, UseBasePage) {
	var ParmString = "?";

	if(UseGetParms) {
		var CurrentParms = CURL.GetCurrentParms();

		for(var Key in CurrentParms) {
			var Skip = false;

			for(var Key2 in Parms) {
				if(Key == Key2) Skip = true;
			}

			if(Skip) continue;

			var Value = CurrentParms[Key];

			ParmString += Key + "=" + encodeURIComponent(Value) + "&";
		}
	}

	for(var Key in Parms) {
		var Value = Parms[Key];

		if(Value == undefined) Value = "";

		ParmString += Key + "=" + encodeURIComponent(Value) + "&";
	}

	if(URL.length <= 0 || URL == "") {
		URL = CURL.GetLocation();
	}

	if(ParmString == "?") ParmString = "";

	if(UseBasePage) {
		return URL + CURL.GetBasePage() + ParmString;
	}

	return URL + ParmString;
}

//-----------------------------------------------------------------------------
CURL.GetCurrentParms = function() {
	var URLParts = window.location.href.split("?");

	if(URLParts.length <= 1) return {};

	var Parms = URLParts[1].split("&");

	var ParmObject = {};

	for(var i in Parms) {
		var ParmParts = Parms[i].split("=");

		if(ParmParts[0].length <= 0) continue;

		ParmObject[ParmParts[0]] = ParmParts[1];
	}

	return ParmObject;
}

//-----------------------------------------------------------------------------
CURL.Redirect = function(URL) {
	window.location.href = URL;
}

//=============================================================================
