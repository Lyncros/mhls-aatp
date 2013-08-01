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
        MMilestoneList.hideEditMilestone(MilestoneId);
        
        var Parts = explode("\n", Response, 2);
        CPageNotice.Add("Success", Parts[0]);
        $('#MilestoneRowContainer' + MilestoneId).html(Parts[1]);
        
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
            $('#MilestoneRowContainer' + MilestoneID).html('');
            
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
    $('#Milestone' + MilestoneId).html('');
};
