/* 
 * JavaScript file for ProjectCreator module.
 */
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
    if (CForm.Submit("ProjectCreatorHome", "Module", "UploadShopOnlineFile", Prefix, function(Code, Response) {

        var Parts = explode("\n", Response, 2);
        CWindow.New('Upload results', Parts[1], 400, 400);
        
        // We handle the display of success message
        return false;
    }) == false) {
        alert(CForm.GetLastError());
        return false;
    }
    return true;
};
