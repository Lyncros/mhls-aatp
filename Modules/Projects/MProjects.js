//=============================================================================
MProjects = {};

MProjects.ProductSolutions = {};

//=============================================================================
MProjects.OnInit = function() {
	$("#Filter").click(function(){
		$(this).toggleClass('FilterActive');
		$('#FilterOptions').slideToggle();
		if (window.getSelection) {  // all browsers, except IE before version 9
			var selection = window.getSelection ();                                        
			selection.removeAllRanges ();
		}
		else {
			if (document.selection.createRange) {        // Internet Explorer
				var range = document.selection.createRange ();
				document.selection.empty ();
			}
		}
	});
	
	$("#FilterOptions").click(function(e){
		e.stopPropagation();
	});
	
	$(".FilterOption").click(function(){
		var Filters = JSON.parse($("#Filters").val());

		$(this).parent(".FilterOptionContainer").toggleClass("FilterOptionContainerActive");
		$(this).toggleClass("FilterOptionActive");
		$(this).parent(".FilterOptionContainer").children(".SubOptions").slideToggle();
		if($(this).parent(".FilterOptionContainer").hasClass("FilterOptionContainerActive")) {
			Filters[$(this).parent(".FilterOptionContainer").attr("id")] = true;
			Filters[$(this).parent(".FilterOptionContainer").attr("id") + "Value"] = {};
			$("#Filters").val(JSON.stringify(Filters))
		} else {
			delete Filters[$(this).parent(".FilterOptionContainer").attr("id")];
			$("#Filters").val(JSON.stringify(Filters))
			$(this).parent(".FilterOptionContainer").find(".FilterSubOption").each(function(){
				if($(this).hasClass("FilterSubOptionActive")) {
					$(this).click();
				}
			});
			delete Filters[$(this).parent(".FilterOptionContainer").attr("id") + "Value"];
		}
		
		var ActiveFilterList	= "";
		var Separator			= "";
		$.each(Filters, function(i, item){
			if(item === true) {
				ActiveFilterList += Separator + $("#" + i).attr("value");
				Separator = "&nbsp;|&nbsp;";
			}
		});
		$("#ActiveFilterList").html(ActiveFilterList)
	});
	
	$(".FilterSubOption").click(function(){
		var Filters = JSON.parse($("#Filters").val());

		$(this).parent(".FilterSubOptionContainer").toggleClass("FilterSubOptionContainerActive");
		$(this).toggleClass("FilterSubOptionActive");
		if($(this).parent(".FilterSubOptionContainer").hasClass("FilterSubOptionContainerActive")) {
			Filters[$(this).parent(".FilterSubOptionContainer").attr("id")][$(this).parent(".FilterSubOptionContainer").attr("value")] = true;
			$("#Filters").val(JSON.stringify(Filters))
		} else {
			delete Filters[$(this).parent(".FilterSubOptionContainer").attr("id")][$(this).parent(".FilterSubOptionContainer").attr("value")];
			$("#Filters").val(JSON.stringify(Filters))
		}
	});
		
	$(".FilterSubDate").change(function(){
		var Filters = JSON.parse($("#Filters").val());

		Filters[$(this).attr("name")][str_replace($(this).attr("name"), "", $(this).attr("id"))] = $(this).val();
		$("#Filters").val(JSON.stringify(Filters))
	});
	
	$("#FilterOperator").change(function(){
		var Filters = JSON.parse($("#Filters").val());

		Filters['FilterOperator'] = $('#FilterOperator').val();
		$("#Filters").val(JSON.stringify(Filters));
	});
}

//=============================================================================
MProjects.SaveFilter = function() {
	var Filters = JSON.parse($("#Filters").val());
	Filters['FilterOperator'] = $('#FilterOperator').val();
	var Name = $('#SaveFilterName').val();
		
	// Has to edit a previous filter
	if((!Name && !$("#FilterProfiles option[value='0']").is(':selected')) || Name)
	{
		var Parms = {};
		Parms["Name"]			= (Name) ? Name : $("#FilterProfiles option:selected").text();
		Parms["Options"]		= JSON.stringify(Filters);		
		
		CAJAX.Add("Projects", "Module", "SaveFilter", Parms, function(Code, Content){
			if(Code == 0) {
				alert(Content);
			} else {
				$('#FilterProfiles').append($('<option></option>').attr('value', Parms["Options"]).attr('selected', true).text(Parms["Name"]));			
				CPageNotice.Add("Success", Content);
				$('#Filters').val(Parms["Options"]);
				$('#SearchForm').submit();
			}
		});
	}
	else
	{	
		alert('The filter name is required');
	}
}

//=============================================================================
MProjects.DeleteFilter = function() {
	var option = $("#FilterProfiles option:selected");
	if (option.val() != '0')
	{
		var Parms = {};
		Parms["Name"] = option.text();
		CAJAX.Add("Projects", "Module", "DeleteFilter", Parms, function(Code, Content){
			if(Code == 0)
				alert(Content)
			else
			{				
				CPageNotice.Add("Success", Content);
				$("#FilterProfiles option[value='{}']").attr("selected", true);
				$('#Filters').val($("#FilterProfiles option[value='{}']").val());
				$('#SearchForm').submit();
			}
		});
	}
}

//=============================================================================
MProjects.ApplyFilter = function() {
	var Filters = JSON.parse($("#Filters").val());
	Filters['FilterOperator'] = $('#FilterOperator').val();
	
	var Parms = {};
	Parms["Name"]		= 'Temporary';
	Parms["Options"]	= JSON.stringify(Filters);	
	
	CAJAX.Add("Projects", "Module", "SaveFilter", Parms, function(Code, Content){
		if(Code == 0) {
			alert(Content);
		} else {
			CPageNotice.Add("Success", Content);
			$('#FilterProfiles').append($('<option></option>').attr('value', Parms["Options"]).attr('selected', true).text(Parms["Name"]));
			$('#Filters').val(Parms["Options"]);
			$('#SearchForm').submit();
		}
	});
}

//=============================================================================
MProjects.MoveToList = function(ProjectID) {
	$(".PreviewBox").fadeOut("fast");
	$('#SearchResultsContainer').animate({ left : '25px' }, 500, 'swing', function(){
		//$().scrollTo($('#ProjectPosition').val(), 500, { offset : -15 });
		$('html, body').animate({ scrollTop : $($('#ProjectPosition').val()).offset().top - 15 }, 500, 'swing');
	});
	$('.SidebarSubicon').hide(500);
	$('.SidebarActive').animate({ height : '72px' }, 500, 'swing');
	$('#HeightCalculator').animate({ height : $('#SearchResultsContainer').css('height') }, 500, 'swing');
}

//-----------------------------------------------------------------------------
MProjects.MoveToDetails = function(ProjectID) {
	$(".PreviewBox").fadeOut("fast");
	$('#SearchResultsContainer').animate({ left		: '-810px' }, 500, 'swing');
	$('.SidebarSubicon').show(500);
	$('.SidebarActive').animate({ height : '190px' }, 500, 'swing');
	//$().scrollTo('.Body', 500);
	$('html, body').animate({ scrollTop : $(".Body").offset().top }, 500, 'swing');
	$('#HeightCalculator').animate({ height : ($('#ProjectDetailsContainer').height() + 2000) + "px" }, 500, 'swing');
	if(ProjectID) {
		$('#ProjectPosition').val('#ProjectOverview' + ProjectID);
	}
}

//-----------------------------------------------------------------------------
MProjects.MoveToMilestones = function() {
	MProjects.MoveToDetails();
	//$().scrollTo('#MilestoneTarget', 500);
	$('html, body').animate({ scrollTop : $("#MilestoneTarget").offset().top }, 500, 'swing');
}

//-----------------------------------------------------------------------------
MProjects.MoveToMessages = function() {
	$(".PreviewBox").fadeOut("fast");
	$('#SearchResultsContainer').animate({ left : '-1620px' }, 500, 'swing');
	//$().scrollTo('.Body', 500);
	$('html, body').animate({ scrollTop : $(".Body").offset().top }, 500, 'swing');
	$('#HeightCalculator').animate({ height : ($('#ProjectMessagesContainer').height() + 500) + "px" }, 500, 'swing');
}

//-----------------------------------------------------------------------------
MProjects.MoveToResources = function() {
	$(".PreviewBox").fadeOut("fast");
	$('#SearchResultsContainer').animate({ left : '-2430px' }, 500, 'swing');
	//$().scrollTo('.Body', 500);
	$('html, body').animate({ scrollTop : $(".Body").offset().top }, 500, 'swing');
	$('#HeightCalculator').animate({ height : ($('#ProjectResourcesContainer').height() + 500) + "px" }, 500, 'swing');
}

//-----------------------------------------------------------------------------
MProjects.MoveToNotifications = function() {
	$(".PreviewBox").fadeOut("fast");
	$('#SearchResultsContainer').animate({ left : '-3240px' }, 500, 'swing');
	//$().scrollTo('.Body', 500);
	$('html, body').animate({ scrollTop : $(".Body").offset().top }, 500, 'swing');
	$('#HeightCalculator').animate({ height : ($('#ProjectNotificationsContainer').height() + 500) + "px" }, 500, 'swing');
}

//=============================================================================
MProjects.Delete = function(WindowID, Prefix) {
	if(CForm.Submit("Projects", "Module", "Delete", Prefix, function(Code, Content) {
		if(Code > 0) CWindow.Close(WindowID);

		return true;
	}) == false) {
		alert(CForm.GetLastError());

		return false;
	}

	return true;
}

//=============================================================================
MProjects.DeleteProject = function(Prefix, ProjectID) {
	var Parms = {
		"ProjectID" : ProjectID
	}
	
	if(confirm("Are you sure you want to delete this Project?")) {
		if(CForm.Submit("Projects", "Module", "DeleteProject", Prefix, function(Code, Content) {
			//MProjects.ViewDetails($('#' + Prefix + 'ProjectsID').val());
			setTimeout("CModule.Load('Projects');", 2000);
			return true;
		}, Parms) == false) {
			alert(CForm.GetLastError());
	
			return false;
		}
	}

	return true;
}

//-----------------------------------------------------------------------------
MProjects.DeleteMilestone = function(Prefix, MilestoneID) {
	var Parms = {
		"MilestoneID" : MilestoneID
	};
	
	if(confirm("Are you sure you want to delete this Milestone?")) {
		if(CForm.Submit("Projects", "Module", "DeleteMilestone", Prefix, function(Code, Content) {
			MProjects.ViewDetails($('#' + Prefix + 'ProjectsID').val());
			return true;
		}, Parms) == false) {
			alert(CForm.GetLastError());
	
			return false;
		}
	}

	return true;
}

//-----------------------------------------------------------------------------
MProjects.DeleteMilestoneToDo = function(Prefix, MilestoneToDoID) {
	var Parms = {
		"MilestoneToDoID" : MilestoneToDoID
	};
	
	if(confirm("Are you sure you want to delete this Milestone To-Do?")) {
		if(CForm.Submit("Projects", "Module", "DeleteMilestoneToDo", Prefix, function(Code, Content) {
			MProjects.ViewDetails($('#' + Prefix + 'ProjectsID').val());
			return true;
		}, Parms) == false) {
			alert(CForm.GetLastError());
	
			return false;
		}
	}

	return true;
}

//-----------------------------------------------------------------------------
MProjects.DeleteToDo = function(Prefix, ToDoID) {
	var Parms = {
		"ToDoID" : ToDoID
	};
	
	if(confirm("Are you sure you want to delete this To-Do?")) {
		if(CForm.Submit("Projects", "Module", "DeleteToDo", Prefix, function(Code, Content) {
			MProjects.ViewDetails($('#' + Prefix + 'ProjectsID').val());
			return true;
		}, Parms) == false) {
			alert(CForm.GetLastError());
	
			return false;
		}
	}

	return true;
}

//-----------------------------------------------------------------------------
MProjects.AddFile = function(Prefix) {
	if(CForm.Submit("Projects", "Module", "AddFile", Prefix, function(Code, Content) {
		MProjects.ViewResources($('#' + Prefix + 'ProjectsID').val());
		return true;
	}) == false) {
		alert(CForm.GetLastError());

		return false;
	}

	return true;
}

//-----------------------------------------------------------------------------
MProjects.AddMessage = function(Prefix) {
	if(CForm.Submit("Projects", "Module", "AddMessage", Prefix, function(Code, Content) {
		MProjects.ViewMessages($('#' + Prefix + 'ProjectsID').val());
		return true;
	}) == false) {
		alert(CForm.GetLastError());

		return false;
	}

	return true;
}

//-----------------------------------------------------------------------------
MProjects.AddMilestone = function(Prefix) {
	if(CForm.Submit("Projects", "Module", "AddEditMilestone", Prefix, function(Code, Content) {
		MProjects.ViewDetails($('#' + Prefix + 'ProjectsID').val());
		$().scrollTo('.Body', 500);
		return true;
	}) == false) {
		alert(CForm.GetLastError());

		return false;
	}

	return true;
}

//-----------------------------------------------------------------------------
MProjects.AddMilestoneToDo = function(Prefix) {
	if(CForm.Submit("Projects", "Module", "AddEditMilestoneToDo", Prefix, function(Code, Content) {
		MProjects.ViewDetails($('#' + Prefix + 'ProjectsID').val());
		$().scrollTo('.Body', 500);
		return true;
	}) == false) {
		alert(CForm.GetLastError());

		return false;
	}

	return true;
}

//-----------------------------------------------------------------------------
MProjects.AddMilestoneToDoList = function(Prefix) {
	if(CForm.Submit("Projects", "Module", "AddMilestoneToDoList", Prefix, function(Code, Content) {
		MProjects.ViewDetails($('#' + Prefix + 'ProjectsID').val());
		$().scrollTo('.Body', 500);
		return true;
	}) == false) {
		alert(CForm.GetLastError());

		return false;
	}

	return true;
}

//-----------------------------------------------------------------------------
MProjects.AddProjectToDo = function(Prefix) {
	if(CForm.Submit("Projects", "Module", "AddEditProjectToDo", Prefix, function(Code, Content) {
		MProjects.ViewDetails($('#' + Prefix + 'ProjectsID').val());
		$().scrollTo('.Body', 500);
		return true;
	}) == false) {
		alert(CForm.GetLastError());

		return false;
	}

	return true;
}

//-----------------------------------------------------------------------------
MProjects.UpdateNotifications = function(Prefix) {
	if(CForm.Submit("Projects", "Module", "UpdateNotifications", Prefix, function(Code, Content) {
		MProjects.ViewNotifications($('#' + Prefix + 'ProjectsID').val());
		$().scrollTo('.Body', 500);
		return true;
	}) == false) {
		alert(CForm.GetLastError());

		return false;
	}

	return true;
}

//-----------------------------------------------------------------------------
MProjects.AddProductType = function(Text) {
	function OnDone(Code, Content) {
		if(Code > 0) {
			return "{id:" + Code + ", text:" + Text + "}";
		} else {
			alert(Content);
			return null;
		}
	}
	
	var Parms = {};
	Parms["Name"] = Text;
	
	CAJAX.Add("Projects", "Module", "AddProductType", Parms, OnDone);
}

//=============================================================================
MProjects.ViewDetails = function(ProjectID, Callback) {
	function OnDone(Code, Content) {
		if(Code > 0) {
			$("#ProjectDetailsHeader").html($("#Project" + ProjectID + "Header").val());
			$("#ProjectDetailsContainer").html(Content);
			if(Callback) {
				Callback();
			} else {
				$().scrollTo('.Body', 500);
			}
		}
	}

	var Parms = {};
	Parms["ProjectID"]			= ProjectID;

	CAJAX.Add("Projects", "Module", "ViewDetails", Parms, OnDone);
}

//------------------------------------------------------------------------------
MProjects.ViewMessages = function(ProjectID, Keywords) {
	function OnDone(Code, Content) {
		if(Code > 0) {
			$("#MessageBoardHeader").html($("#Project" + ProjectID + "Header").val());
			$("#ProjectMessagesContainer").html(Content);
		}
	}

	var Parms = {};
	Parms["ProjectID"]			= ProjectID;
	if(typeof Keywords != "undefined") Parms["Keywords"]			= Keywords;

	CAJAX.Add("Projects", "Module", "ViewMessages", Parms, OnDone);
}

//------------------------------------------------------------------------------
MProjects.ViewResources = function(ProjectID) {
	function OnDone(Code, Content) {
		if(Code > 0) {
			$("#ResourceCenterHeader").html($("#Project" + ProjectID + "Header").val());
			$("#ProjectResourcesContainer").html(Content);
		}
	}

	var Parms = {};
	Parms["ProjectID"]			= ProjectID;

	CAJAX.Add("Projects", "Module", "ViewResources", Parms, OnDone);
}

//------------------------------------------------------------------------------
MProjects.ViewNotifications = function(ProjectID) {
	function OnDone(Code, Content) {
		if(Code > 0) {
			$("#NotificationsHeader").html($("#Project" + ProjectID + "Header").val());
			$("#ProjectNotificationsContainer").html(Content);
		}
	}

	var Parms = {};
	Parms["ProjectID"]			= ProjectID;

	CAJAX.Add("Projects", "Module", "ViewNotifications", Parms, OnDone);
}

//=============================================================================
MProjects.LoadDefaultMilestone = function(Prefix, MilestoneID) {
	var Parms = {
		"MilestoneID"	: MilestoneID
	}
	
	function OnDone(Code, Content) {
		if(Code > 0) {
			var Data = JSON.parse(Content);
			$('#' + Prefix + 'Name').val(Data["Name"]);
			$('#' + Prefix + 'Summary').val(htmlspecialchars_decode(Data["Summary"]));
			$('#' + Prefix + 'PlantAllocated').val(Data["PlantAllocated"]);
		} else {
			alert("Error loading Milestone info");
		}
	}
	
	if(MilestoneID > 0) {
		CAJAX.Add("Projects", "Module", "LoadDefaultMilestone", Parms, OnDone);
	} else {
		$('#' + Prefix + 'Name').val("");
		$('#' + Prefix + 'Summary').val("");
		$('#' + Prefix + 'PlantAllocated').val("");
	}
}

//-----------------------------------------------------------------------------
MProjects.LoadDefaultToDo = function(Prefix, ToDoID) {
	var Parms = {
		"ToDoID"	: ToDoID
	}
	
	function OnDone(Code, Content) {
		if(Code > 0) {
			var Data = JSON.parse(Content);
			$('#' + Prefix + 'Name').val(Data["Name"]);
			$('#' + Prefix + 'Comment').val(htmlspecialchars_decode(Data["Comment"]));
			if(Data["CommentRequired"] != $('#' + Prefix + 'CommentRequired').val()) $('#' + Prefix + 'CommentRequired_YesNo').click();
		} else {
			alert("Error loading To Do info");
		}
	}
	
	if(ToDoID > 0) {
		CAJAX.Add("Projects", "Module", "LoadDefaultToDo", Parms, OnDone);
	} else {
		$('#' + Prefix + 'Name').val("");
		$('#' + Prefix + 'Comment').val("");
		if($('#' + Prefix + 'CommentRequired').val() == 1) $('#' + Prefix + 'CommentRequired_YesNo').click();
	}
}

//------------------------------------------------------------------------------
MProjects.LoadDefaultToDoList = function(Prefix, ToDoListID) {
	var Parms = {
		"ToDoListID"	: ToDoListID
	}
	
	CAJAX.Add("Projects", "Module", "GetToDoListMembers", Parms, function(Code, Content){
		if(Code > 0) {
			$('#MilestoneToDoListMembers').html("List Members:<ul>" + Content + "</ul>");
		} else {
			alert("Error loading To Do List info");
		}
	});
}

//=============================================================================
MProjects.Window_Delete = function(ID) {
	CWindow.Open("Delete Project", 300, 170, "Projects", "Module", "Window_Delete", {"ID" : ID});
}

//=============================================================================
MProjects.ShowPreviewBox = function(Element, Type, RecordID) {
	var Parms = {
		"Type"  	: Type,
		"RecordID" 	: RecordID
	};
	
	if($(".PreviewBox").get(0) == undefined) {
		$(document.body).append("<div class='PreviewBox'></div>");
	}
	
	CAJAX.Add("Projects", "Module", "GetPreviewBoxData", Parms, function(Code, Content) {
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
				$(".PreviewBox").animate({
					"opacity" : 0
				});
			});				
		}
	});	
}

//=============================================================================
MProjects.WatchInputs = function() {
	MProjects.FirstLoad = true;

	$("#AddUserButton").bind("click", function() {
		var UsersID = $("#AddUserID").val();
		var Name	= $("#AddUserID").selectedTexts();
		var Role	= "";
		
		Name = Name[0];

		MProjects.AddAssignedUser(UsersID, Name, Role);
	});
	
	$("#InstitutionsID").bind("change", function() {
		var ID = parseInt($(this).val());
		
		if(ID == 0) {
			$(this).parent().parent().parent().find("input[id^=Institution]").removeAttr("disabled").val("");
		}else{
			$(this).parent().parent().parent().find("input[id^=Institution]").attr("disabled", "disabled");
		}
		
		MProjects.UpdateDropdowns();
	}).trigger("change");
	
	$("#InstitutionsUsersID").bind("change", function() {
		var ID = parseInt($(this).val());
		
		if(ID == 0) {
			$(this).parent().parent().parent().find("[id^=Institution]").removeAttr("disabled").val("");
		}else{
			$(this).parent().parent().parent().find("[id^=Institution]").attr("disabled", "disabled");
			$(this).removeAttr("disabled");
		}		
		
		MProjects.UpdateReadOnlyInfo();	
	}).trigger("change");	
	
	$(".SaveButton").bind("click", function() { MProjects.Save("Save") });
	$(".ABAdvanceButton").bind("click", function() { 
		if(confirm("Are you sure you want to advance this AB to the next step?") == false) return;
		
		MProjects.Save("Advance");
	});	
	
	//$("#ProductSolutionsID").css("width", "180px").after("<div class='ABUniqueIcon'></div>");
	$("#InstitutionsID").css("width", "180px").after("<div class='ABUniqueIcon'></div>");
	$("#InstitutionsUsersID").css("width", "180px").after("<div class='ABUniqueIcon'></div>");
	
	//$("#ProductSolutionName").bind("keydown", MProjects.CheckForUnique);
	$("#InstitutionName").bind("keydown", MProjects.CheckForUnique);
	$("#InstitutionContactFirstName").bind("keydown", MProjects.CheckForUnique);
	$("#InstitutionContactLastName").bind("keydown", MProjects.CheckForUnique);
	
	MProjects.FirstLoad = false;
	
	MProjects.UpdateReadOnlyInfo();
}

//-----------------------------------------------------------------------------
MProjects.AddAssignedUser = function(UsersID, Name, Role) {
	var HTML = "<div rel='" + UsersID + "'>";
	
	HTML += Name;
	HTML += "<select rel='Role'></select>";
	HTML += "</div>";
	
	var NewUser = $(HTML).appendTo(".AssignedUserList");
	
	$(NewUser).find("select").addOption(MProjects.AssignedUsersRoles);
	$(NewUser).find("select").selectOption(Role);
	
	$(NewUser).bind("click", function() {
		if(confirm("Are you sure you want to remove this Assigned User?") == false) return;
		
		$(this).remove();
	});
}

//-----------------------------------------------------------------------------
MProjects.AddISBN = function(ISBN) {
	var Element = $("<div><input type='text' style='width: 180px;'/> <div class='Icon_Delete' style='float: right; margin-top: 4px;'></div></div>").appendTo(".ISBNList");
	
	$(Element).find("input").val(ISBN);
	$(Element).find(".Icon_Delete").click(function() {
		if(confirm("Are you sure you want to remove this ISBN?") == false) return;
		
		$(this).parent().remove();
	});
}

//-----------------------------------------------------------------------------
MProjects.AddProductSolution = function(ID) {
	var Element = $("<div><select rel='ID' style='width: 130px;'></select> <input type='text' rel='Name' style='width: 120px;'/> <input type='text' rel='Price' style='width: 40px;'/> <div class='Icon_Delete' style='float: right; margin-top: 4px;'></div></div>").appendTo(".ProductSolutionsList");
	
	$(Element).find("select").addOption(MProjects.ProductSolutions);	
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
		
		MProjects.UpdateReadOnlyInfo();		
	}).trigger("change");
}

//-----------------------------------------------------------------------------
MProjects.UpdateProductSolutionsArray = function() {
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
MProjects.UpdateDropdowns = function() {
	MProjects.UpdateProductSolutionsArray();

	var Parms = {
		"InstitutionsID" : $("#InstitutionsID").val()
	};
	
	$("#InstitutionsUsersID").attr("disabled", "disabled");
	$(".ProductSolutionsList select").attr("disabled", "disabled");	
	
	CAJAX.Add("Projects", "Module", "GetInstitutionData", Parms, function(Code, Content) {
		if(Code == 0) {
			console.log(Content);
		}else{
			var Data = JSON.parse(Content);
			
			MProjects.ProductSolutions = Data.ProductSolutions;
			
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
MProjects.UpdateReadOnlyInfo = function() {
	if(MProjects.FirstLoad) return;
	
	MProjects.UpdateProductSolutionsArray();

	var Parms = {
		"ProductSolutions" 		: $("#ProductSolutions").val(),
		"InstitutionsID" 		: $("#InstitutionsID").val(),
		"InstitutionsUsersID" 	: $("#InstitutionsUsersID").val()			
	};

	CAJAX.Add("Projects", "Module", "GetReadOnlyInfo", Parms, function(Code, Content) {
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
MProjects.CheckForUnique = function() {
	if(MProjects.CheckForUniqueTimer) clearTimeout(MProjects.CheckForUniqueTimer);

	MProjects.CheckForUniqueTimer = setTimeout(function() {
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

		CAJAX.Add("Projects", "Module", "CheckForUnique", Parms, function(Code, Content) {
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
MProjects.Save = function(Prefix) {
	if(CForm.Submit("Projects", "Module", "Save", Prefix, function(Code, Content) {
		if(Code == 0) {
			alert(Content);
		}else{
			// Update Header
			//var Data = JSON.parse(Content);
			//$('#Project' + Data.ID + 'Header').val("<strong>" + Data.ProductNumber + " // " + Data.School + "</strong><br><span style='font-size:11px; color:#0685c5; font-style:italic;'>" + Data.Title + "</span>");
			
			// Refresh
			MProjects.ViewDetails($('#' + Prefix + 'ID').val());
			//$().scrollTo('.Body', 500);
			$('html, body').animate({ scrollTop : $(".Body").offset().top }, 500, 'swing');
		}

		return true;
	}))

	return true;
}

//=============================================================================
$(MProjects.OnInit);

//=============================================================================
