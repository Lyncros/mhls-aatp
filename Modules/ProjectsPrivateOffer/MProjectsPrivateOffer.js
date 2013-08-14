/* 
 * JavaScript functions for MProjectsShopOnline module.
 */

var EFFECT_DURATION = 700;

MProjectsPrivateOffer = {};

MProjectsPrivateOffer.showProjectDetails = function(projectId) {
    var params = {
        'ID': projectId
    };

    CAJAX.Add("ProjectsPrivateOffer", "Module", "ShowProjectDetails", params, function(code, content) {
        if (code == 0) {
            alert(content);
        } else {
            $('#ProjectListContainer').slideUp(EFFECT_DURATION);
            $('#ProjectDetailsContainer').html(content);
            $('#ProjectDetailsContainer').fadeIn(EFFECT_DURATION);
        }
    });
};

MProjectsPrivateOffer.moveToList = function() {
    $('#ProjectListContainer').slideDown(EFFECT_DURATION);
    $('#ProjectDetailsContainer').fadeOut(EFFECT_DURATION, function() {
        $('#ProjectDetailsContainer').empty();
    });
    $('#ProjectDetailsContainer').slideUp(EFFECT_DURATION);
};

MProjectsPrivateOffer.toggleDetailsEdit = function() {
    $('#ProjectDetailsReadOnly').slideToggle(EFFECT_DURATION);
    $('#ProjectDetailsEdit').slideToggle(EFFECT_DURATION);
};

MProjectsPrivateOffer.toggleMilestone = function(milestoneID) {
    $('#Milestone' + milestoneID).slideToggle(EFFECT_DURATION);
};

MProjectsPrivateOffer.saveMilestone = function(prefix) {
    if(CForm.Submit("ProjectsPrivateOffer", "Module", "SaveMilestone", prefix, function(code, response) {
        var message;
        
        if (code == 0) {
            message = response;
        } else {
            var Parts = explode("\n", response, 2);
            message = Parts[0];
            $('#ProjectDetailsContainer').html(Parts[1]);
        }
        
        CForm.SubmitCallback(code, message);
        // We handle the display of success message
        return false;
	}) == false) {
		alert(CForm.GetLastError());
		return false;
	}
    return true;
};

MProjectsPrivateOffer.deleteMilestone = function(MilestoneID) {
    alert('Deleting milestones is not allowed in this phase.');
};
