<?
	//==========================================================================
	/*
		[Permissions]
		View
		AddEdit
		Delete
		Window_AddEdit
		Window_Delete
		[-]
		[Notifications]
		New or Updated Project
		New or Updated Milestone
		New or Updated To-Do
		New Message
		New Resource
		[-]
	*/
	//==========================================================================
	class MProjects extends CModuleGeneric {
		function __construct() {
			$this->Table		= "Projects";
			$this->Classname	= "CProjects";

			parent::__construct();
		}

		//----------------------------------------------------------------------
		function OnExecute() {
			if(CSecurity::CanAccess($this->Name, "AddEdit")) {
				$this->Actions["New Project"] = Array("OnClick" => "MProjects.Window_AddEdit(0);", "Icon" => "Icon_Popup");
			}

			return parent::OnExecute();
		}

		//----------------------------------------------------------------------
		function OnRenderJS() { 
			$this->FileControl->LoadFile("MProjects.js", CFILE_TYPE_JS);
		}

		//----------------------------------------------------------------------
		function OnRender() {
			$Page = $_GET["Page"];
			
			// Also Check Permission Here
			if(isset($_GET["ResourcesID"])) {
				$this->FileControl->LoadFile("Download.php");
			}
			/**
			else
			if(isset($_GET["ID"]) && $Page == "ResourceCenter") {
				$this->ThemeControl->FileControl->LoadFile("header.php");
				$this->FileControl->LoadFile("ViewResourceCenter.php");
				$this->ThemeControl->FileControl->LoadFile("footer.php");
			}else
			if(isset($_GET["ID"]) && $Page == "MessageBoard") {
				$this->ThemeControl->FileControl->LoadFile("header.php");
				$this->FileControl->LoadFile("ViewMessageBoard.php");
				$this->ThemeControl->FileControl->LoadFile("footer.php");
			}else
			if(isset($_GET["ID"]) && ($Page == "ProjectDetails" || $Page == "")) {
				$this->ThemeControl->FileControl->LoadFile("header.php");
				$this->FileControl->LoadFile("View.php");
				$this->ThemeControl->FileControl->LoadFile("footer.php");
			}else
			*/
			if($Page == "Add" && CSecurity::$User->CanAccess("ProjectDetails", "Add")) {
				$this->ThemeControl->FileControl->LoadFile("header.php");
				$this->FileControl->LoadFile("Add.php");			
				$this->ThemeControl->FileControl->LoadFile("footer.php");
			}else{
				$this->ThemeControl->FileControl->LoadFile("header.php");
				$this->FileControl->LoadFile("Render.php");
				$this->ThemeControl->FileControl->LoadFile("footer.php");
			}
		}

		//----------------------------------------------------------------------
		function OnAJAX($Action) {
			if($Action == "AddFile") {
				return $this->AddFile();
			}else
			if($Action == "AddMessage") {
				return $this->AddMessage();
			}else
			if($Action == "AddEditMilestone") {
				return $this->AddEditMilestone();
			}else
			if($Action == "AddEditMilestoneToDo") {
				return $this->AddEditMilestoneToDo();
			}else
			if($Action == "AddMilestoneToDoList") {
				return $this->AddMilestoneToDoList();
			}else
			if($Action == "AddEditProjectToDo") {
				return $this->AddEditProjectToDo();
			}else
			if($Action == "UpdateNotifications") {
				return $this->UpdateNotifications();
			}else
			if($Action == "AddProductType") {
				return $this->AddProductType();
			}else
			if($Action == "Save") {
				return $this->Save();
			}else
			if($Action == "SaveFilter") {
				return $this->SaveFilter();
			}else
			if($Action == "DeleteFilter") {
				return $this->DeleteFilter();
			}else
			if($Action == "DeleteProject") {
				return $this->DeleteProject();
			}else
			if($Action == "DeleteMilestone") {
				return $this->DeleteMilestone();
			}else
			if($Action == "DeleteMilestoneToDo") {
				return $this->DeleteMilestoneToDo();
			}else
			if($Action == "DeleteToDo") {
				return $this->DeleteToDo();
			}else
			if($Action == "UpdateResourceList") {
				return $this->UpdateResourceList();
			}else
			if($Action == "GetInstitutionData") {
				return $this->GetInstitutionData();
			}else
			if($Action == "GetReadOnlyInfo") {
				return $this->GetReadOnlyInfo();
			}else
			if($Action == "CheckForUnique") {
				return $this->CheckForUnique();
			}else
			if($Action == "GetPreviewBoxData") {
				return $this->GetPreviewBoxData();
			}else
			if($Action == "ViewDetails") {
				return $this->ViewDetails();
			}else
			if($Action == "ViewMessages") {
				return $this->ViewMessages();
			}else
			if($Action == "ViewResources") {
				return $this->ViewResources();
			}else
			if($Action == "ViewNotifications") {
				return $this->ViewNotifications();
			}else
			if($Action == "LoadDefaultMilestone") {
				return $this->LoadDefaultMilestone();
			}else
			if($Action == "LoadDefaultToDo") {
				return $this->LoadDefaultToDo();
			}else
			if($Action == "GetToDoListMembers") {
				return $this->GetToDoListMembers();
			}

			if(parent::CanAccess($Action) == false) {
				return Array(0, "You do not have permission to perform this action");
			}

			return parent::OnAJAX($Action);
		}

		//======================================================================
		function AddFile() {
			//if(strlen($_POST["Filename"]) <= 0) return Array(0, "Please select a file");
			
			$Data = Array(
				"ProjectsID"			=> intval($_POST["ProjectsID"]),
				"Title"					=> htmlspecialchars($_POST["Title"]),
				"FilenameOriginal"		=> str_replace(" ", "_", htmlspecialchars($_POST["FilenameOriginal"])),
				"Created"				=> time(),
				"CreatedUsersID"		=> CSecurity::GetUsersID(),
				"CreatedIPAddress"		=> $_SERVER["REMOTE_ADDR"],
			);

			//if(strlen($_POST["Filename"]) > 0) {
				$Data["Filename"] = $_POST["Filename"];

				CData::AddUploadedTempFile(CRESOURCES_DATA_PATH, $_POST["Filename"]);
			//}

			if(($ID = CTable::Add("ProjectsResources", $Data)) === false) return Array(0, "Error adding file");

			$Categories = "";
			foreach(json_decode($_POST["Categories"]) as $CategoryID) {
				CTable::Add("ProjectsResourcesCategories", Array("ProjectsResourcesID" => $ID, "ResourcesCategoriesID" => $CategoryID));

				$Category = new CResourcesCategories();
				$Category->OnLoad($CategoryID);
				$Categories .= $Category->Name."<br>";
			}
			
			$Project = new CProjects();
			$Project->OnLoad($Data["ProjectsID"]);
			$User = new CUsers();
			$User->OnLoad(CSecurity::GetUsersID());
			$EmailData = Array(
				"ProjectsID"		=> $Project->ID,
				"ProjectNumber"		=> $Project->ProductNumber,
				"Created"			=> date('n/j/Y g:ia', $Data["Created"]),
				"User"				=> $User->FirstName . " " . $User->LastName,
				"Categories"		=> $Categories,
				"Title"				=> $Data["Title"],
				"Filename"			=> $Data["FilenameOriginal"],
				"ResourcesID"		=> $ID,
			);
			CNotifier::Push("Module", "Projects", "New Resource", $EmailData, $Project->ID);
			
			return Array(1, "Resource added successfully.");
		}
		
		//----------------------------------------------------------------------
		function AddMessage() {
			$Data = Array(
				"ProjectsID"			=> intval($_POST["ProjectsID"]),
				"Title"					=> htmlspecialchars($_POST["Title"]),
				"Content"				=> $_POST["Content"],
				"Created"				=> time(),
				"CreatedUsersID"		=> CSecurity::GetUsersID(),
				"CreatedIPAddress"		=> $_SERVER["REMOTE_ADDR"],
			);

			if(CTable::Add("ProjectsMessages", $Data) === false) return Array(0, "Error adding message");
			
			if(intval($_POST["SendNotification"])) {
				$Project = new CProjects();
				$Project->OnLoad($Data["ProjectsID"]);
				$User = new CUsers();
				$User->OnLoad(CSecurity::GetUsersID());
				$EmailData = Array(
					"ProjectsID"		=> $Project->ID,
					"ProjectNumber"		=> $Project->ProductNumber,
					"Created"			=> date('n/j/Y g:ia', $Data["Created"]),
					"User"				=> $User->FirstName . " " . $User->LastName,
					"Title"				=> $Data["Title"],
					"Content"			=> $Data["Content"],
				);
				CNotifier::Push("Module", "Projects", "New Message", $EmailData, $Project->ID);
			}
			
			return Array(1, "Message added successfully.");
		}
		
		//----------------------------------------------------------------------
		function AddEditMilestone() {
			$Data = Array(
				"ProjectsID"			=> intval($_POST["ProjectsID"]),
				"Name"					=> htmlspecialchars($_POST["Name"]),
				"CustomerApproval"		=> intval($_POST["CustomerApproval"]),
				"Summary"				=> htmlspecialchars($_POST["Summary"]),
				"ExpectedDeliveryDate"	=> strtotime($_POST["ExpectedDeliveryDate"]),
				"ActualDeliveryDate"	=> strtotime($_POST["ActualDeliveryDate"]),
				"PlantAllocated"		=> htmlspecialchars($_POST["PlantAllocated"]),
				"Status"				=> htmlspecialchars($_POST["Status"]),
			);

			if(intval($_POST["MilestoneID"]) > 0) {
				$Temp = new CProjectsMilestones();
				$Temp->OnLoad(intval($_POST["MilestoneID"]));
				$ChangeData = Array(
					"ProjectsMilestonesID"		=> intval($_POST["MilestoneID"]),
					"Timestamp"					=> time(),
					"UsersID"					=> CSecurity::GetUsersID(),
					"IPAddress"					=> $_SERVER["REMOTE_ADDR"],
					"Old"						=> serialize($Temp->Rows->Current),
					"New"						=> serialize($Data),
				);
				CTable::Add("ProjectsMilestonesChanges", $ChangeData);
				if(CTable::Update("ProjectsMilestones", intval($_POST["MilestoneID"]), $Data) === false) return Array(0, "Error updating Milestone");
			} else {
				$Data["Created"]				= time();
				$Data["CreatedUsersID"]			= CSecurity::GetUsersID();
				$Data["CreatedIPAddress"]		= $_SERVER["REMOTE_ADDR"];
				if(CTable::Add("ProjectsMilestones", $Data) === false) return Array(0, "Error adding Milestone");
			}
			
			$Project = new CProjects();
			$Project->OnLoad($Data["ProjectsID"]);
			$User = new CUsers();
			$User->OnLoad(CSecurity::GetUsersID());
			$EmailData = Array(
				"ProjectNumber"				=> $Project->ProductNumber,
				"DateTime"					=> date('n/j/Y g:ia', time()),
				"User"						=> $User->FirstName . " " . $User->LastName,
				"Name"						=> $Data["Name"],
				"CustomerApproval"			=> ($Data["CustomerApproval"] ? "Yes" : "No"),
				"Summary"					=> $Data["Summary"],
				"ExpectedDeliveryDate"		=> ($Data["ExpectedDeliveryDate"] ? date('n/j/Y', $Data["ExpectedDeliveryDate"]) : "N/A"),
				"ActualDeliveryDate"		=> ($Data["ActualDeliveryDate"] ? date('n/j/Y', $Data["ActualDeliveryDate"]) : "N/A"),
				"PlantAllocated"			=> $Data["PlantAllocated"],
				"Status"					=> $Data["Status"],
			);
			CNotifier::Push("Module", "Projects", "New or Updated Milestone", $EmailData, $Project->ID);
			
			return Array(1, "Milestone added successfully.");
		}
		
		//----------------------------------------------------------------------
		function AddEditMilestoneToDo() {
			$Data = Array(
				"MilestoneID"			=> intval($_POST["MilestoneID"]),
				"Name"					=> htmlspecialchars($_POST["Name"]),
				"Complete"				=> intval($_POST["Complete"]),
				"Comment"				=> htmlspecialchars($_POST["Comment"]),
				"CommentRequired"		=> intval($_POST["CommentRequired"]),
			);

			if(intval($_POST["ToDoID"]) > 0) {
				$Temp = new CProjectsMilestonesToDos();
				$Temp->OnLoad(intval($_POST["ToDoID"]));
				$ChangeData = Array(
					"ToDoID"			=> intval($_POST["ToDoID"]),
					"Timestamp"			=> time(),
					"UsersID"			=> CSecurity::GetUsersID(),
					"IPAddress"			=> $_SERVER["REMOTE_ADDR"],
					"Old"				=> serialize($Temp->Rows->Current),
					"New"				=> serialize($Data),
				);
				CTable::Add("ProjectsMilestonesToDosChanges", $ChangeData);
				if(CTable::Update("ProjectsMilestonesToDos", intval($_POST["ToDoID"]), $Data) === false) return Array(0, "Error updating To-Do");
			} else {
				$Data["Created"]				= time();
				$Data["CreatedUsersID"]			= CSecurity::GetUsersID();
				$Data["CreatedIPAddress"]		= $_SERVER["REMOTE_ADDR"];
				if(CTable::Add("ProjectsMilestonesToDos", $Data) === false) return Array(0, "Error adding To-Do");
			}

			$Milestone = new CProjectsMilestones();
			$Milestone->OnLoad($Data["MilestoneID"]);
			$Project = new CProjects();
			$Project->OnLoad($Milestone->ProjectsID);
			$User = new CUsers();
			$User->OnLoad(CSecurity::GetUsersID());
			$EmailData = Array(
				"ProjectNumber"				=> $Project->ProductNumber,
				"DateTime"					=> date('n/j/Y g:ia', time()),
				"User"						=> $User->FirstName . " " . $User->LastName,
				"Name"						=> $Data["Name"],
				"Comment"					=> $Data["Comment"],
				"Complete"					=> ($Data["Complete"] ? "Yes" : "No"),
			);
			CNotifier::Push("Module", "Projects", "New or Updated To-Do", $EmailData, $Project->ID);
			
			return Array(1, "To-Do added successfully.");
		}
		
		//----------------------------------------------------------------------
		function AddMilestoneToDoList() {
			$ToDoListID = intval($_POST["DefaultMilestoneToDoList"]);
			
			$List = new CToDosLists();
			if($List->OnLoad($ToDoListID) === false) return Array(0, "Error loading To Do List info");
			
			$ListMembers	= unserialize($List->Members);
			$MembersArray	= Array();
			foreach($ListMembers as $MemberID) {
				$Member = new CToDos();
				if($Member->OnLoad($MemberID) === false) continue;
				
				if($Member->Active == 0) continue;
				
				$Data = Array(
					"MilestoneID"			=> intval($_POST["MilestoneID"]),
					"Name"					=> htmlspecialchars($Member->Name),
					"Comment"				=> htmlspecialchars($Member->Comment),
					"CommentRequired"		=> intval($Member->CommentRequired),
					"Created"				=> time(),
					"CreatedUsersID"		=> CSecurity::GetUsersID(),
					"CreatedIPAddress"		=> $_SERVER["REMOTE_ADDR"],
				);
				if(CTable::Add("ProjectsMilestonesToDos", $Data) === false) return Array(0, "Error adding To-Do's");
			}
			

			$Milestone = new CProjectsMilestones();
			$Milestone->OnLoad($Data["MilestoneID"]);
			$Project = new CProjects();
			$Project->OnLoad($Milestone->ProjectsID);
			$User = new CUsers();
			$User->OnLoad(CSecurity::GetUsersID());
			$EmailData = Array(
				"ProjectNumber"				=> $Project->ProductNumber,
				"DateTime"					=> date('n/j/Y g:ia', time()),
				"User"						=> $User->FirstName . " " . $User->LastName,
				"Name"						=> $Milestone->Name,
				"CustomerApproval"			=> ($Milestone->CustomerApproval ? "Yes" : "No"),
				"Summary"					=> $Milestone->Summary,
				"ExpectedDeliveryDate"		=> ($Milestone->ExpectedDeliveryDate ? date('n/j/Y', $Milestone->ExpectedDeliveryDate) : "N/A"),
				"ActualDeliveryDate"		=> ($Milestone->ActualDeliveryDate ? date('n/j/Y', $Milestone->ActualDeliveryDate) : "N/A"),
				"PlantAllocated"			=> $Milestone->PlantAllocated,
				"Status"					=> $Milestone->Status,
			);
			CNotifier::Push("Module", "Projects", "New or Updated Milestone", $EmailData, $Project->ID);
			
			return Array(1, "To-Do's added successfully.");
		}
		
		//----------------------------------------------------------------------
		function AddEditProjectToDo() {
			$Data = Array(
				"ProjectsID"			=> intval($_POST["ProjectsID"]),
				"Name"					=> htmlspecialchars($_POST["Name"]),
				"Complete"				=> intval($_POST["Complete"]),
				"Comment"				=> htmlspecialchars($_POST["Comment"]),
				"CommentRequired"		=> intval($_POST["CommentRequired"]),
			);

			if(intval($_POST["ToDoID"]) > 0) {
				$Temp = new CProjectsMilestonesToDos();
				$Temp->OnLoad(intval($_POST["ToDoID"]));
				$ChangeData = Array(
					"ToDoID"			=> intval($_POST["ToDoID"]),
					"Timestamp"			=> time(),
					"UsersID"			=> CSecurity::GetUsersID(),
					"IPAddress"			=> $_SERVER["REMOTE_ADDR"],
					"Old"				=> serialize($Temp->Rows->Current),
					"New"				=> serialize($Data),
				);
				CTable::Add("ProjectsToDosChanges", $ChangeData);
				if(CTable::Update("ProjectsToDos", intval($_POST["ToDoID"]), $Data) === false) return Array(0, "Error updating To-Do");
			} else {
				$Data["Created"]				= time();
				$Data["CreatedUsersID"]			= CSecurity::GetUsersID();
				$Data["CreatedIPAddress"]		= $_SERVER["REMOTE_ADDR"];
				if(CTable::Add("ProjectsToDos", $Data) === false) return Array(0, "Error adding To-Do");
			}

			$Project = new CProjects();
			$Project->OnLoad($Data["ProjectsID"]);
			$User = new CUsers();
			$User->OnLoad(CSecurity::GetUsersID());
			$EmailData = Array(
				"ProjectNumber"				=> $Project->ProductNumber,
				"DateTime"					=> date('n/j/Y g:ia', time()),
				"User"						=> $User->FirstName . " " . $User->LastName,
				"Name"						=> $Data["Name"],
				"Comment"					=> $Data["Comment"],
				"Complete"					=> ($Data["Complete"] ? "Yes" : "No"),
			);
			CNotifier::Push("Module", "Projects", "New or Updated To-Do", $EmailData, $Project->ID);
			
			return Array(1, "To-Do added successfully.");
		}
		
		//----------------------------------------------------------------------
		function UpdateNotifications() {
			$ProjectsID = intval($_POST["ProjectsID"]);
			
			for($i = 0;;$i++) {
				if(!isset($_POST["Notice_".$i."_Values"])) break;

				$DataNotifications	= json_decode($_POST["Notice_".$i."_Values"]);

				//$Popup	= intval($_POST["Notice_".$i."_Popup"]);
				$Email	= intval($_POST["Notice_".$i."_Email"]);
				//$SMS	= intval($_POST["Notice_".$i."_SMS"]);

				$SubData = Array(
					//"BusinessesID" => intval(CSecurity::GetBusinessesID()), 
					"UsersID"		=> intval(CSecurity::GetUsersID()),
					"ProjectsID"	=> $ProjectsID,
					"Type"			=> $DataNotifications->Type,
					"Name"			=> $DataNotifications->Name,
					"SubName"		=> $DataNotifications->SubName,
					//"Popup"		=> $Popup,
					"Email"			=> $Email,
					//"SMS"			=> $SMS
				);

				$NSubClass = new CNotifierSubscriptions();
				if($NSubClass->OnLoadByUsersID(CSecurity::GetUsersID(), $DataNotifications->Type, $DataNotifications->Name, $DataNotifications->SubName, $ProjectsID) === false) {
					if(CTable::Add("NotifierSubscriptions", $SubData) === false) return Array(0, "Error saving Notification Subscriptions");
				}else{
					if(CTable::Update("NotifierSubscriptions", $NSubClass->ID, $SubData) === false) return Array(0, "Error saving Notification Subscriptions");
				}
			}
			
			return Array(1, "Notification subscriptions updated successfully");
		}
		
		//----------------------------------------------------------------------
		function AddProductType($Name) {
			$Name = htmlspecialchars($Name);
			
			$Data = Array(
				"Name"					=> $Name,
				"Active"				=> 1,
				"Created"				=> time(),
				"CreatedUsersID"		=> CSecurity::GetUsersID(),
				"CreatedIPAddress"		=> $_SERVER["REMOTE_ADDR"],
				"Modified"				=> time(),
				"ModifiedUsersID"		=> CSecurity::GetUsersID(),
				"ModifiedIPAddress"		=> $_SERVER["REMOTE_ADDR"],
			);
			
			if(($ID = CTable::Add("ProductTypes", $Data)) !== false) return $ID;
			
			return false;
		}
		
		//----------------------------------------------------------------------
		function UpdateResourceList() {
			$ProjectID = $_POST["ProjectID"];
			
			
		}
		
		//----------------------------------------------------------------------
		function Save() {
			// Should probably also have checks here if the User can access this
			// Project
			
			// According to John, Institutions will not be using this Module
			// but will have their own separate Portal. So no need to check
			// for Institution Permissions and such.
			$ID 									= intval($_POST["ID"]);
			$Data = Array(
				"ProductNumber"						=> intval($_POST["ProductNumber"]),
				"ProjectValue"						=> doubleval($_POST["ProjectValue"]),
				"BusinessAnalyst"					=> htmlspecialchars($_POST["BusinessAnalyst"]),
				"PrimaryCustomer"					=> htmlspecialchars($_POST["PrimaryCustomer"]),
				"CustomerPhone"						=> htmlspecialchars($_POST["CustomerPhone"]),
				"CustomerEmail"						=> htmlspecialchars($_POST["CustomerEmail"]),
				"LeadAuthor"						=> htmlspecialchars($_POST["LeadAuthor"]),
				"Title"								=> htmlspecialchars($_POST["Title"]),
				"MHID"								=> htmlspecialchars($_POST["MHID"]),
				"Ed"								=> htmlspecialchars($_POST["Ed"]),
				"Imp"								=> htmlspecialchars($_POST["Imp"]),
				"NetPrice"							=> htmlspecialchars($_POST["NetPrice"]),
				"EstimatedUMC"						=> htmlspecialchars($_POST["EstimatedUMC"]),
				"ActualUMC"							=> htmlspecialchars($_POST["ActualUMC"]),
				"School"							=> htmlspecialchars($_POST["School"]),
				"Status"							=> htmlspecialchars($_POST["Status"]),
				"CourseStartDate"					=> strtotime($_POST["CourseStartDate"]),
				"DueDate"							=> strtotime($_POST["DueDate"]),
				"QOH"								=> htmlspecialchars($_POST["QOH"]),
				"QOHDate"							=> strtotime($_POST["QOHDate"]),
				"StatSponsorCode"					=> htmlspecialchars($_POST["StatSponsorCode"]),
				"2012YTDSalesNetUnits"				=> intval($_POST["2012YTDSalesNetUnits"]),
				"2012YTDSalesNetRevenue"			=> doubleval($_POST["2012YTDSalesNetRevenue"]),
				"2012YTDSalesGrossUnits"			=> intval($_POST["2012YTDSalesGrossUnits"]),
				"2012YTDSalesGrossRevenue"			=> doubleval($_POST["2012YTDSalesGrossRevenue"]),
				"2011SalesNetUnits"					=> intval($_POST["2011SalesNetUnits"]),
				"2011SalesNetRevenue"				=> doubleval($_POST["2011SalesNetRevenue"]),
				"2011SalesGrossUnits"				=> intval($_POST["2011SalesGrossUnits"]),
				"2011SalesGrossRevenue"				=> doubleval($_POST["2011SalesGrossRevenue"]),
				"LeadNotes"							=> htmlspecialchars($_POST["LeadNotes"]),
				"RequestPlant"						=> intval($_POST["RequestPlant"]),
				"Modified"							=> time(),
				"ModifiedUsersID"					=> CSecurity::GetUsersID(),
			);

			// Changes
			$Temp = new CProjects();
			if($Temp->OnLoad($ID) !== false) {
				$OldPlantRequest = $Temp->RequestPlant;
				$ChangeData = Array(
					"ProjectsID"			=> $ID,
					"Timestamp"				=> time(),
					"UsersID"				=> CSecurity::GetUsersID(),
					"IPAddress"				=> $_SERVER["REMOTE_ADDR"],
					"Old"					=> serialize($Temp->Rows->Current),
					"New"					=> serialize($Data),
				);
				CTable::Add("ProjectsChanges", $ChangeData);
			} else {
				$OldPlantRequest = 0;
			}

			if($ID) {
				if(CTable::Update("Projects", $ID, $Data) === false) return Array(0, "Error updating record");
			} else {
				if(($ID = CTable::Add("Projects", $Data)) === false) return Array(0, "Error saving record");
			}

			// District Managers
			CTable::RunQuery("DELETE FROM `ProjectsDistrictManagers` WHERE `ProjectsID` = $ID");
			if($_POST["DistrictManagerUsersID"] != "null") {
				$DistrictManagers = json_decode($_POST["DistrictManagerUsersID"]);
				foreach($DistrictManagers as $UserID) {
					CTable::Add("ProjectsDistrictManagers", Array("ProjectsID" => $ID, "UsersID" => $UserID));
				}
			}
			
			// Sales Rep
			CTable::RunQuery("DELETE FROM `ProjectsSalesReps` WHERE `ProjectsID` = $ID");
			if($_POST["SalesRepUsersID"] != "null") {
				$SalesReps = json_decode($_POST["SalesRepUsersID"]);
				foreach($SalesReps as $UserID) {
					CTable::Add("ProjectsSalesReps", Array("ProjectsID" => $ID, "UsersID" => intval($UserID)));
				}
			}
			
			// LSCs
			CTable::RunQuery("DELETE FROM `ProjectsLSCs` WHERE `ProjectsID` = $ID");
			if($_POST["LSCUsersID"] != "null") {
				$LSCs = json_decode($_POST["LSCUsersID"]);
				foreach($LSCs as $UserID) {
					CTable::Add("ProjectsLSCs", Array("ProjectsID" => $ID, "UsersID" => intval($UserID)));
				}
			}
			
			// LSSs
			CTable::RunQuery("DELETE FROM `ProjectsLSSs` WHERE `ProjectsID` = $ID");
			if($_POST["LSSUsersID"] != "null") {
				$LSSs = json_decode($_POST["LSSUsersID"]);
				foreach($LSSs as $UserID) {
					CTable::Add("ProjectsLSSs", Array("ProjectsID" => $ID, "UsersID" => intval($UserID)));
				}
			}
			
			// LSRs
			CTable::RunQuery("DELETE FROM `ProjectsLSRs` WHERE `ProjectsID` = $ID");
			if($_POST["LSRUsersID"] != "null") {
				$LSRs = json_decode($_POST["LSRUsersID"]);
				foreach($LSRs as $UserID) {
					CTable::Add("ProjectsLSRs", Array("ProjectsID" => $ID, "UsersID" => intval($UserID)));
				}
			}
			
			// Junior Creative Analysts
			CTable::RunQuery("DELETE FROM `ProjectsJuniorCreativeAnalysts` WHERE `ProjectsID` = $ID");
			if($_POST["JuniorCreativeAnalystUsersID"] != "null") {
				$JCAs = json_decode($_POST["JuniorCreativeAnalystUsersID"]);
				foreach($JCAs as $UserID) {
					CTable::Add("ProjectsJuniorCreativeAnalysts", Array("ProjectsID" => $ID, "UsersID" => intval($UserID)));
				}
			}
			
			// Creative Analysts
			CTable::RunQuery("DELETE FROM `ProjectsCreativeAnalysts` WHERE `ProjectsID` = $ID");
			if($_POST["CreativeAnalystUsersID"] != "null") {
				$CAs = json_decode($_POST["CreativeAnalystUsersID"]);
				foreach($CAs as $UserID) {
					CTable::Add("ProjectsCreativeAnalysts", Array("ProjectsID" => $ID, "UsersID" => intval($UserID)));
				}
			}
			
			// Creative Consultants
			CTable::RunQuery("DELETE FROM `ProjectsCreativeConsultants` WHERE `ProjectsID` = $ID");
			if($_POST["CreativeConsultantUsersID"] != "null") {
				$CCs = json_decode($_POST["CreativeConsultantUsersID"]);
				foreach($CCs as $UserID) {
					CTable::Add("ProjectsCreativeConsultants", Array("ProjectsID" => $ID, "UsersID" => intval($UserID)));
				}
			}
			
			// Institutional Sales Reps
			CTable::RunQuery("DELETE FROM `ProjectsInstitutionalSalesReps` WHERE `ProjectsID` = $ID");
			if($_POST["InstitutionalSalesRepUsersID"] != "null") {
				$InstitutionalSalesReps = json_decode($_POST["InstitutionalSalesRepUsersID"]);
				foreach($InstitutionalSalesReps as $UserID) {
					CTable::Add("ProjectsInstitutionalSalesReps", Array("ProjectsID" => $ID, "UsersID" => intval($UserID)));
				}
			}
			
			// Product Managers
			CTable::RunQuery("DELETE FROM `ProjectsProductManagers` WHERE `ProjectsID` = $ID");
			if($_POST["ProductManagerUsersID"] != "null") {
				$ProductManagers = json_decode($_POST["ProductManagerUsersID"]);
				foreach($ProductManagers as $UserID) {
					CTable::Add("ProjectsProductManagers", Array("ProjectsID" => $ID, "UsersID" => intval($UserID)));
				}
			}

			// Product Types
			CTable::RunQuery("DELETE FROM `ProjectsProductTypes` WHERE `ProjectsID` = $ID");
			if($_POST["ProductTypes"]) {
				$ProductTypes = explode(",", htmlspecialchars($_POST["ProductTypes"]));
				foreach($ProductTypes as $ProductTypeID) {
					if(intval($ProductTypeID) <= 0) {
						$ProductTypeID = self::AddProductType($ProductTypeID);
						if(!$ProductTypeID) continue;
					}
					CTable::Add("ProjectsProductTypes", Array("ProjectsID" => $ID, "ProductTypesID" => $ProductTypeID));
				}
			}

			$User = new CUsers();
			$User->OnLoad(CSecurity::GetUsersID());
			$EmailData = Array(
				"ProjectsID"				=> $ID,
				"ProjectNumber"				=> $Data["ProductNumber"],
				"DateTime"					=> date('n/j/Y g:ia', time()),
				"User"						=> $User->FirstName . " " . $User->LastName,
			);
			CNotifier::Push("Module", "Projects", "New or Updated Project", $EmailData, $ID);
			if(intval($_POST["RequestPlant"]) && !$OldPlantRequest) {
				// Trigger email to appropriate manager
				// 2012-07-31 per John Henry, just Craig Bartley for now (craig_bartley@mcgraw-hill.com)
				CNotifier::PushEmail("craig_bartley@mcgraw-hill.com", "Module", "Projects", "Plant Request", $EmailData);
				CNotifier::PushEmail("jarrod.nix@jhspecialty.com", "Module", "Projects", "Plant Request", $EmailData);
			}
			
			return Array('Project updated successfully');
		}
		
		//----------------------------------------------------------------------
		function SaveFilter() {
			$Data = Array(
				"Options" 	=> $_POST["Options"],
				"Name" 		=> htmlspecialchars($_POST["Name"]),
				"UsersID"	=> CSecurity::GetUsersID(),				
			);
		
			$Data["Options"]["FilterOperator"] = $_POST["FilterOperator"];
			
			$CFilterProfiles = new CFilterProfiles();			
			$Row = $CFilterProfiles->LoadByName($Data["Name"]);
						
			if(is_array($Row))
			{				
				CTable::Update("FilterProfiles", $Row["ID"], $Data);
				return Array($Row["ID"], "Filter updated successfully");
			}
			else
			{
				if(($ID = CTable::Add("FilterProfiles", $Data)) === false)
					return Array(0, "Error saving Filter");
				return Array($ID, "Filter saved successfully");
			}		
			
		}

		//----------------------------------------------------------------------
		function DeleteFilter() {
			$Data = Array(
				"Name" 		=> htmlspecialchars($_POST["Name"]),				
			);
			
			$CFilterProfiles = new CFilterProfiles();			
			$Row = $CFilterProfiles->LoadByName($Data["Name"]);
			
			if(is_array($Row))
			{				
			
				CTable::Delete("FilterProfiles", $Row["ID"]);
				return Array($Row["ID"], "Filter deleted successfully");
			}
			
			return Array(0, "Filter cannot be deleted");
		}
		
		//----------------------------------------------------------------------
		function Delete() {
		 	// Hide Projects, do not Delete
			//return parent::Delete();
		}
		
		function GetInstitutionData() {
			$InstitutionsID = intval($_POST["InstitutionsID"]);
			
			$ReturnData = Array(
				"Contacts"			=> Array(0 => "Add New"),
				"ProductSolutions"  => Array(0 => "Add New")
			);
			
			$Users = new CUsers();
			if($Users->OnLoadAll("WHERE `Active` = 1 && `Type` = 'Institution' && `InstitutionsID` = ".$InstitutionsID) !== false) {
				foreach($Users->Rows as $Row) {
					$ReturnData["Contacts"][$Row->ID] = $Users->GetName();
				}
			}			
			
			$ProdSolutions = new CProductSolutions();
			if($ProdSolutions->OnLoadAll("WHERE `Public` = 1 || `InstitutionsID` = ".$InstitutionsID) !== false) {
				foreach($ProdSolutions->Rows as $Row) {
					$ReturnData["ProductSolutions"][$Row->ID] = $Row->Name;
				}
			}
			
			return Array(1, json_encode($ReturnData));
		}
		
		function GetReadOnlyInfo() {
			$ProductSolutions 		= json_decode($_POST["ProductSolutions"], true);
			$InstitutionsID 		= intval($_POST["InstitutionsID"]);
			$InstitutionsUsersID 	= intval($_POST["InstitutionsUsersID"]);
			
			$Data = Array();
			
			$Data["ProductSolutions"] = Array();			
			
			if(is_array($ProductSolutions)) {
				$i = 0;
				foreach($ProductSolutions as $ProductSolution) {
					$ProdSolution = new CProductSolutions();
					if($ProdSolution->OnLoad($ProductSolution["ID"]) !== false) {
						$Data["ProductSolutions"][$i] = Array(
							"Name" 	=> $ProdSolution->Name,
							"Price"	=> $ProdSolution->Price
						);
					}else{
						$Data["ProductSolutions"][$i] = Array(
							"Name" 	=> "",
							"Price"	=> ""
						);					
					}
					
					$i++;
				}
			}
			
			$Institution = new CInstitutions();
			if($Institution->OnLoad($InstitutionsID) !== false) {
				$Data["InstitutionName"] 		= $Institution->Name;	
				$Data["InstitutionAddress1"] 	= $Institution->Address1;	
				$Data["InstitutionAddress2"] 	= $Institution->Address2;	
				$Data["InstitutionCity"] 		= $Institution->City;	
				$Data["InstitutionState"] 		= $Institution->State;
				$Data["InstitutionZip"] 		= $Institution->Zip;
			}
			
			$Contact = new CUsers();
			if($Contact->OnLoad($InstitutionsUsersID) !== false) {
				$Data["InstitutionContactTitle"] 		= $Contact->Title;	
				$Data["InstitutionContactFirstName"] 	= $Contact->FirstName;	
				$Data["InstitutionContactLastName"] 	= $Contact->LastName;									
				$Data["InstitutionContactAddress1"] 	= $Contact->Address1;	
				$Data["InstitutionContactAddress2"] 	= $Contact->Address2;	
				$Data["InstitutionContactCity"] 		= $Contact->City;	
				$Data["InstitutionContactState"] 		= $Contact->State;
				$Data["InstitutionContactZip"] 			= $Contact->Zip;
				$Data["InstitutionContactCampus"] 		= $Contact->Campus;				
				$Data["InstitutionContactOfficePhone"] 	= $Contact->OfficePhone;
				$Data["InstitutionContactMobilePhone"] 	= $Contact->MobilePhone;
				$Data["InstitutionContactEmail"] 		= $Contact->Email;												
			}
			
			return Array(1, json_encode($Data));
		}
		
		function CheckForUnique() {
			$Data = Array(
				"ProductSolution" 	=> "",
				"Institution"		=> "",
				"InstitutionUser" 	=> ""
			);
			
			if(intval($_POST["ProductSolutionsID"]) <= 0) {
				$ProdSolution = new CProductSolutions();
				if($ProdSolution->OnLoadAll("WHERE `Name` = '".mysql_real_escape_string(trim($_POST["ProductSolutionName"]))."'") !== false) {
					$Data["ProductSolution"] = "Bad";				
				}else{
					$Data["ProductSolution"] = "Good";
				}
			}
			
			if(intval($_POST["InstitutionsID"]) <= 0) {
				$Institution = new CInstitutions();
				if($Institution->OnLoadAll("WHERE `Name` = '".mysql_real_escape_string(trim($_POST["InstitutionName"]))."'") !== false) {
					$Data["Institution"] = "Bad";				
				}else{
					$Data["Institution"] = "Good";
				}
			}
			
			if(intval($_POST["InstitutionsUsersID"]) <= 0) {
				$User = new CUsers();
				if($User->OnLoadAll("WHERE `FirstName` = '".mysql_real_escape_string(trim($_POST["InstitutionContactFirstName"]))."' && `LastName` = '".mysql_real_escape_string(trim($_POST["InstitutionContactLastName"]))."'") !== false) {
					$Data["InstitutionUser"] = "Bad";				
				}else{
					$Data["InstitutionUser"] = "Good";
				}
			}
			
			return Array(1, json_encode($Data));			
		}
		
		function GetPreviewBoxData() {
			$Type = $_POST["Type"];			
			
			$ValidTypes = Array(
				"LastTouched",
				"LeadNotes",
				"ProjectLink",
			);
			
			if(in_array($Type, $ValidTypes) == false) return Array(0, "Type not Valid");
			
			$Width  = 400;
			$Height = 78;
			
			
			
			$Data = Array(
				"HTML" 		=> $this->FileControl->LoadFile("PreviewBox".$Type.".php", CFILE_TYPE_CONTENTS_OB),
				"Width" 	=> $Width,				
				//"Height"	=> $Height
			);
			
			return Array(1, json_encode($Data));
		}
		
		//======================================================================
		function ViewDetails() {
			return Array(1, $this->FileControl->LoadFile("ViewProjectDetails.php", CFILE_TYPE_CONTENTS_OB));
		}
		
		//----------------------------------------------------------------------
		function ViewMessages() {
			return Array(1, $this->FileControl->LoadFile("ViewMessageBoard.php", CFILE_TYPE_CONTENTS_OB));
		}
		
		//----------------------------------------------------------------------
		function ViewResources() {
			return Array(1, $this->FileControl->LoadFile("ViewResourceCenter.php", CFILE_TYPE_CONTENTS_OB));
		}
		
		//----------------------------------------------------------------------
		function ViewNotifications() {
			return Array(1, $this->FileControl->LoadFile("ViewNotifications.php", CFILE_TYPE_CONTENTS_OB));
		}
		
		//----------------------------------------------------------------------
		function LoadDefaultMilestone() {
			$Milestone = new CMilestones();
			if($Milestone->OnLoad($_POST["MilestoneID"]) === false) return Array(0, "Error loading Milestone info");
			
			return Array(1, json_encode($Milestone->Rows->Current));
		}
		
		//----------------------------------------------------------------------
		function LoadDefaultToDo() {
			$ToDo = new CToDos();
			if($ToDo->OnLoad($_POST["ToDoID"]) === false) return Array(0, "Error loading To-Do info");
			
			return Array(1, json_encode($ToDo->Rows->Current));
		}
		
		//----------------------------------------------------------------------
		function DeleteProject() {
			if(!CSecurity::$User->CanAccess("ProjectDetails", "Delete")) {
				return Array(0, "You do not have permissions to perform this action");
			}
			
			$ProjectID = intval($_POST["ProjectID"]);
			
			$Data = Array(
				"Deleted"			=> time(),
				"DeletedUsersID"	=> CSecurity::GetUsersID(),
				"DeletedIPAddress"	=> $_SERVER["REMOTE_ADDR"],
			);
			if(CTable::Update("Projects", $ProjectID, $Data) === false) return Array(0, "Error deleting Project");
			
			return Array(1, "Project deleted successfully");
		}
		
		//----------------------------------------------------------------------
		function DeleteMilestone() {
			if(!CSecurity::$User->CanAccess("Milestones", "Delete")) {
				return Array(0, "You do not have permissions to perform this action");
			}
			
			$MilestoneID = intval($_POST["MilestoneID"]);
			
			$Data = Array(
				"Deleted"			=> time(),
				"DeletedUsersID"	=> CSecurity::GetUsersID(),
				"DeletedIPAddress"	=> $_SERVER["REMOTE_ADDR"],
			);
			if(CTable::Update("ProjectsMilestones", $MilestoneID, $Data) === false) return Array(0, "Error deleting Milestone");
			
			return Array(1, "Milestone deleted successfully");
		}
		
		//----------------------------------------------------------------------
		function DeleteMilestoneToDo() {
			if(!CSecurity::$User->CanAccess("MilestonesToDos", "Delete")) {
				return Array(0, "You do not have permissions to perform this action");
			}
			
			$MilestoneToDoID = intval($_POST["MilestoneToDoID"]);
			
			$Data = Array(
				"Deleted"			=> time(),
				"DeletedUsersID"	=> CSecurity::GetUsersID(),
				"DeletedIPAddress"	=> $_SERVER["REMOTE_ADDR"],
			);
			if(CTable::Update("ProjectsMilestonesToDos", $MilestoneToDoID, $Data) === false) return Array(0, "Error deleting Milestone To-Do");
			
			return Array(1, "Milestone To-Do deleted successfully");
		}
		
		//----------------------------------------------------------------------
		function DeleteToDo() {
			if(!CSecurity::$User->CanAccess("ToDos", "Delete")) {
				return Array(0, "You do not have permissions to perform this action");
			}
			
			$ToDoID = intval($_POST["ToDoID"]);
			
			$Data = Array(
				"Deleted"			=> time(),
				"DeletedUsersID"	=> CSecurity::GetUsersID(),
				"DeletedIPAddress"	=> $_SERVER["REMOTE_ADDR"],
			);
			if(CTable::Update("ProjectsToDos", $ToDoID, $Data) === false) return Array(0, "Error deleting To-Do");
			
			return Array(1, "To-Do deleted successfully");
		}
		
		//----------------------------------------------------------------------
		function GetToDoListMembers() {
			$ToDoListID = intval($_POST["ToDoListID"]);
			
			$List = new CToDosLists();
			if($List->OnLoad($ToDoListID) === false) return Array(0, "Error loading To Do List info");
			
			$ListMembers	= unserialize($List->Members);
			$MembersArray	= Array();
			foreach($ListMembers as $MemberID) {
				$Member = new CToDos();
				if($Member->OnLoad($MemberID) === false) continue;
				
				if($Member->Active == 0) continue;
				
				$MembersArray[$Member->ID] = $Member->Name;
			}
			
			$Return = "";
			foreach($MembersArray as $ID => $Name) {
				$Return .= "<li>{$Name}</li>";
			}
			
			return Array(1, $Return);
		}
	};

	//==========================================================================
?>
