//=============================================================================
CKeyboard = {};

//=============================================================================
CKeyboard.GetKey = function(Event) {
	if(window.event) KeyCode = window.event.keyCode;
	else if(Event) KeyCode = Event.which;
	else return true;

	switch(KeyCode) {
		case 13: return "KS_Enter";

		default: return "";
	}
}

//=============================================================================
