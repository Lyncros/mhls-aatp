/* 
 * JavaScript functions for MProjectsShopOnline module.
 */

var EFFECT_DURATION = 700;

MProjectsShopOnline = {};

MProjectsShopOnline.showProjectDetails = function(projectId) {
    var params = {
        'ID': projectId
    };

    CAJAX.Add("ProjectsShopOnline", "Module", "ShowProjectDetails", params, function(code, content) {
        if (code == 0) {
            alert(content);
        } else {
            $('#ProjectListContainer').slideUp(EFFECT_DURATION);
            $('#ProjectDetailsContainer').html(content);
            $('#ProjectDetailsContainer').fadeIn(EFFECT_DURATION);
        }
    });
};

MProjectsShopOnline.moveToList = function() {
    $('#ProjectListContainer').slideDown(EFFECT_DURATION);
    $('#ProjectDetailsContainer').fadeOut(EFFECT_DURATION, function() {
        $('#ProjectDetailsContainer').empty();
    });
    $('#ProjectDetailsContainer').slideUp(EFFECT_DURATION);
};

MProjectsShopOnline.toggleDetailsEdit = function() {
    $('#ProjectDetailsReadOnly').slideToggle();
    $('#ProjectDetailsEdit').slideToggle();
};
