/* 
 * JavaScript file for ProjectCreator module.
 */
MProjectCreator = {};
var RecaptchaOptions;

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

MProjectCreator.InitPrivateOfferForm = function(Prefix) {
	RecaptchaOptions = { theme : 'clean' }; 
	
	$(document).ready(function() {
		$('.PriceType').change(function(){
			$('#'+Prefix+'PriceType').attr('value',$(this).val());
		});
		$('.ConnectionType').change(function(){
			$('#'+Prefix+'ConnectionType').attr('value',$(this).val());
		});

		$.validator.setDefaults({ onkeyup: false, onfocusout: false });					
		jQuery.validator.addMethod(	"checkCaptcha", 
									function() {
										var Parms = { 	
														recaptcha_challenge_field: $('input#recaptcha_challenge_field').val(),
														recaptcha_response_field: $('input#recaptcha_response_field').val()
													};
										var Response = CAJAX.ExecuteAsync('ProjectCreatorHome','Module','CheckCaptcha',Parms);

										if(Response[0] != 0) {
											return true;
										} else {
											Recaptcha.reload();
											return false;
										}
									  }, " (*) Invalid Captcha");					
		jQuery.validator.addMethod(	"projectNumberExists", 
									function() {
										var rsp = CAJAX.ExecuteAsync('ProjectCreatorHome','Module','ProjectNumberExists',{ ProjectNumber: $('#'+Prefix+'ProjectNumber').val() });
										
										return rsp[0] == 0;	
										
									}, " (*) Project Number already Exists");
		jQuery.validator.addMethod(	"ISBNExists", 
									function() {
										var rsp = CAJAX.ExecuteAsync('ProjectCreatorHome','Module','ISBNExists',{ ISBN: $('#'+Prefix+'ISBN').val() });													

										return rsp[0] == 0;	

									}, " (*) ISBN already Exists");
		jQuery.validator.addMethod(	"connectPlusISBNExists", 
									function() {
										var rsp = CAJAX.ExecuteAsync('ProjectCreatorHome','Module','ConnectPlusISBNExists',{ ConnectPlusISBN: $('#'+Prefix+'ConnectPlusISBN').val() });
										
										return rsp[0] == 0;	
										
									}, " (*) Connect Plus ISBN already Exists");
		
		var rules = new Object();
		rules[Prefix+'ProjectNumber'] = { required:true, maxlength: 10, number: true, projectNumberExists: true};
		rules[Prefix+'ISBN'] = { required:true, maxlength: 10, ISBNExists: true };
		rules[Prefix+'ConnectPlusISBN'] = { maxlength: 10, connectPlusISBNExists: true };
		rules[Prefix+'RequesterName'] = { required:true, maxlength: 50 };
		rules[Prefix+'RequesterEmail'] = { required:true, maxlength: 50, email: true };
		rules[Prefix+'LscID'] = { min: 1, number: true };
		rules[Prefix+'DateNeeded'] = { required:true, date: true };
		rules[Prefix+'CreativeContactID'] = { min: 1, number: true };
		rules[Prefix+'ConnectionType'] = { required:true };
		rules[Prefix+'Duration'] = { number: true };			
		rules[Prefix+'PriceType'] = { required:true };
		rules[Prefix+'Price'] = { required:true, number: true };
		rules['recaptcha_response_field'] = { checkCaptcha: true };
		
		var messages = new Object();
		messages[Prefix+'ProjectNumber'] = { required: " (*) Project number is required.", 
												 maxlength: " (*) Max length is 10 digits.", 
												 number: " (*) Project number must be a number." };
		messages[Prefix+'ISBN'] = { required: " (*) ISBN is required.", 
										maxlength: " (*) Max length is 10 digits."};
		messages[Prefix+'ConnectPlusISBN'] = 	{ maxlength: " (*) Max length is 10 digits."};					
		messages[Prefix+'RequesterName'] = 	{ required: " (*) Requester Name is required.", 
												  maxlength: " (*) Max length is 50 digits."};
		messages[Prefix+'RequesterEmail'] = 	{	required: " (*) Requester Email is required.", 
														maxlength: " (*) Max length is 50 digits.",
														email: " (*) Invalid email."};
		messages[Prefix+'DateNeeded'] = { 	required: " (*) Date Needed is required.", 
												date: " (*) Invalid date."};
		messages[Prefix+'Duration'] = { number: " (*) Duration must be a number." };
		messages[Prefix+'Price'] = { required: " (*) Price is required.", 
										 number: " (*) Price must be a number."};
		
		$("#CreationForm").validate({
			ignore: '',
			rules: rules,
			messages: messages
		});		
	});
}

MProjectCreator.InitPrivateOfferUpload = function(Prefix) {
	var rules = new Object();
	rules[Prefix+'Filename'] = { required:true };
	
	var messages = new Object();
	messages[Prefix+'Filename'] = { required: "Select a file with the upload button."};
	
	$(document).ready(function() {
		$("#UploadForm").validate({
			ignore: '',
			rules: rules,
			messages: messages
		});					
	});
}