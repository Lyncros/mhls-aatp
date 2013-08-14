/* 
 * JavaScript file for ProjectCreator module.
 */
MProjectCreator = {};
var RecaptchaOptions;

MProjectCreator.checkCaptchaFunction = function() {
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
};

MProjectCreator.initUploadForm = function(Prefix) {		
	$(document).ready(function() {
        var rules = new Object();
        rules[Prefix+'Filename'] = { required:true };

        var messages = new Object();
        messages[Prefix+'Filename'] = { required: "Select a file with the upload button."};
    
		$("#UploadForm").validate({
			ignore: '',
			rules: rules,
			messages: messages
		});					
	});
}

// Shop Online Functions
MProjectCreator.initShopOnlineForm = function(Prefix) {
	RecaptchaOptions = { theme : 'clean' }; 
	
	$(document).ready(function() {
		$('.CoverType').change(function(){
			if($(this).val() == 'CustomCover') {
				$('#'+Prefix+'CustomCoverURL').parent().parent().show();
			} else {
                $('#'+Prefix+'CustomCoverURL').val('');
                $('#'+Prefix+'CustomCoverURL').parent().parent().hide();
            }
		});
		
		$.validator.setDefaults({ onkeyup: false, onfocusout: false });
		jQuery.validator.addMethod(	"checkCaptcha", MProjectCreator.checkCaptchaFunction, " (*) Invalid Captcha");
        jQuery.validator.addMethod(	"ISBNExists", 
									function() {
										var rsp = CAJAX.ExecuteAsync('ProjectCreatorHome','Module','ShopOnlineISBN10Exists',{ ISBN10: $('#'+Prefix+'ISBN10').val() });
										
										return rsp[0] == 0;	
										
									}, " (*) This ISBN is already in use. Please contact your Creative Analyst.");
        jQuery.validator.addMethod(	"isCustomCover", 
                                    function() {
                                        if($('input[name=CoverType]:checked').val() == 'CustomCover')
                                            return $('#'+Prefix+'CustomCoverURL').val().length > 0; 
                                        else
                                            return true;
                                    }, " (*) Custom Cover is required when Custom Cover is selected.");
		
		var rules = new Object();
		rules[Prefix+'ISBN10']              = { required:true, maxlength: 10, ISBNExists: true };
		rules[Prefix+'Author']              = { required:true, maxlength: 255 };
		rules[Prefix+'RequesterName']       = { required:true, maxlength: 255 };
		rules[Prefix+'RequesterEmail']      = { required:true, maxlength: 255, email: true };
		rules[Prefix+'DateNeeded']          = { required:true, date: true };
		rules[Prefix+'UsersID']             = { min: 1, number: true };
		rules[Prefix+'CustomCoverURL']      = { isCustomCover:true };
		rules[Prefix+'ISBNType']            = {  required: true };			
		rules['recaptcha_response_field']   = { checkCaptcha: true };
		
		var messages = new Object();
		messages[Prefix+'ISBN10']           = { required: " (*) ISBN-10 is required.", 
                                                maxlength: " (*) Max length is 10 digits."};
		messages[Prefix+'Author']           = { required: " (*) Author is required.", 
                                                maxlength: " (*) Max length is 255 digits."};					
		messages[Prefix+'RequesterName']    = { required: " (*) Requester Name is required.", 
                                                maxlength: " (*) Max length is 255 digits."};
		messages[Prefix+'RequesterEmail']   = { required: " (*) Requester Email is required.", 
                                                maxlength: " (*) Max length is 255 digits.",
                                                email: " (*) Invalid email."};
		messages[Prefix+'DateNeeded']       = { required: " (*) Date Needed is required.", 
                                                date: " (*) Invalid date."};
		
		$("#CreationForm").validate({
			ignore: '',
			rules: rules,
			messages: messages
		});		
	});
};

MProjectCreator.createShopOnline = function(Prefix) {
    if($('#CreationForm').valid()) {
        if (CForm.Submit("ProjectCreatorHome", "Module", "CreateShopOnline", Prefix, function(Code, Response) {
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

MProjectCreator.uploadShopOnlineFile = function(Prefix) {
    if($('#UploadForm').valid()) {
        if (CForm.Submit("ProjectCreatorHome", "Module", "UploadShopOnlineFile", Prefix, function(Code, Response) {
            if(Code == 0) {
                alert(Response);
			} else {
                var Parts = explode("\n", Response, 2);
                CWindow.New('Upload results', Parts[1], 700, 550);
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

// Private Offer Functions
MProjectCreator.initPrivateOfferForm = function(Prefix) {
	RecaptchaOptions = { theme : 'clean' };
	
	$(document).ready(function() {
		$('.PriceType').change(function(){
			$('#'+Prefix+'PriceType').attr('value',$(this).val());
		});
		$('.ConnectionType').change(function(){
			$('#'+Prefix+'ConnectionType').attr('value',$(this).val());
		});

		$.validator.setDefaults({ onkeyup: false, onfocusout: false });
		jQuery.validator.addMethod(	"checkCaptcha", MProjectCreator.checkCaptchaFunction, " (*) Invalid Captcha");
		jQuery.validator.addMethod(	"projectNumberExists", 
									function() {
										var rsp = CAJAX.ExecuteAsync('ProjectCreatorHome','Module','PrivateOfferProjectNumberExists',{ ProjectNumber: $('#'+Prefix+'ProjectNumber').val() });
										
										return rsp[0] == 0;	
										
									}, " (*) Project Number already Exists");
		jQuery.validator.addMethod(	"ISBNExists", 
									function() {
										var rsp = CAJAX.ExecuteAsync('ProjectCreatorHome','Module','PrivateOfferISBNExists',{ ISBN: $('#'+Prefix+'ISBN').val() });													

										return rsp[0] == 0;	

									}, " (*) This ISBN is already in use. Please contact your Creative Analyst.");
		jQuery.validator.addMethod(	"connectPlusISBNExists", 
									function() {
										var rsp = CAJAX.ExecuteAsync('ProjectCreatorHome','Module','PrivateOfferConnectPlusISBNExists',{ ConnectPlusISBN: $('#'+Prefix+'ConnectPlusISBN').val() });
										
										return rsp[0] == 0;	
										
									}, " (*) This Connect Plus ISBN is already in use. Please contact your Creative Analyst.");
		
		var rules = new Object();
		rules[Prefix+'ProjectNumber']       = { required:true, maxlength: 10, number: true, projectNumberExists: true};
		rules[Prefix+'ISBN']                = { required:true, maxlength: 10, ISBNExists: true };
		rules[Prefix+'ConnectPlusISBN']     = { maxlength: 10, connectPlusISBNExists: true };
		rules[Prefix+'RequesterName']       = { required:true, maxlength: 255 };
		rules[Prefix+'RequesterEmail']      = { required:true, maxlength: 255, email: true };
		rules[Prefix+'LscID']               = { min: 1, number: true };
		rules[Prefix+'DateNeeded']          = { required:true, date: true };
		rules[Prefix+'CreativeContactID']   = { min: 1, number: true };
		rules[Prefix+'ConnectionType']      = { required:true };
		rules[Prefix+'Duration']            = { number: true };			
		rules[Prefix+'PriceType']           = { required:true };
		rules[Prefix+'Price']               = { required:true, number: true };
		rules['recaptcha_response_field']   = { checkCaptcha: true };
		
		var messages = new Object();
		messages[Prefix+'ProjectNumber']    = { required: " (*) Project number is required.", 
                                                maxlength: " (*) Max length is 10 digits.", 
                                                number: " (*) Project number must be a number." };
		messages[Prefix+'ISBN']             = { required: " (*) ISBN is required.", 
                                                maxlength: " (*) Max length is 10 digits."};
		messages[Prefix+'ConnectPlusISBN']  = { maxlength: " (*) Max length is 10 digits."};					
		messages[Prefix+'RequesterName']    = { required: " (*) Requester Name is required.", 
                                                maxlength: " (*) Max length is 255 digits."};
		messages[Prefix+'RequesterEmail']   = { required: " (*) Requester Email is required.", 
                                                maxlength: " (*) Max length is 255 digits.",
                                                email: " (*) Invalid email."};
		messages[Prefix+'DateNeeded']       = { required: " (*) Date Needed is required.", 
                                                date: " (*) Invalid date."};
		messages[Prefix+'Duration']         = { number: " (*) Duration must be a number." };
		messages[Prefix+'Price']            = { required: " (*) Price is required.", 
                                                number: " (*) Price must be a number."};
		
		$("#CreationForm").validate({
			ignore: '',
			rules: rules,
			messages: messages
		});		
	});
}

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
    if($('#UploadForm').valid()) {
		if (CForm.Submit("ProjectCreatorHome", "Module", "UploadPrivateOfferFile", Prefix, function(Code, Response) {		
			if(Code == 0) {
				alert(Response);
			} else {			
				var Parts = explode("\n", Response, 2);
                CWindow.New('Upload results', Parts[1], 700, 550);
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