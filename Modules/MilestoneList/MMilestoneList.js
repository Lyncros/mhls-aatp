/* 
 * JavaScript file for ModuleList module.
 */

MilestoneList = {};

MilestoneList.saveMilestone = function(Prefix) {

    if (CForm.Submit("MilestoneList", "Module", "SaveMilestone", Prefix, function(Code, Content) {

        alert('TODO: Refresh milestone list');
        return true;
    }) == false) {
        alert(CForm.GetLastError());

        return false;
    }

    return true;
};

