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
        CWindow.New('Upload results', Parts[1], 450, 550);
        
        // We handle the display of success message
        return false;
    }) == false) {
        alert(CForm.GetLastError());
        return false;
    }
    return true;
};

MProjectCreator.createPrivateOffer = function(Prefix) {
    if($('#CreationForm').valid()) {
		if (CForm.Submit("ProjectCreatorHome", "Module", "CreatePrivateOffer", Prefix, function(Code, Response) {
			 return true;
		}) == false) {
			alert(CForm.GetLastError());
			return false;
		}
		return true;
	} else {
		Recaptcha.reload();
	}	
};

MProjectCreator.uploadPrivateOfferFile = function(Prefix) {
    if($('#UploadForm').valid())
	{
		MProjectCreator.setEnableUploadButton(false);
	   
		if (CForm.Submit("ProjectCreatorHome", "Module", "UploadPrivateOfferFile", Prefix, function(Code, Response) {		
			if(Code == 0)
			{
				alert(Response);
			}
			else
			{			
				var Parts = explode("\n", Response, 2);
				CWindow.New('Upload results', Parts[1], 460, 600);
			}
			// We handle the display of success message
			return false;
		}) == false) {
			alert(CForm.GetLastError());
			return false;
		}
		return true;
	}
};

