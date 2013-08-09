/* 
 * JavaScript file for ProjectCreator module.
 */
var EFFECT_DURATION = 800;

MProjectCreator = {};

MProjectCreator.createShopOnline = function(Prefix) {
    if (CForm.Submit("ProjectCreatorHome", "Module", "CreateShopOnline", Prefix, function(Code, Response) {
        return true;
    }) == false) {
        alert(CForm.GetLastError());
        return false;
    }
    return true;
};

MProjectCreator.uploadShopOnlineFile = function(Prefix) {
    MProjectCreator.setEnableUploadButton(false);
   
    if (CForm.Submit("ProjectCreatorHome", "Module", "UploadShopOnlineFile", Prefix, function(Code, Response) {

        var Parts = explode("\n", Response, 2);
        $('#UploadResultContainer').html(Parts[1]);
        $('#UploadResultContainer').slideDown(EFFECT_DURATION);
        
        // We handle the display of success message
        return false;
    }) == false) {
        alert(CForm.GetLastError());
        return false;
    }
    return true;
};

MProjectCreator.hideUploadResults = function() {
    $('#UploadResultContainer').slideUp(EFFECT_DURATION, function() {
        $('#UploadResultContainer').empty();
        MProjectCreator.setEnableUploadButton(true);
    });
};

MProjectCreator.setEnableUploadButton = function(value) {
  //set enable or disable upload button  
};

