<?
	//$Project = $this->Parent->TableObject;
	
	$ProjectID = intval($_POST["ProjectID"]);
	
	$_GET["CSearch_Keywords"] = "";
	if(@$_POST["Keywords"]) {
		$_GET["CSearch_Keywords"] = $_POST["Keywords"];
	}
	
	$Prefix = "NewMessageForm".time();
	$EditPrefix = "EditMessageForm".time();
	CForm::SetPrefix($Prefix);	
?>
	
	<div class='Button' value='AddMessage' onClick="$('#AddMessage').slideDown();" style='float:left; position:relative; margin-left:0px; padding:0px 10px; margin-top:20px; margin-bottom:-38px; z-index:1000;'>Add New</div>
	<form id='SearchForm' method='get' style='top:0px;' onSubmit="MProjects.ViewMessages(<?=$ProjectID;?>, $('#MessagesQuery').val()); return false;">
		<input id='MessagesQuery' name='CSearch_Keywords' type='text' value='<?=htmlspecialchars($_GET["CSearch_Keywords"]);?>' placeholder='search'>
		<input id='MessagesQuerySubmit' type='submit' value=''>
	</form>
	<div class='AddMessage' id='AddMessage' style='z-index:1001;'>
		<div class='ProjectWrapper'>
			<div class='ProjectContainer'>
				<center>
					<table style='width:95%;'>
						<?
							echo CForm::AddRow("<div class='CloseAddMessage' id='CloseAddMessage' onClick=\"$('#AddMessage').slideUp();\"></div>");
							echo CForm::AddTextbox("Title", "Title", "");
							echo CForm::AddTextarea("Content", "Content", "");
							echo CForm::AddYesNo("Send Notification", "SendNotification", 1, "YesNo");
							echo CForm::AddHidden("ProjectsID", $ProjectID);
						?>
						<tr>
							<td colspan='2'><div style='position:relative; height:20px;'><input type='button' class='CWindow_Save' style='bottom:2px;' onClick="if(MProjects.AddMessage('<?=$Prefix;?>')) $('#AddMessage').slideUp();" value='Save'/></div></td>
						</tr>
					</table>
				</center>
			</div>
		</div>
	</div>
	
	<? CForm::SetPrefix($EditPrefix); ?>
	<div class='AddMessage' id='EditMessage' style='z-index:1001;'>
		<div class='ProjectWrapper'>
			<div class='ProjectContainer'>
				<center>
					<table id="EditMessageTable" style='width:95%;'>
						<?
							echo CForm::AddRow("<div class='CloseAddMessage' id='CloseAddMessage' onClick=\"$('#EditMessage').slideUp();\"></div>");
							echo CForm::AddTextbox("Title", "Title", "");
							echo CForm::AddTextarea("Content", "Content", "");
							echo CForm::AddHidden("MessageID", "");
							echo CForm::AddHidden("ProjectsID", $ProjectID);
						?>
						<tr>
							<td colspan='2'><div style='position:relative; height:20px;'><input type='button' class='CWindow_Save' style='bottom:2px;' onClick="if(MProjects.EditMessage('<?=$EditPrefix;?>')) $('#EditMessage').slideUp();" value='Save'/></div></td>
						</tr>
					</table>
				</center>
			</div>
		</div>
	</div>
	<script type='text/javascript'>
		function hideAll()
		{
			$('#EditMessage').slideUp();
			$('#AddtMessage').slideUp();
		}
		
		function EditProjectMessage(event, messageId)
		{
			hideAll();
			var Parms = {};
			
			Parms["MessageId"] = messageId;
			CAJAX.Add('Projects', 'Module', 'FindMessage', Parms, function(Code, Content) {
				if(Code == 0)
					CPageNotice.Add("Error", Content);
				else
				{
					var Data = JSON.parse(Content);
					
					$('#EditMessageTable').find('#<? echo $EditPrefix?>Title').val(Data.Title);
					$('#EditMessageTable').find('#<? echo $EditPrefix?>Content').text(Data.Content);
					$('#EditMessageTable').find('#<? echo $EditPrefix?>MessageID').val(Data.ID);
					
					$('#EditMessage').slideDown();
				}
				return false;
			});		
			
			stopPropagation(event);
			return false;
		}
		
		function DeleteProjectMessage(event, messageId)
		{
			hideAll();
			event.stopPropagation(event);
			var a = confirm('Please confirm to delete the message');
			if(a==true)
			{
				var Parms = {};
				Parms["MessageId"]			= messageId;
				CAJAX.Add('Projects', 'Module', 'DeleteMessage', Parms, function(Code, Content) {
					if(Code == 0)
						CPageNotice.Add("Error", Content);
					else
					{
						$('tr[itemID="'+messageId+'"]').remove();
						CPageNotice.Add("Success", Content);
					}
					return false;
				});				
			}
			
			return false;
		}
		
		function stopPropagation(e) {
			if (e.stopPropagation) {
				e.stopPropagation();
			} else {
			   e.cancelBubble = true;
			   e.returnValue = false;
			}
		}
	</script>
<?

	function OnTitle($Value, $Row) {
		return "
		<div style='position:relative;'>".$Value."
			<div id='Message".$Row->ID."' style='display:none;position:relative;top:100%;border:1px dotted #d1d1d1;padding:4px;margin-top:8px;'>
				".nl2br($Row->Content)."
			</div>
		</div>
		";
	}

	function OnCreated($Value) {
		return date('n/j/Y g:ia', $Value);
	}
	
	function OnUser($Value) {
		$User = new CUsers();
		$User->OnLoad($Value);
		return $User->LastName . ", " . $User->FirstName;
	}

	$Search = new CSearch("ProjectsMessages");
	
	$Search->AddColumn("Title", "Title", "55%;text-align:left;vertical-align:top;", CSEARCHCOLUMN_SEARCHTYPE_LOOSE, "", "", "", "OnTitle");
	$Search->AddColumn("Created", "Created", "15%;text-align:left;vertical-align:top;", CSEARCHCOLUMN_SEARCHTYPE_LOOSE, "", "", "", "OnCreated");
	$Search->AddColumn("By", "CreatedUsersID", "15%;text-align:left;vertical-align:top;", CSEARCHCOLUMN_SEARCHTYPE_LOOSE, "", "", "", "OnUser");
	$Search->ShowEdit("EditProjectMessage");
	$Search->ShowDelete("DeleteProjectMessage");	
	
	$Search->AddHiddenColumn("Content", CSEARCHCOLUMN_SEARCHTYPE_LOOSE);
	
	$Search->SetOnClick("$('#Message[[ID]]').toggle(500);");
	
	$Search->AddRestriction("ProjectsID",$ProjectID);
	
	$Search->SetDefaultColumn(1, 1);
	
	$Search->OnInit();
	
	$Search->OnRender();
	$Search->OnRenderPages();
	
	//echo "<pre>";
	//var_dump($_SERVER);
	//echo "</pre>";
	
	
?>
