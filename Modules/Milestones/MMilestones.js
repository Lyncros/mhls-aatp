//=============================================================================
MMilestones = {};

//=============================================================================
MMilestones.AddEdit = function(WindowID, Prefix) {
	if(CForm.Submit("Milestones", "Module", "AddEdit", Prefix, function(Code, Content) {
		if(Code > 0) CWindow.Close(WindowID);

		return true;
	}) == false) {
		alert(CForm.GetLastError());

		return false;
	}

	return true;
}

//-----------------------------------------------------------------------------
MMilestones.Delete = function(WindowID, Prefix) {
	if(CForm.Submit("Milestones", "Module", "Delete", Prefix, function(Code, Content) {
		if(Code > 0) CWindow.Close(WindowID);

		return true;
	}) == false) {
		alert(CForm.GetLastError());

		return false;
	}

	return true;
}

//=============================================================================
MMilestones.Window_AddEdit = function(ID) {
	var Title = "Add Milestone";

	if(ID > 0) {
		Title = "Edit Milestone";
	}

	CWindow.Open(Title, 500, 300, "Milestones", "Module", "Window_AddEdit", {"ID" : ID});
}

//-----------------------------------------------------------------------------
MMilestones.Window_Delete = function(ID) {
	CWindow.Open("Delete Milestone", 300, 150, "Milestones", "Module", "Window_Delete", {"ID" : ID});
}

//=============================================================================
MMilestones.Save = function(ID) {
	var Parms		= {};
	Parms["ID"]		= ID;
	if(ID == 0) {
		Parms["Name"]				= $('#AddName').val();
		Parms["CustomerApproval"]	= $('#AddCustomerApproval').val();
		Parms["Summary"]			= $('#AddSummary').val();
		Parms["PlantAllocated"]		= $('#AddPlantAllocated').val();
		Parms["ToDosLists"]			= $('#AddToDosLists').select2("val");		
		Parms["Active"]				= $('#AddActive').val();
	} else {
		Parms["Name"]				= $('#Edit' + ID + 'Name').val();
		Parms["CustomerApproval"]	= $('#Edit' + ID + 'CustomerApproval').val();
		Parms["Summary"]			= $('#Edit' + ID + 'Summary').val();
		Parms["PlantAllocated"]		= $('#Edit' + ID + 'PlantAllocated').val();
		Parms["ToDosLists"]			= $('#Edit' + ID + 'ToDosLists').select2("val");		
		Parms["Active"]				= $('#Edit' + ID + 'Active').val();		
	}
	
	CAJAX.Add("Milestones", "Module", "AddEdit", Parms, function(Code, Content) {
		if(Code == 0) {
			alert(Content);			
			return false;
		}else{
			$(document.body).scrollTo(0, 800);
			
			if(ID == 0) {
				$('#AddName').val("");
				$('#AddCustomerApproval').val("");
				$('#AddSummary').val("");
				$('#AddPlantAllocated').val("");				
				$('#AddToDosLists').select2("val", "");				
				$('#AddActive').val("");
				$('#Add').slideUp();
				location.reload();
			}
			else
			{
				$('#H1'+ID).text(Parms["Name"]);
				$('#P'+ID).text(Parms["Summary"]);
			}
			return true;			
		}
	});
	
	return true;
}