/* 
 * JavaScript file for ModuleList module.
 */

MMilestoneList = {};

MMilestoneList.editMilestone = function(MilestoneId) {
    var Params = {
        'MilestoneID': MilestoneId
    };

    CAJAX.Add("MilestoneList", "Module", "EditMilestone", Params, function(Code, Content) {
        if (Code == 0) {
            alert(Content);
        } else {
            $('#Milestone' + MilestoneId).html(Content);
            $('#Milestone' + MilestoneId).slideDown();
        }
    });
};

MMilestoneList.cancelEditMilestone = function(MilestoneId) {
    this.hideEditMilestone(MilestoneId);
};

MMilestoneList.saveMilestone = function(MilestoneId, Prefix) {
    if(CForm.Submit("MilestoneList", "Module", "SaveMilestone", Prefix, function(Code, Response) {
        var message;
        
        if (Code == 0) {
            message = Response;
        } else {
            MMilestoneList.hideEditMilestone(MilestoneId);
        
            var Parts = explode("\n", Response, 2);
            message = Parts[0];
            $('#MilestoneRowContainer' + MilestoneId).html(Parts[1]);
        }
        
        CForm.SubmitCallback(Code, message);
        // We handle the display of success message
        return false;
	}) == false) {
		alert(CForm.GetLastError());
		return false;
	}
    return true;
};

MMilestoneList.deleteMilestone = function(MilestoneID) {
	var Params = {
		'MilestoneID' : MilestoneID
	};
	
	if(confirm("Are you sure you want to delete this Milestone?")) {
		if(CForm.Submit('MilestoneList', 'Module', 'DeleteMilestone', '', function(Code, Content) {
            MMilestoneList.hideEditMilestone(MilestoneID);
            $('#MilestoneRowContainer' + MilestoneID).empty();
            
			return true;
		}, Params) == false) {
			alert(CForm.GetLastError());
			return false;
		}
	}

	return true;
};

MMilestoneList.hideEditMilestone = function(MilestoneId) {
    $('#Milestone' + MilestoneId).slideUp();
    $('#Milestone' + MilestoneId).empty();
};

MMilestoneList.editToDo = function(MilestoneToDoID) {
    var Params = {
        'MilestoneToDoID': MilestoneToDoID
    };

    CAJAX.Add("MilestoneList", "Module", "EditMilestoneToDo", Params, function(Code, Content) {
        if (Code == 0) {
            alert(Content);
        } else {
            $('#ToDo' + MilestoneToDoID).html(Content);
            $('#ToDo' + MilestoneToDoID).slideDown();
        }
    });
};

MMilestoneList.cancelEditToDo = function(MilestoneToDoID) {
    this.hideEditToDo(MilestoneToDoID);
};

MMilestoneList.saveMilestoneToDo = function(MilestoneToDoId, Prefix) {
    if(CForm.Submit("MilestoneList", "Module", "SaveMilestoneToDo", Prefix, function(Code, Response) {
        var message;
        
        if (Code == 0) {
            message = Response;
        } else {
            MMilestoneList.hideEditToDo(MilestoneToDoId);
        
            var Parts = explode("\n", Response, 2);
            message = Parts[0];
            $('#MilestoneToDoContainer' + MilestoneToDoId).html(Parts[1]);
        }
        
        CForm.SubmitCallback(Code, message);
        // We handle the display of success message
        return false;
	}) == false) {
		alert(CForm.GetLastError());
		return false;
	}
    return true;
};

MMilestoneList.deleteMilestoneToDo = function(MilestoneToDoID) {
	var Params = {
		'MilestoneToDoID' : MilestoneToDoID
	};
	
	if(confirm("Are you sure you want to delete this Milestone ToDo?")) {
		if(CForm.Submit('MilestoneList', 'Module', 'DeleteMilestoneToDo', '', function(Code, Content) {
            MMilestoneList.hideEditMilestone(MilestoneToDoID);
            $('#MilestoneToDoContainer' + MilestoneToDoID).empty();
            
			return true;
		}, Params) == false) {
			alert(CForm.GetLastError());
			return false;
		}
	}

	return true;
};

MMilestoneList.hideEditToDo = function(MilestoneToDoId) {
    $('#ToDo' + MilestoneToDoId).slideUp();
    $('#ToDo' + MilestoneToDoId).empty();
};
