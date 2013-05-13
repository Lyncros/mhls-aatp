//=============================================================================
CAJAX = {};

//=============================================================================
CAJAX.DebugMode = false;

//=============================================================================
CAJAX.Queue = Array();

CAJAX.Sending = false;

CAJAX.Count = 0;

//=============================================================================
CAJAX.QueueItem = function() {
	var self		= this;

	self.ID			= "";

	self.Request	= "";
	self.Type		= "";
	self.Action		= "";
	self.Parms		= Array();

	self.Callback	= null;
	self.URI		= "";
};

//=============================================================================
CAJAX.Add = function(Request, Type, Action, Parms, Callback) {
	var Item = new CAJAX.QueueItem();

	Item.ID			= "JHA_Panel_PActions_" + CAJAX.Count;
	Item.Request	= Request;
	Item.Type		= Type;
	Item.Action		= Action;
	Item.Callback	= Callback;
	Item.Parms		= Parms;

	if(CAJAX.DebugMode) {
		try {
			PConsole.AddNotice("CAJAX.Add :: Pushing Item into Queue : (" + Request + ", " + Type + ", " + Action + ")", "Notice");

			for(var i in Parms) {
				PConsole.AddNotice(" - [" + i + "] = " + Parms[i], "Notice");
			}
		}catch(err) {};
	}

	CAJAX.Queue.push(Item);

	CAJAX.Count++;

	if(CAJAX.Sending == false) {
		CAJAX.OnLoop();
	}
}

//-----------------------------------------------------------------------------
CAJAX.OnInit = function() {
	try {
		//Register Debug Command with Console
		function AJAXDebug(Parts) {
			if(Parts[1] == 1) {
				CAJAX.DebugMode = true;

				PConsole.AddNotice("Debugging Turned On", "Notice");
			}else{
				CAJAX.DebugMode = false;

				PConsole.AddNotice("Debugging Turned Off", "Notice");
			}
		}

		PConsole.AddCommand("CAJAXDebug", AJAXDebug, "Turn on Debugging Mode");
	}catch(err) {};

	/*window.onbeforeunload = function(e) {
		if(CAJAX.Sending) {
			return "You have 1 or more requests running. If you continue, you may lose important data.";
		}

		return;
	};*/

	setTimeout(CAJAX.OnLoop, 500);
}

//-----------------------------------------------------------------------------
CAJAX.OnLoop = function() {
	if(CAJAX.Queue.length <= 0) {
		CAJAX.Sending = false;

		CLoading.Toggle(false);
		return;
	}

	if(CAJAX.Sending) {
		setTimeout(CAJAX.OnLoop, 1000);
		return;
	}

	CLoading.Toggle(true);

	CAJAX.Sending = true;
	
	var ID			= CAJAX.Queue[0].ID;
	var Request		= CAJAX.Queue[0].Request;
	var Type		= CAJAX.Queue[0].Type;
	var Action		= CAJAX.Queue[0].Action;
	var Parms		= CAJAX.Queue[0].Parms;

	var ParmsString = "";

	for(var i in Parms) {
		if(is_array(Parms[i]) || is_object(Parms[i])) {
			ParmsString += "&" + i + "=" + encodeURIComponent(JSON.stringify(Parms[i]));
		}else{
			ParmsString += "&" + i + "=" + encodeURIComponent(Parms[i]);
		}
	}

	var URI = "AJAX_Request=" + encodeURIComponent(Request) + "&AJAX_Type=" + encodeURIComponent(Type) + "&AJAX_Action=" + encodeURIComponent(Action) + ParmsString;

	if(CAJAX.DebugMode) {
		try {
			PConsole.AddNotice("CAJAX.OnLoop :: Starting AJAX Request", "Notice");
			PConsole.AddNotice(" - " + URI, "Notice");
		}catch(err) {};
	}

	$.ajax({
		async:		true,
		type:		"POST",
		url:		"/AJAX.php",
		data:		URI,
		dataType:	"text",

		success:	CAJAX.OnSuccess,
		error:		CAJAX.OnError
	});

	CAJAX.Queue[0].URI = URI;

	setTimeout(CAJAX.OnLoop, 1000);
}

//-----------------------------------------------------------------------------
CAJAX.OnSuccess = function(Data, TextStatus) {
	var ID			= CAJAX.Queue[0].ID;
	var Request		= CAJAX.Queue[0].Request;
	var Type		= CAJAX.Queue[0].Type;
	var Action		= CAJAX.Queue[0].Action;
	var Callback	= CAJAX.Queue[0].Callback;
	var URI			= CAJAX.Queue[0].URI;

	if(CAJAX.DebugMode) {
		try {
			PConsole.AddNotice("CAJAX.OnDone :: Request Finished Successfully", "Notice");
			PConsole.AddNotice(" - " + TextStatus, "Notice");
			PConsole.AddNotice(" - " + htmlspecialchars(Data), "Notice");
		}catch(err) {};
	}

	var Parts = explode("\n", Data, 2);

	if(Parts[0] <= 0 && CAJAX.DebugMode) {
		try {
			PConsole.AddNotice("CAJAX.OnDone :: " + Request + " :: (Code: " + Parts[0] + ") : " + Parts[1], "Error");
		}catch(err) {};
	}

	if(Callback) {
		Callback(Parts[0], Parts[1]);
	}

	CAJAX.Queue.reverse();
	CAJAX.Queue.pop();
	CAJAX.Queue.reverse();

	CAJAX.Sending = false;
}

//-----------------------------------------------------------------------------
CAJAX.OnError = function(Data, TextStatus) {
	var ID			= CAJAX.Queue[0].ID;
	var Request		= CAJAX.Queue[0].Request;
	var Type		= CAJAX.Queue[0].Type;
	var Action		= CAJAX.Queue[0].Action;
	var Callback	= CAJAX.Queue[0].Callback;
	var URI			= CAJAX.Queue[0].URI;

	if(CAJAX.DebugMode) {
		try {
			PConsole.AddNotice("CAJAX.OnDone :: " + Request + " :: HTTP Error : " + TextStatus + " (" + URI + ") : " + Data.responseText, "Error");
			PConsole.AddNotice(" - " + TextStatus, "Notice");
			PConsole.AddNotice(" - " + htmlspecialchars(Data), "Notice");
		}catch(err) {};
	}

	if(Callback) {
		Callback(0, Data.responseText);
	}

	CAJAX.Queue.reverse();
	CAJAX.Queue.pop();
	CAJAX.Queue.reverse();

	CAJAX.Sending = false;
}

//=============================================================================
$(CAJAX.OnInit);

//=============================================================================
