//=============================================================================
CBannedIPs = {};

//=============================================================================
CBannedIPs.Ban = function(IP) {
	if(confirm("Are you sure you want to ban this IP?") == false) return;

	var Parms = {
		"IP" : IP
	};

	CAJAX.Add("BannedIPs", "Module", "Ban", Parms, null);
}

//=============================================================================
CBannedIPs.Unban = function(IP) {
	if(confirm("Are you sure you want to ban this IP?") == false) return;

	var Parms = {
		"IP" : IP
	};

	CAJAX.Add("BannedIPs", "Module", "Unban", Parms, null);
}

//=============================================================================
