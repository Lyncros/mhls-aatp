//=============================================================================
CBox = {};

//=============================================================================
CBox.Remove = function(UID) {
	var Element = $("CBox_" + UID).get(0);

	if(Element == null) return;

	if(confirm("Are you sure you want to close this box?")) {
		function OnDone() {
			Element.style.display = "none";
		}

		$(Element).hide("normal", OnDone);
	}
}

//-----------------------------------------------------------------------------
CBox.CollapseExpand = function(UID, ElementArrow) {
	var Element = $("CBox_Content_" + UID).get(0);

	if(Element == null) return;

	$(Element).toggle("slow");

	if(ElementArrow.className == "CBox_Collapse") {
		ElementArrow.className = "CBox_Expand";

//		Element.style.overflow	= "hidden";
//		Element.style.display	= "none";
	}else{
		ElementArrow.className = "CBox_Collapse";

//		Element.style.display	= "";
//		Element.style.overflow	= "auto";
	}
}

//=============================================================================
