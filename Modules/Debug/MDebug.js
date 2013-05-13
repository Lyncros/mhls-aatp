//=============================================================================
MDebug = {};

//=============================================================================
MDebug.ClearAll = function() {
	if(confirm("Are you sure you want to clear all Debug Information?")) {
		CAJAX.Add("Debug", "Module", "ClearAll", {}, function(Code, Content) {
			if(Code == 0) {
				alert(Content);
			}else{
				CModule.Load("Debug", {});
			}

			return false;
		});
	}
}

//=============================================================================
