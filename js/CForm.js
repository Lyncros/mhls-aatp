//=============================================================================
CForm = {};

//=============================================================================
CForm.Error = "";

CForm.DebugMode = false;

//-----------------------------------------------------------------------------
CForm.OnInit = function() {
	try {
		//Register Debug Command with Console
		function FormDebug(Parts) {
			if(Parts[1] == 1) {
				CForm.DebugMode = true;

				PConsole.AddNotice("Debugging Turned On", "Notice");
			}else{
				CForm.DebugMode = false;

				PConsole.AddNotice("Debugging Turned Off", "Notice");
			}
		}

		PConsole.AddCommand("CFormDebug", FormDebug, "Turn on Debugging Mode");
	}catch(err) {}
}

//=============================================================================
CForm.Stylize = function() {
	$(".CForm_Textbox").each(function() {
		var ID = $(this).attr("id");

		$(this).wrap("<div class='CFormS_Textbox'></div>");
	});

	$(".CForm_Dropdown").each(function() {
		var ID = $(this).attr("id");

		var SelTexts = $(this).selectedTexts();

		$(this).before("<div class='CFormS_Dropdown' id='" + ID + "_Dropdown'><div class='CFormS_Dropdown_Text' id='" + ID + "_Text'>" + SelTexts[0] + "</div><div class='CFormS_Dropdown_Arrow' id='" + ID + "_Arrow'></div><div class='CFormS_Dropdown_List' id='" + ID + "_List'></div></div>");
		$(this).after("<input type='hidden' id='" + $(this).attr("id") + "' name='" + $(this).attr("id") + "' value='" + $(this).attr("value") +"'/>");

		$(this).attr("id", $(this).attr("id") + "_Old");
		$(this).attr("name", $(this).attr("name") + "_Old");
		$(this).attr("class", "");

		$(this).css("display", "none");

		$("#" + ID + "_Arrow").bind("click", function() {
			$(".CFormS_Dropdown_List").each(function(i) {
				if($(this).attr("id") == ID + "_List") return;

				$(this).slideUp("fast");
			});

			$("#" + ID + "_List").slideToggle("normal");
		});

		(function(ID, OldDrop) {
			$("#" + ID).bind("change", function() {
				$(OldDrop).trigger("change");
			});
		})(ID, $(this));

		$(this).find("option").each(function(i) {
			$("#" + ID + "_List").append("<div class='CFormS_Dropdown_List_Item' id='" + ID + "_List_Item_" + i + "' rel='" + $(this).attr("value") + "'>" + $(this).html() + "</div>");

			$("#" + ID + "_List_Item_" + i).bind("click", function() {
				$("[id^=" + ID + "_List_Item]").removeClass("CFormS_Dropdown_List_Item_Selected");

				$(this).addClass("CFormS_Dropdown_List_Item_Selected");				
					
				$("#" + ID).attr("value", $(this).attr("rel"));
				$("#" + ID + "_Text").html($(this).html());

				$("#" + ID + "_List").slideUp("fast");

				$("#" + ID).trigger("change");
			}).bind("mouseover", function() {
				$(this).addClass("CFormS_Dropdown_List_Item_Hover");
			}).bind("mouseout", function() {
				$(this).removeClass("CFormS_Dropdown_List_Item_Hover");
			});
		});
	});
}

//=============================================================================
CForm.UpdateTimestamp = function(ID) {
	var ElementHidden = $("#" + ID).get(0);

	var Month	= parseInt($("#" + ID + "_Month").attr("value"));
	var Day		= parseInt($("#" + ID + "_Day").attr("value"));
	var Year	= parseInt($("#" + ID + "_Year").attr("value"));

	if(Month == 0) {
		$("#" + ID + "_Day").attr("disabled", "disabled");
		$("#" + ID + "_Year").attr("disabled", "disabled");

		ElementHidden.value = "";
	}else{
		$("#" + ID + "_Day").removeAttr("disabled");
		$("#" + ID + "_Year").removeAttr("disabled");

		var TempDate = new Date();

		TempDate.setTime(0);
		TempDate.setHours(0);
		TempDate.setMinutes(0);
		TempDate.setSeconds(0);

		TempDate.setMonth(Month - 1, Day);
		TempDate.setDate(Day);
		TempDate.setFullYear(Year);

		ElementHidden.value = (TempDate.getTime() / 1000);
	}
}

//=============================================================================
CForm.Submit = function(Request, Type, Action, Prefix, Callback, Parms) {
	if(Parms == undefined) Parms = Array();

	var Elements = $("[id^=" + Prefix + "]").get();

	var Valid = true;

	for(var i = 0;i < Elements.length;i++) {
		var Element = Elements[i];

		$(Element).removeClass("CForm_Error");

		if(Valid == false) continue;

		if(Element.title.length > 0 && Element.value == "") {

			if($(Element).attr("rel") == "Autocomplete") {
				$("#" + Element.id + "_Search").focus();
				$("#" + Element.id + "_Search").addClass("CForm_Error");
			}else{
				$(Element).addClass("CForm_Error");
				Element.focus();
			}

			CForm.Error = Element.title;

			Valid = false;
		}

		if($(Element).attr("rel") == "CForm_RTE") {
			Element.value = CKEDITOR.instances[Element.id].getData();
		}

		if($(Element).attr("multiple")) {
			var NewValue = Array();

			//var SelValues = $(Element).selectedValues();
			var SelValues = $(Element).val();

			Parms[Element.id.replace(Prefix, "")] = JSON.stringify(SelValues);
		}else{
			Parms[Element.id.replace(Prefix, "")] = Element.value;
		}
	}

	if(CForm.DebugMode) {
		try {
			PConsole.AddNotice("CForm.Submit :: Submitting Form", "Notice");

			for(var i in Parms) {
				PConsole.AddNotice(" - [" + i + "] = " + Parms[i], "Notice");
			}
		}catch(err) {}
	}

	if(Valid == false) {
		return false;
	}

	if(!Callback) {
		Callback = CForm.SubmitCallback;
	}

	CAJAX.Add(Request, Type, Action, Parms, function(Code, Content) {
		if(Callback) {
			if(Callback(Code, Content)) {
				CForm.SubmitCallback(Code, Content);
			}
		}else{
			CForm.SubmitCallback(Code, Content);
		}
	});

	return true;
}

CForm.SubmitCallback = function(Code, Content) {
	if(Code == 0) {
		alert("There was an error submitting this form:\n\n" + Content);
	}else{
		CPageNotice.Add("Success", Content);

		CRefresh.Suggest();
	}
}

//=============================================================================
CForm.GetLastError = function() {
	return CForm.Error;
}

//=============================================================================
CForm.Prefix	= "";
CForm.Tooltip	= "";

//-----------------------------------------------------------------------------
CForm.SetPrefix = function(Prefix) {
	CForm.Prefix = Prefix;
}

//-----------------------------------------------------------------------------
CForm.AddHidden = function(Name, FormName, Value) {
	if(Value == undefined) {
		Value = "";
	}

	return "\
	<tr>\
		<td class='CForm_Value' valign='top' colspan='2'><input type='hidden' name='" + CForm.Prefix + FormName + "' id='" + CForm.Prefix + FormName + "' value='" + Value + "'/></td>\
	</tr>\
	";

	CForm.ResetTooltip();
}

//-----------------------------------------------------------------------------
CForm.AddTextbox = function(Name, FormName, Value, Error) {
	if(Value == undefined) {
		Value = "";
	}

	return "\
	<tr>\
		<td align='right' class='CForm_Name' valign='top'><b onMouseOver=\"CForm.ShowTooltip('" + CForm.Tooltip + "');\" onMouseOut=\"CForm.HideTooltip();\">" + Name + ":</b>&nbsp;</td>\
		<td class='CForm_Value' valign='top'><input type='text' name='" + CForm.Prefix + FormName + "' id='" + CForm.Prefix + FormName + "' value='" + Value + "' title='" + Error + "' class='CForm_Textbox'/></td>\
	</tr>\
	";

	CForm.ResetTooltip();
}

//-----------------------------------------------------------------------------
CForm.AddTextarea = function(Name, FormName, Value, Error) {
	if(Value == undefined) {
		Value = "";
	}

	return "\
	<tr>\
		<td align='right' class='CForm_Name' valign='top'><b onMouseOver=\"CForm.ShowTooltip('" + CForm.Tooltip + "');\" onMouseOut=\"CForm.HideTooltip();\">" + Name + ":</b>&nbsp;</td>\
		<td class='CForm_Value' valign='top'><textarea name='" + CForm.Prefix + FormName + "' id='" + CForm.Prefix + FormName + "' title='" + Error + "'  class='CForm_Textarea'>" + Value + "</textarea></td>\
	</tr>\
	";

	CForm.ResetTooltip();
}

//-----------------------------------------------------------------------------
CForm.AddDropdown = function(Name, FormName, Values, SelectedValue, Error) {
	Content = "\
	<tr>\
		<td align='right' class='CForm_Name' valign='top'><b onMouseOver=\"CTooltip.Show('" + CForm.Tooltip + "');\" onMouseOut=\"CTooltip.Hide();\">" + Name + ":</b>&nbsp;</td>\
		<td class='CForm_Value' valign='top'>\
		<select name='" + CForm.Prefix + FormName + "' id='" + CForm.Prefix + FormName + "' title='" + Error + "' class='CForm_Dropdown'>";

	for(var i in Values) {
		Content += "<option value='" + i +"'";

		if(Values[i] == SelectedValue) {
			Content += "selected='selected'";
		}

		Content += ">" + Values[i] + "</option>";
	}

	Content += "\
		</select>\
		</td>\
	</tr>\
	";

	CForm.ResetTooltip();

	return Content;
}

//-----------------------------------------------------------------------------
CForm.AddAdvancedRTEControl = function(ID, Config) {
	setTimeout(function() { 
		$("#" + ID).markItUp(Config);
	}, 1000);
}

//-----------------------------------------------------------------------------
CForm.AddRTEControl = function(ID, Config) {
	setTimeout(function() { 
	    if(CKEDITOR.instances[ID]) {
			delete CKEDITOR.instances[ID];
		}

		CKEDITOR.replace(ID, Config);
	}, 1000);
}

//-----------------------------------------------------------------------------
CForm.AddYesNo = function(Name, FormName, SelectedValue, Error) {
	return CForm.AddDropdown(Name, FormName, {0 : "No", 1 : "Yes"}, SelectedValue, Error);
}

//-----------------------------------------------------------------------------
CForm.AddYesNoControl = function(ID, FormID) {
	var OnClick = function() {
		var HiddenInput = $("#" + FormID).get(0);

		if(HiddenInput.value == 0) {
			$("#" + ID).css("background-position", "0px -20px");
			HiddenInput.value = 1;
		}else{
			$("#" + ID).css("background-position", "0px 0px");
			HiddenInput.value = 0;
		}
	}

	$("#" + ID).bind("click", OnClick);

	var HiddenInput = $("#" + FormID).get(0);

	if(HiddenInput.value == 0) {
		$("#" + ID).css("background-position", "0px 0px");
	}else{
		$("#" + ID).css("background-position", "0px -20px");
	}
}

//-----------------------------------------------------------------------------
CForm.AddAutocomplete = function(ID, Request, Type, Action, Callback) {
	$("#" + ID + "_Search").autocomplete("AJAX.php", {
		width : "250px",
		onItemSelect : function(LI) {
			//ID - LI.extra[0]
			//Text - 

			$("#" + ID).attr("value", LI.extra[0]);
			$("#" + ID + "_Search").attr("value", ""); //Clear
			$("#" + ID + "_Text").attr("value", LI.innerHTML);

			$("#" + ID + "_Text").attr("alt", LI.innerHTML);
			$("#" + ID + "_Text").attr("title", LI.innerHTML);

			$("#" + ID + "_Search").focus();

			if(Callback) {
				Callback(LI.extra[0], LI.innerHTML);
			}
		},
		extraParams : {
			"AJAX_Request"	: Request,
			"AJAX_Type"		: Type,
			"AJAX_Action"	: Action
		}
	}); 
}

//-----------------------------------------------------------------------------
CForm.DualListboxUpdate = function(ID) {
	var Left	= $("#" + ID + "_Left");
	var Right	= $("#" + ID + "_Right");

	var Selected = $(Right).find("option");
	var Data = {};

	for(var i = 0;i < Selected.length;i++) {
		Data[ $(Selected[i]).attr("value") ] = $(Selected[i]).html();
	}

	$("#" + ID).attr("value", JSON.stringify(Data));
}

//-----------------------------------------------------------------------------
CForm.AddDualListboxControl = function(ID) {
	var Left	= $("#" + ID + "_Left");
	var Right	= $("#" + ID + "_Right");

	Left.bind("dblclick", function(e) {
		$(Left).copyOptions(Right, "selected");

		CForm.DualListboxUpdate(ID);
	});

	Right.bind("dblclick", function(e) {
		$(Right).removeOption(/./, true);

		CForm.DualListboxUpdate(ID);
	});

	CForm.DualListboxUpdate(ID);
}

//-----------------------------------------------------------------------------
CTooltip.Show = function() {
}

//-----------------------------------------------------------------------------
CTooltip.Hide = function() {
}

//-----------------------------------------------------------------------------
CForm.SetTooltip = function(Content) {
	CForm.Tooltip = Content;
}

//-----------------------------------------------------------------------------
CForm.ResetTooltip = function() {
	CForm.Tooltip = "";
}

//-----------------------------------------------------------------------------
CForm.AddUpload = function(Name, FormName) {
	Content = "\
	<tr>\
		<td align='right' class='CForm_Name' valign='top'><b onMouseOver=\"CTooltip.Show('" + CForm.Tooltip + "');\" onMouseOut=\"CTooltip.Hide();\">" + Name + ":</b>&nbsp;</td>\
		<td class='CForm_Value' valign='top'>\
		<table width='100%'>\
		<tr>\
			<!-- <td valign='top'><div id='" + CForm.Prefix + FormName + "_SWFUpload_Preview' class='CForm_Upload_Preview'></div></td> -->\
			<td width='100%' valign='top'>\
			<div id='" + CForm.Prefix + FormName + "_SWFUpload_Icon' class='CForm_Upload_Icon'></div>\
			<span class='CForm_Upload_Button' id='" + CForm.Prefix + FormName + "_SWFUpload' rel='SWFUpload'></span>\
			<input type='hidden' name='" + CForm.Prefix + FormName + "' id='" + CForm.Prefix + FormName + "'/>\
			<input type='hidden' name='" + CForm.Prefix + FormName + "Original' id='" + CForm.Prefix + FormName + "Original'/>\
			</td>\
		</tr>\
		</table>\
		<script language='Javascript'>CForm.AddUploadControl('" + CForm.Prefix + FormName + "_SWFUpload', '" + CForm.Prefix + FormName + "');</script>\
		</td>\
	</tr>";

	CForm.ResetTooltip();

	return Content;
}

//-----------------------------------------------------------------------------
CForm.AddUploadControl = function(ID, FormID) {
	var Icon = $("#" + ID + "_Icon").get(0);

	Icon.className = "CForm_Upload_Icon";

	$("#" + ID).each(function(Index, Element) {
		new SWFUpload({ 
			upload_url				: CURL.GetLocation() + "AJAX.php?AJAX_Request=CData&AJAX_Type=System&AJAX_Action=UploadTemp",
			flash_url				: "./js/swfupload/Flash/swfupload.swf",
			file_size_limit			: "20 MB",

			button_image_url		: CURL.GetLocation() + "Theme/Default/Default/XPButtonUploadText_61x22.png",
			button_placeholder_id	: Element.id,
			button_width			: 61,
			button_height			: 22,
			button_window_mode		: "transparent",
			//debug					: true,

			file_queued_handler		: function(file) {
			},
			file_queue_error_handler : function(file, errorCode, message) {
			},
			file_dialog_complete_handler : function(numFilesSelected, numFilesQueued) {
				if (numFilesSelected > 0) {
					$("#" + this.customSettings.cancelButtonId).attr("disabled", false);
				}

				this.startUpload();
			},
			upload_start_handler	: function(file) {
				Icon.className = "CForm_Upload_Icon_Progress";

				return true;
			}, 
			upload_progress_handler : function(file, bytesLoaded, bytesTotal) {
				var Percent = (bytesLoaded / bytesTotal) * 100;
					Percent = Percent.toFixed(2);

				$(Icon).html(Percent + "%");
			},
			upload_error_handler	: function(file, errorCode, message) {
				alert("There was an error uploading your file : (" + errorCode + ") : " + message);

				$(Icon).html("");

				Icon.className = "CForm_Upload_Icon_Error";

				return true;
			},
			upload_success_handler	: function(file, serverData) {
				var Parts = explode("\n", serverData, 2);

				Parts[0] = intval(Parts[0]);

				$(Icon).html("");

				if(Parts[0] <= 0) {
					alert("There was an error uploading your file : " + Parts[1] + "\nComplete Response : " + serverData);

					Icon.className = "CForm_Upload_Icon_Error";
				}else{
					$("#" + FormID).attr("value", Parts[1]);
					$("#" + FormID + "Original").attr("value", file["name"]);

					Icon.className = "CForm_Upload_Icon_Done";
				}

				return true;
			},
			upload_complete_handler : function(file) {
			},
			queue_complete_handler	: function(numFilesUploaded) {
			}
		}); 
	});
}

//-----------------------------------------------------------------------------
CForm.AddUploadMultipleControl = function(ID, FormID, Callback) {
	var Icon = $("#" + ID + "_Icon").get(0);

	Icon.className = "CForm_Upload_Icon";

	$("#" + ID).each(function(Index, Element) {
		new SWFUpload({ 
			upload_url				: CURL.GetLocation() + "AJAX.php?AJAX_Request=CData&AJAX_Type=System&AJAX_Action=UploadTemp",
			flash_url				: "./js/swfupload/Flash/swfupload.swf",
			file_size_limit			: "20 MB",

			button_image_url		: CURL.GetLocation() + "Theme/Default/Default/XPButtonUploadText_61x22.png",
			button_placeholder_id	: Element.id,
			button_width			: 61,
			button_height			: 22,
			button_window_mode		: "transparent",

			file_dialog_complete_handler : function(numFilesSelected, numFilesQueued) {
				if (numFilesSelected > 0) {
					$("#" + this.customSettings.cancelButtonId).attr("disabled", false);
				}

				this.startUpload();
			},
			upload_start_handler	: function(file) {
				Icon.className = "CForm_Upload_Icon_Progress";

				return true;
			}, 
			upload_progress_handler : function(file, bytesLoaded, bytesTotal) {
				var Percent = (bytesLoaded / bytesTotal) * 100;
					Percent = Percent.toFixed(2);

				var Stats = this.getStats();

				var NumFiles = Stats.files_queued + Stats.successful_uploads;

				$(Icon).html("(File: " + (Stats.successful_uploads + 1) + "/" + NumFiles + ") " + Percent + "%");
			},
			upload_error_handler	: function(file, errorCode, message) {
				alert("There was an error uploading your file : (" + errorCode + ") : " + message);

				$(Icon).html("");

				Icon.className = "CForm_Upload_Icon_Error";

				return true;
			},
			upload_success_handler	: function(file, serverData) {
				var Parts = explode("\n", serverData, 2);

				Parts[0] = intval(Parts[0]);

				$(Icon).html("");

				if(Parts[0] <= 0) {
					alert("There was an error uploading your file : " + Parts[1] + "\nComplete Response : " + serverData);

					Icon.className = "CForm_Upload_Icon_Error";
				}else{
					Icon.className = "CForm_Upload_Icon_Done";

					Callback(Parts[1], file["name"]);
				}

				this.startUpload();

				return true;
			},
			upload_complete_handler : function(file) {
			},
			queue_complete_handler	: function(numFilesUploaded) {
			}
		}); 
	});
}

//-----------------------------------------------------------------------------
CForm.AddDragUploadControl = function(ID, DragClass, Callback) {
	if(DragClass != "") {
		$("#" + ID).get(0).addEventListener("dragenter", function(e) {
			$("#" + ID).addClass(DragClass);
		}, false);

		$("#" + ID).get(0).addEventListener("dragexit", function(e) {
			$("#" + ID).removeClass(DragClass);
		}, false);
	}

	$("#" + ID).get(0).addEventListener("drop", function(e) {
	    var DataTransfer	= e.dataTransfer;
	    var Files			= DataTransfer.files;

		e.stopPropagation();
		e.preventDefault();

		if(Files.length <= 0) {
			//Supposed to Handle Data
			//DataTransfer
		}else{
			for (var i = 0; i < Files.length;i++) {
				(function(File) {
					var xhr				= new XMLHttpRequest();
					var BinaryReader	= new FileReader();

					var boundary = '------multipartformboundary' + (new Date).getTime();
					var dashdash = '--';
					var crlf     = '\r\n';

					var builder = '';

					builder += dashdash;
					builder += boundary;
					builder += crlf;

					builder += 'Content-Disposition: form-data; name="Filedata"';
					if (File.fileName) {
					  builder += '; filename="' + File.fileName + '"';
					}
					builder += crlf;

					builder += 'Content-Type: application/octet-stream';
					builder += crlf;
					builder += crlf; 

					builder += File.getAsBinary();
					builder += crlf;

					builder += dashdash;
					builder += boundary;
					builder += crlf;

					builder += dashdash;
					builder += boundary;
					builder += dashdash;
					builder += crlf;

					xhr.open("POST", CURL.GetLocation() + "AJAX.php?AJAX_Request=CData&AJAX_Type=System&AJAX_Action=UploadTemp", true);
					xhr.setRequestHeader('content-type', 'multipart/form-data; boundary=' + boundary);
					xhr.sendAsBinary(builder);        
					
					xhr.onload = function(e2) { 
						var Parts = explode("\n", xhr.responseText);
						
						if(parseInt(Parts[0]) == 0) {
							alert(Parts[1]);
						}else{
							if(Callback) {
								Callback(Parts[1], File.fileName);
							}
						}
					};
				})(Files[i]);
			}
		}
	}, false);

	$("#" + ID).get(0).addEventListener("dragover", function(e) {
		e.preventDefault();
	}, false);
}

//=============================================================================
CForm.AddCCControl = function(JID) {
	for(var i = 1;i < 4;i++) {
		$("#" + JID + i).data("Pos", i);

		$("#" + JID + i).bind("keydown", function(e) {
			var Pos = parseInt($(this).data("Pos"));

			if($(this).attr("value").length >= 4) {
				$("#" + JID + (Pos + 1)).focus();
			}
		});
	}
}

//=============================================================================
CForm.Button = {}

//-----------------------------------------------------------------------------
CForm.Button.OnMouseDown = function(Element) {
	if(Element.hasChildNodes() == false) return;

	for(var i = 0;i < Element.childNodes.length;i++) {
		var Child = Element.childNodes[i];

		if(Child.className == "CForm_Button_Left") {
			Child.style.backgroundPosition = "-12px 0px";
		}else
		if(Child.className == "CForm_Button_Right") {
			Child.style.backgroundPosition = "-18px 0px";
		}else
		if(Child.className == "CForm_Button_Content") {
			Child.style.backgroundPosition = "0px -52px";
		}
	}
}

//-----------------------------------------------------------------------------
CForm.Button.OnMouseUp = function(Element) {
	if(Element.hasChildNodes() == false) return;

	for(var i = 0;i < Element.childNodes.length;i++) {
		var Child = Element.childNodes[i];

		if(Child.className == "CForm_Button_Left") {
			Child.style.backgroundPosition = "0px 0px";
		}else
		if(Child.className == "CForm_Button_Right") {
			Child.style.backgroundPosition = "-6px 0px";
		}else
		if(Child.className == "CForm_Button_Content") {
			Child.style.backgroundPosition = "0px -26px";
		}
	}
}

//=============================================================================
$(CForm.OnInit);

//=============================================================================
