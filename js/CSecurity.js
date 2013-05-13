//=============================================================================
CSecurity = {};

//=============================================================================
CSecurity.Idle = 0;
CSecurity.KeepSessionAlive = 0;

//=============================================================================
CSecurity.OnInit = function() {
	CSecurity.KeepSessionAlive = parseInt($("#CSystem_KeepSessionAlive").attr("value"));

	$(document).mousemove(function(e) {
		CSecurity.Idle = 0;
	});

	setTimeout(CSecurity.OnLoop, 5000);
}

//-----------------------------------------------------------------------------
CSecurity.OnLoop = function() {
	if(CSecurity.KeepSessionAlive) {
		CAJAX.Add("Login", "Module", "IsLoggedIn", {}, function(Code, Content) {});

		setTimeout(CSecurity.OnLoop, 60000); //Every Minute
	}else{
		CSecurity.Idle += 5;

		if(CSecurity.Idle > (60 * 60)) {
			CAJAX.Add("Login", "Module", "Logout", {}, function(Code, Content) {
				CRefresh.Go();
			});
		}

		setTimeout(CSecurity.OnLoop, 5000);
	}
}

//=============================================================================
$(CSecurity.OnInit);

//=============================================================================
