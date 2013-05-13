<?
	//$Project = $this->Parent->TableObject;
	
	$ProjectID = intval($_POST["ProjectID"]);
	
	CForm::RandomPrefix();
?>
	
	
	

<?

	function OnFilename($Value) {
		return "<a href='".$_SERVER["REQUEST_URI"]."ResourcesID=".$Value."' target='_blank'>View/Download</a>";
	}
	
	function OnCreated($Value) {
		return date('n/j/Y g:ia', $Value);
	}
	
	function OnUser($Value) {
		$User = new CUsers();
		$User->OnLoad($Value);
		return $User->LastName . ", " . $User->FirstName;
	}

	$Search = new CSearch("ProjectsResources");
	
	//$Search->AddColumn("ID", "ID", "0px;display:none", CSEARCHCOLUMN_SEARCHTYPE_NOSEARCH, "", "", "", "OnRow");
	$Search->AddColumn("Title", "Title", "50%;text-align:left", CSEARCHCOLUMN_SEARCHTYPE_NOSEARCH);
	$Search->AddColumn("", "ID", "15%;text-align:left", CSEARCHCOLUMN_SEARCHTYPE_NOSEARCH, "", "", "", "OnFilename");
	$Search->AddColumn("Uploaded", "Created", "20%;text-align:left", CSEARCHCOLUMN_SEARCHTYPE_NOSEARCH, "", "", "", "OnCreated");
	$Search->AddColumn("By", "CreatedUsersID", "15%;text-align:left", CSEARCHCOLUMN_SEARCHTYPE_NOSEARCH, "", "", "", "OnUser");
	
	$Search->AddRestriction("ProjectsID", $ProjectID);
	
	$Search->SetDefaultColumn(2, 1);
	
	//$Search->OnInit();
	
	//$Search->OnRender();
	//$Search->OnRenderPages();
	
	echo "<div class='ProjectContainer' style='padding-bottom:45px;'>
		<table class='CForm_Table'>";
		//echo CForm::AddYesNo("New/Updated Project", "Projects");
		//echo CForm::AddYesNo("Milestone Updates", "Milestones");
		//echo CForm::AddYesNo("Milestone To-Do Updates", "MilestoneToDos");
		//echo CForm::AddYesNo("Messages", "Messages");
		//echo CForm::AddYesNo("Resources", "Resources");
		
		CForm::SetFormat("None");
		
		$ModuleNotifications = CModule::GetAllNotifications();
		//echo CForm::AddYesNo("Notify me of new messages", "NotificationsMessages", $User->NotificationsMessages);
		//echo CForm::AddYesNo("Notify me of new resources", "NotificationsResources", $User->NotificationsResources);
		
		$Type			= "Module";
		$NoticeCount	= 0;
		foreach($ModuleNotifications as $Name => $NoticeList) {
			if(CSecurity::CanAccess($Name) == false) continue;
			
			$i = 0;
			foreach($NoticeList as $Notice) {
				$BGColor = "";

				if($i % 2 == 1) {
					$BGColor = "#EEEEEE";
				}

				//$Popup	= 0;
				$Email	= 0;
				//$SMS	= 0;

				$NSubClass = new CNotifierSubscriptions();
				if($NSubClass->OnLoadByUsersID(CSecurity::GetUsersID(), $Type, $Name, $Notice, $ProjectID) !== false) {
					//$Popup	= $NSubClass->Popup;
					$Email	= $NSubClass->Email;
					//$SMS	= $NSubClass->SMS;
				}

				$JID = "Notice_".$NoticeCount;

				echo "<tr>";
					echo "<td style='background-color: $BGColor;'>".$Notice."<input type='hidden' value=\"".CForm::MakeSafe(json_encode(Array("Type" => $Type, "Name" => $Name, "SubName" => $Notice)))."\" id='".CForm::GetPrefix().$JID."_Values'/></td>";
					//echo "<td align='center' style='background-color: $BGColor;'>".CForm::AddYesNo("", $JID."_Popup", $Popup)."</td>";
					echo "<td align='center' style='background-color: $BGColor;'>".CForm::AddYesNo("", $JID."_Email", $Email)."</td>";
					//echo "<td align='center' style='background-color: $BGColor;'>".CForm::AddYesNo("", $JID."_SMS", $SMS)."</td>";
				echo "<tr>";

				$i++;
				$NoticeCount++;
			}
			
		}
		
		CForm::SetFormat("Table");
		echo "<tr>
				<td colspan='2' style='text-align:right;'>
					<input type='hidden' name='".CForm::GetPrefix()."ProjectsID' id='".CForm::GetPrefix()."ProjectsID' value='".$ProjectID."'>
					<div class='Button' value='Save' onClick=\"MProjects.UpdateNotifications('".CForm::GetPrefix()."');\">save</div>
				</td>
			</tr>
		</table>
	</div>";
	
	//echo "<pre>";
	//var_dump($_SERVER);
	//echo "</pre>";
	
	
?>
