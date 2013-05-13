//=============================================================================
MAuditBills = {};

MAuditBills.ProductSolutions = {};

//=============================================================================
MAuditBills.Delete = function(WindowID, Prefix) {
	if(CForm.Submit("AuditBills", "Module", "Delete", Prefix, function(Code, Content) {
		if(Code > 0) CWindow.Close(WindowID);

		return true;
	}) == false) {
		alert(CForm.GetLastError());

		return false;
	}

	return true;
}

//=============================================================================
MAuditBills.Window_Delete = function(ID) {
	CWindow.Open("Delete Audit Bill", 300, 170, "AuditBills", "Module", "Window_Delete", {"ID" : ID});
}

//=============================================================================
MAuditBills.ShowPreviewBox = function(Element, Type, RecordID) {
	var Parms = {
		"Type"  	: Type,
		"RecordID" 	: RecordID
	};
	
	if($(".PreviewBox").get(0) == undefined) {
		$(document.body).append("<div class='PreviewBox'></div>");
	}
	
	CAJAX.Add("AuditBills", "Module", "GetPreviewBoxData", Parms, function(Code, Content) {
		if(Code == 0) {
			console.log(Content);
		}else{
			var Data = JSON.parse(Content);
			
			var Pos = $(Element).offset();			
			
			var ElementWidth = $(Element).width();
		
			$(".PreviewBox").html(Data.HTML + "<div class='PreviewBoxClose'></div>");
			$(".PreviewBox").animate({
				"left"	  : (Pos.left + ElementWidth) + "px",
				"top"	  : (Pos.top) + "px",				
				"width"   : Data.Width + "px",
				"height"  : Data.Height + "px",
				"opacity" : 1
			});		
			
			$(".PreviewBoxClose").click(function() {
				$(".PreviewBox").fadeOut("fast");
			});				
		}
	});	
}

//=============================================================================
MAuditBills.WatchInputs = function() {
	MAuditBills.FirstLoad = true;

	$("#AddUserButton").bind("click", function() {
		var UsersID = $("#AddUserID").val();
		var Name	= $("#AddUserID").selectedTexts();
		var Role	= "";
		
		Name = Name[0];

		MAuditBills.AddAssignedUser(UsersID, Name, Role);
	});
	
	$("#InstitutionsID").bind("change", function() {
		var ID = parseInt($(this).val());
		
		if(ID == 0) {
			$(this).parent().parent().parent().find("input[id^=Institution]").removeAttr("disabled").val("");
		}else{
			$(this).parent().parent().parent().find("input[id^=Institution]").attr("disabled", "disabled");
		}
		
		MAuditBills.UpdateDropdowns();
	}).trigger("change");
	
	$("#InstitutionsUsersID").bind("change", function() {
		var ID = parseInt($(this).val());
		
		if(ID == 0) {
			$(this).parent().parent().parent().find("[id^=Institution]").removeAttr("disabled").val("");
		}else{
			$(this).parent().parent().parent().find("[id^=Institution]").attr("disabled", "disabled");
			$(this).removeAttr("disabled");
		}		
		
		MAuditBills.UpdateReadOnlyInfo();	
	}).trigger("change");	
	
	$(".SaveButton").bind("click", function() { MAuditBills.Save("Save") });
	$(".ABAdvanceButton").bind("click", function() { 
		if(confirm("Are you sure you want to advance this AB to the next step?") == false) return;
		
		MAuditBills.Save("Advance");
	});	
	
	//$("#ProductSolutionsID").css("width", "180px").after("<div class='ABUniqueIcon'></div>");
	$("#InstitutionsID").css("width", "180px").after("<div class='ABUniqueIcon'></div>");
	$("#InstitutionsUsersID").css("width", "180px").after("<div class='ABUniqueIcon'></div>");
	
	//$("#ProductSolutionName").bind("keydown", MAuditBills.CheckForUnique);
	$("#InstitutionName").bind("keydown", MAuditBills.CheckForUnique);
	$("#InstitutionContactFirstName").bind("keydown", MAuditBills.CheckForUnique);
	$("#InstitutionContactLastName").bind("keydown", MAuditBills.CheckForUnique);
	
	MAuditBills.FirstLoad = false;
	
	MAuditBills.UpdateReadOnlyInfo();
}

//-----------------------------------------------------------------------------
MAuditBills.AddAssignedUser = function(UsersID, Name, Role) {
	var HTML = "<div rel='" + UsersID + "'>";
	
	HTML += Name;
	HTML += "<select rel='Role'></select>";
	HTML += "</div>";
	
	var NewUser = $(HTML).appendTo(".AssignedUserList");
	
	$(NewUser).find("select").addOption(MAuditBills.AssignedUsersRoles);
	$(NewUser).find("select").selectOption(Role);
	
	$(NewUser).bind("click", function() {
		if(confirm("Are you sure you want to remove this Assigned User?") == false) return;
		
		$(this).remove();
	});
}

//-----------------------------------------------------------------------------
MAuditBills.AddISBN = function(ISBN) {
	var Element = $("<div><input type='text' style='width: 180px;'/> <div class='Icon_Delete' style='float: right; margin-top: 4px;'></div></div>").appendTo(".ISBNList");
	
	$(Element).find("input").val(ISBN);
	$(Element).find(".Icon_Delete").click(function() {
		if(confirm("Are you sure you want to remove this ISBN?") == false) return;
		
		$(this).parent().remove();
	});
}

//-----------------------------------------------------------------------------
MAuditBills.AddProductSolution = function(ID) {
	var Element = $("<div><select rel='ID' style='width: 130px;'></select> <input type='text' rel='Name' style='width: 120px;'/> <input type='text' rel='Price' style='width: 40px;'/> <div class='Icon_Delete' style='float: right; margin-top: 4px;'></div></div>").appendTo(".ProductSolutionsList");
	
	$(Element).find("select").addOption(MAuditBills.ProductSolutions);	
	$(Element).find("select").val(ID).attr("default", ID);
	$(Element).find(".Icon_Delete").click(function() {
		if(confirm("Are you sure you want to remove this Product Solution?") == false) return;
		
		$(this).parent().remove();
	});	
	
	$(Element).find("select").bind("change", function() {
		var ID = parseInt($(this).val());
		
		if(ID == 0) {
			$(this).parent().find("[rel=Name]").removeAttr("disabled").val("");
			$(this).parent().find("[rel=Price]").removeAttr("disabled").val("");
		}else{
			$(this).parent().find("[rel=Name]").attr("disabled", "disabled");
			$(this).parent().find("[rel=Price]").attr("disabled", "disabled");
		}
		
		MAuditBills.UpdateReadOnlyInfo();		
	}).trigger("change");
}

//-----------------------------------------------------------------------------
MAuditBills.UpdateProductSolutionsArray = function() {
	var ProductSolutions = [];

	$(".ProductSolutionsList > div").each(function() { 
		ProductSolutions.push({
			"ID"	: $(this).find("[rel=ID]").val(),
			"Name" 	: $(this).find("[rel=Name]").val(),
			"Price" : $(this).find("[rel=Price]").val()
		}); 
	});
	
	$("#ProductSolutions").val(JSON.stringify(ProductSolutions));	
}

//-----------------------------------------------------------------------------
MAuditBills.UpdateDropdowns = function() {
	MAuditBills.UpdateProductSolutionsArray();

	var Parms = {
		"InstitutionsID" : $("#InstitutionsID").val()
	};
	
	$("#InstitutionsUsersID").attr("disabled", "disabled");
	$(".ProductSolutionsList select").attr("disabled", "disabled");	
	
	CAJAX.Add("AuditBills", "Module", "GetInstitutionData", Parms, function(Code, Content) {
		if(Code == 0) {
			console.log(Content);
		}else{
			var Data = JSON.parse(Content);
			
			MAuditBills.ProductSolutions = Data.ProductSolutions;
			
			$(".ProductSolutionsList select").each(function() {
				var OldValue = $(this).val();
				
				if($(this).attr("default") != "") {
					OldValue = $(this).attr("default");
					$(this).attr("default", "");
				}
				
				$(this).removeOption(/./);
				$(this).addOption(Data.ProductSolutions);
				
				if(OldValue != undefined) $(this).selectOptions(OldValue);
			});
			
			var OldValue = $("#InstitutionsUsersID").val();
			$("#InstitutionsUsersID").removeOption(/./);
			$("#InstitutionsUsersID").addOption(Data.Contacts).selectOptions(OldValue);			
			
			$("#InstitutionsUsersID").removeAttr("disabled").trigger("change");
			$(".ProductSolutionsList select").removeAttr("disabled").trigger("change");				
		}
	});
}

//-----------------------------------------------------------------------------
MAuditBills.UpdateReadOnlyInfo = function() {
	if(MAuditBills.FirstLoad) return;
	
	MAuditBills.UpdateProductSolutionsArray();

	var Parms = {
		"ProductSolutions" 		: $("#ProductSolutions").val(),
		"InstitutionsID" 		: $("#InstitutionsID").val(),
		"InstitutionsUsersID" 	: $("#InstitutionsUsersID").val()			
	};

	CAJAX.Add("AuditBills", "Module", "GetReadOnlyInfo", Parms, function(Code, Content) {
		if(Code == 0) {
			console.log(Content);
		}else{
			var Data = JSON.parse(Content);
			
			for(var i in Data) {
				if(i == "ProductSolutions") continue;
			
				$("#" + i).val(Data[i]);
			}			
			
			var j = 1;
			for(var i in Data.ProductSolutions) {
				var ProductSolution = Data.ProductSolutions[i];				
				var $ParentDiv 		= $(".ProductSolutionsList > div:nth-child(" + j + ")");
				
				var ID = parseInt($ParentDiv.find("[rel=ID]").val());
				
				if(ID > 0) {
					$ParentDiv.find("[rel=Name]").val(ProductSolution.Name);
					$ParentDiv.find("[rel=Price]").val(ProductSolution.Price);
				}
				
				j++;
			}
		}
	});
}

//-----------------------------------------------------------------------------
MAuditBills.CheckForUnique = function() {
	if(MAuditBills.CheckForUniqueTimer) clearTimeout(MAuditBills.CheckForUniqueTimer);

	MAuditBills.CheckForUniqueTimer = setTimeout(function() {
		var Parms = {
			"ProductSolutionsID" 			: $("#ProductSolutionsID").val(),
			"ProductSolutionName" 			: $("#ProductSolutionName").val(),
		
			"InstitutionsID" 				: $("#InstitutionsID").val(),
			"InstitutionName" 				: $("#InstitutionName").val(),		
		
			"InstitutionsUsersID" 			: $("#InstitutionsUsersID").val(),
			"InstitutionContactFirstName" 	: $("#InstitutionContactFirstName").val(),
			"InstitutionContactLastName" 	: $("#InstitutionContactLastName").val()
		};
	
		$("#ProductSolutionsID").find(".ABUniqueIcon").attr("rel", "");
		$("#InstitutionsID").find(".ABUniqueIcon").attr("rel", "");
		$("#InstitutionsUsersID").find(".ABUniqueIcon").attr("rel", "");

		CAJAX.Add("AuditBills", "Module", "CheckForUnique", Parms, function(Code, Content) {
			if(Code == 0) {
				console.log(Content);
			}else{
				var Data = JSON.parse(Content);		
			
				$("#ProductSolutionsID").parent().find(".ABUniqueIcon").attr("rel", Data.ProductSolution);
				$("#InstitutionsID").parent().find(".ABUniqueIcon").attr("rel",  Data.Institution);
				$("#InstitutionsUsersID").parent().find(".ABUniqueIcon").attr("rel", Data.InstitutionUser);			
			}
		});
	}, 1000);
}

//-----------------------------------------------------------------------------
MAuditBills.Save = function(SaveType) {
	var ISBNs = [];

	$(".ISBNList input").each(function() { ISBNs.push($(this).val()); });
	$("#ISBNs").val(JSON.stringify(ISBNs));
	
	MAuditBills.UpdateProductSolutionsArray();

	if(CForm.Submit("AuditBills", "Module", "Save", "", function(Code, Content) {
		if(Code == 0) {
			//alert(Content);
		}else{			
			setTimeout(function() { CModule.Load('AuditBills', {'ID' : Code}) }, 500);
		}

		return true;
	}, {"SaveType" : SaveType}) == false) {
		alert(CForm.GetLastError());

		return false;
	}

	return true;
}

//=============================================================================
