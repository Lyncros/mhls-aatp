//=============================================================================
CPlugin = {};

//=============================================================================
CPlugin.ShowHide = function(Element, Name) {
	var Parent	= Element.parentNode;
	var Content	= null;

	for(var i = 0;i < Parent.childNodes.length;i++) {
		if(Parent.childNodes[i] != null && Parent.childNodes[i].className == "CPlugin_Content") {			
			Content = Parent.childNodes[i];
			break;
		}
	}

	$(Content).toggle("slow", function() {
		CSideBar.ResetHeight();

		var Toggle = 1;

		if(Content.style.display == "block") {
			Toggle = 0;
		}

		var Parms = {
			"Toggle" : Toggle
		};

		CAJAX.Add(Name, "Plugin", "ToggleShrink", Parms, null);
	});
}

//=============================================================================
