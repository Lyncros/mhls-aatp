//=============================================================================
MUsers = {};

//=============================================================================
MUsers.AddEdit = function(WindowID, Prefix) {
	if(CForm.Submit("Users", "Module", "AddEdit", Prefix, function(Code, Content) {
		if(Code > 0) CWindow.Close(WindowID);

		return true;
	}) == false) {
		alert(CForm.GetLastError());

		return false;
	}

	return true;
}

//-----------------------------------------------------------------------------
MUsers.Delete = function(WindowID, Prefix) {
	if(CForm.Submit("Users", "Module", "Delete", Prefix, function(Code, Content) {
		if(Code > 0) CWindow.Close(WindowID);

		return true;
	}) == false) {
		alert(CForm.GetLastError());

		return false;
	}

	return true;
}

//=============================================================================
MUsers.Window_AddEdit = function(ID) {
	var Title = "Add User";

	if(ID > 0) {
		Title = "Edit User";
	}

	CWindow.Open(Title, 640, 400, "Users", "Module", "Window_AddEdit", {"ID" : ID});
}

//-----------------------------------------------------------------------------
MUsers.Window_Delete = function(ID) {
	CWindow.Open("Delete User", 300, 150, "Users", "Module", "Window_Delete", {"ID" : ID});
}

//=============================================================================
MUsers.NumPayTypes = 0;

MUsers.WatchForm = function() {
	$("#Type").bind("change", function() {
		if($(this).val() == "Provider") {
			$("#ProviderForm").fadeIn("fast");
		}else{
			$("#ProviderForm").fadeOut("fast");
		}
	});

	$("#ProviderType").bind("change", function() {
		MUsers.UpdateFormAccordingToProviderType();
	}).trigger("click");

	$(".AddPayType").bind("click", MUsers.AddPayType);
}

//-----------------------------------------------------------------------------
MUsers.AddPayType = function(PayTypesID, Rate, SecondRate, RateBreak, IEA, IEASecondRate, IEABreak) {
	if(PayTypesID == undefined)			PayTypesID = 0;
	if(Rate == undefined)				Rate = 0;
	if(SecondRate == undefined)			SecondRate = 0;
	if(RateBreak == undefined)			RateBreak = 0;
	if(IEA == undefined)				IEA = 0;
	if(IEASecondRate == undefined)		IEASecondRate = 0;
	if(IEABreak == undefined)	IEABreak = 0;

	var Content = "\
	<tr class='PayType'>\
		<td align='center'><select rel='Type' id='PayType_Type_" + MUsers.NumPayTypes + "' name='PayType_Type_" + MUsers.NumPayTypes + "' style='width: 95%'></select></td>\
		<td align='center'><input type='text' id='PayType_Rate_" + MUsers.NumPayTypes + "' name='PayType_Rate_" + MUsers.NumPayTypes + "' style='width: 95%; text-align: center;' rel='Rate'/></td>\
		<td align='center'><input type='text' id='PayType_SecondRate_" + MUsers.NumPayTypes + "' name='PayType_SecondRate_" + MUsers.NumPayTypes + "' style='width: 95%; text-align: center;' rel='SecondRate'/></td>\
		<td align='center'><input type='text' id='PayType_RateBreak_" + MUsers.NumPayTypes + "' name='PayType_RateBreak_" + MUsers.NumPayTypes + "' style='width: 95%; text-align: center;' rel='RateBreak'/></td>\
		<td align='center'><input type='text' id='PayType_IEA_" + MUsers.NumPayTypes + "' name='PayType_IEA_" + MUsers.NumPayTypes + "' style='width: 95%; text-align: center;' rel='IEA'/></td>\
		<td align='center'><input type='text' id='PayType_IEASecondRate_" + MUsers.NumPayTypes + "' name='PayType_IEASecondRate_" + MUsers.NumPayTypes + "' style='width: 95%; text-align: center;' rel='IEASecondRate'/></td>\
		<td align='center'><input type='text' id='PayType_IEABreak_" + MUsers.NumPayTypes + "' name='PayType_IEABreak_" + MUsers.NumPayTypes + "' style='width: 95%; text-align: center;' rel='IEABreak'/></td>\
	</tr>\
	";

	var Element = $(Content).insertAfter(".PayTypes > tbody > tr:last-child");

	MUsers.UpdateFormAccordingToProviderType();

	$(Element).find("[rel=Type]").data("Value", PayTypesID).val(PayTypesID);
	$(Element).find("[rel=Rate]").val(Rate);
	$(Element).find("[rel=SecondRate]").val(SecondRate);
	$(Element).find("[rel=RateBreak]").val(RateBreak);
	$(Element).find("[rel=IEA]").val(IEA);
	$(Element).find("[rel=IEASecondRate]").val(IEASecondRate);
	$(Element).find("[rel=IEABreak]").val(IEABreak);

	MUsers.NumPayTypes++;
}

//-----------------------------------------------------------------------------
MUsers.UpdateFormAccordingToProviderType = function() {
	var Type = $("#ProviderType").val();

	$(".PayType").find("[rel=Type]").each(function() {
		$(this).data("Value", $(this).val());
	});

	$(".PayType").find("[rel=Type]").removeOption(/./).addOption({"0" : "-- Select One --"});

	if(Type == "W-2") {
		$("#BenefitsTable").slideDown("fast");
		$(".PayType").find("[rel=Type]").addOption(MUsers.PayTypes["W-2"]).sortOptions();

		$(".PayType").find("[rel=IEA]").removeAttr("disabled");
		$(".PayType").find("[rel=IEASecondRate]").removeAttr("disabled");
		$(".PayType").find("[rel=IEABreak]").removeAttr("disabled");
	}else{
		$("#BenefitsTable").slideUp("fast");
		$(".PayType").find("[rel=Type]").addOption(MUsers.PayTypes["1099"]).sortOptions();

		$(".PayType").find("[rel=IEA]").attr("disabled", "disabled");
		$(".PayType").find("[rel=IEASecondRate]").attr("disabled", "disabled");
		$(".PayType").find("[rel=IEABreak]").attr("disabled", "disabled");
	}

	$(".PayType").find("[rel=Type]").each(function() {
		$(this).val($(this).data("Value"));
	});
}

//=============================================================================
MUsers.Save = function() {
	if(CForm.Submit("Users", "Module", "Save", "", function(Code, Content) {
		if(Code == 0) {
			//alert(Content);
		}else{
			$(document.body).scrollTo(0, 800);

			$("#ID").val(Code);
		}

		return true;
	}) == false) {
		alert(CForm.GetLastError());

		return false;
	}

	return true;
}

//=============================================================================
