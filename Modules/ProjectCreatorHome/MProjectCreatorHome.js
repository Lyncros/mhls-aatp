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
    if($('#Form').valid())
	{
		if (CForm.Submit("ProjectCreatorHome", "Module", "CreatePrivateOffer", Prefix, function(Code, Response) {
			 return true;
		}) == false) {
			alert(CForm.GetLastError());
			return false;
		}
		return true;
	}
};

MProjectCreator.uploadPrivateOfferFile = function(Prefix) {
    MProjectCreator.setEnableUploadButton(false);
   
    if (CForm.Submit("ProjectCreatorHome", "Module", "UploadPrivateOfferFile", Prefix, function(Code, Response) {		
		if(Code == 0)
		{
			alert(Response);
		}
		else
		{			
			var Parts = explode("\n", Response, 2);
			$('#UploadResultContainer').html(Parts[1]);
			$('#UploadResultContainer').slideDown(EFFECT_DURATION);
        }
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

