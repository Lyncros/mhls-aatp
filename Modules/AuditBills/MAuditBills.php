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
	*/
	//==========================================================================
	class MAuditBills extends CModuleGeneric {
		function __construct() {
			$this->Table		= "AuditBills";
			$this->Classname	= "CAuditBills";

			parent::__construct();
		}

		//----------------------------------------------------------------------
		function OnExecute() {
			if(CSecurity::CanAccess($this->Name, "AddEdit")) {
				$this->Actions["New Audit Bill"] = Array("OnClick" => "MAuditBills.Window_AddEdit(0);", "Icon" => "Icon_Popup");
			}

			return parent::OnExecute();
		}

		//----------------------------------------------------------------------
		function OnRenderJS() { 
			$this->FileControl->LoadFile("MAuditBills.js", CFILE_TYPE_JS);
		}

		//----------------------------------------------------------------------
		function OnRender() {
			$Page = $_GET["Page"];
			
			// Also Check Permission Here
			if($Page == "New") {
				$this->ThemeControl->FileControl->LoadFile("header.php");
				$this->FileControl->LoadFile("View.php");
				$this->ThemeControl->FileControl->LoadFile("footer.php");			
			}else
			if($Page == "Step1" || $Page == "Step2" || $Page == "Step3" || $Page == "Step4") {
				$this->ThemeControl->FileControl->LoadFile("header.php");
				$this->FileControl->LoadFile("RenderSteps.php");
				$this->ThemeControl->FileControl->LoadFile("footer.php");
			}else{
				parent::OnRender();
			}
		}

		//----------------------------------------------------------------------
		function OnAJAX($Action) {
			if(parent::CanAccess($Action) == false) {
				return Array(0, "You do not have permission to perform this action");
			}
			
			if($Action == "Save") {
				return $this->Save();
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
			}

			return parent::OnAJAX($Action);
		}

		//======================================================================
		function Save() {
			// Should probably also have checks here if the User can access this
			// Audit Bill
			
			// According to John, Institutions will not be using this Module
			// but will have their own separate Portal. So no need to check
			// for Institution Permissions and such.
		
			$ID 					= intval($_POST["ID"]);
			$InstitutionsID 		= intval($_POST["InstitutionsID"]);		
			$InstitutionsUsersID 	= intval($_POST["InstitutionsUsersID"]);
			
			$SaveType		= $_POST["SaveType"]; // 'Save' or 'Advance'	

			$HistoryContent = "";

			// Load the AB if it's not New
			$AB = new CAuditBills();
			if($ID > 0 && $AB->OnLoad($ID) === false) { 
				return Array(0, "Sorry, the selected Audit Bill cannot be found.");
			}
			
			if($_POST["Number"] == "") {
				return Array(0, "Please enter an AB Number.");			
			}
			
			if($_POST["StartDate"] == "") {
				return Array(0, "Please enter an AB Date.");			
			}			
			
			// New Institution
			if($InstitutionsID <= 0) {
				if($_POST["InstitutionName"] == "") {
					return Array(0, "Please enter an Institution Name or pick an existing Institution.");				
				}
			
				$Data = Array(
					"UsersID"			=> CSecurity::GetUsersID(),
					"Timestamp"			=> time(),
					"TimestampUpdated"	=> time(),
					"Name"				=> CFormat::SpecialChars($_POST["InstitutionName"]),
					"Address1"			=> CFormat::SpecialChars($_POST["InstitutionAddress1"]),
					"Address2"			=> CFormat::SpecialChars($_POST["InstitutionAddress2"]),
					"City"				=> CFormat::SpecialChars($_POST["InstitutionCity"]),
					"State"				=> CFormat::SpecialChars($_POST["InstitutionState"]),
					"Zip"				=> CFormat::SpecialChars($_POST["InstitutionZip"]),
					"Active"			=> 1
				);
				
				$InstitutionsID = CTable::Add("Institutions", $Data);
				
				$HistoryContent .= "New Institution Added named '".$_POST["InstitutionName"]."'\n";				
			}
			
			// New Contact
			if($InstitutionsUsersID <= 0) {	
				if($_POST["InstitutionContactFirstName"] == "" || $_POST["InstitutionContactLastName"] == "") {
					return Array(0, "Please enter an Institution Contact Name or pick an existing Institution.");
				}
				
				if($_POST["InstitutionContactEmail"] == "") {
					return Array(0, "Please enter an Institution Contact Email or pick an existing Institution.");
				}				
				
				// Also add New Contact
				$Data = Array(
					"InstitutionsID"	=> $InstitutionsID,
					"UsersGroupsID"		=> 0,
					"Timestamp"			=> time(),
					"TimestampUpdated"	=> time(),
					"Type"				=> "Institution",
					"Username"			=> $_POST["Email"],
					"Password"			=> substr(md5(microtime()), 0, 8),
					"Title"				=> CFormat::SpecialChars($_POST["InstitutionContactTitle"]),
					"FirstName"			=> CFormat::SpecialChars($_POST["InstitutionContactFirstName"]),
					"LastName"			=> CFormat::SpecialChars($_POST["InstitutionContactLastName"]),					
					"Address1"			=> CFormat::SpecialChars($_POST["InstitutionContactAddress1"]),
					"Address2"			=> CFormat::SpecialChars($_POST["InstitutionContactAddress2"]),
					"City"				=> CFormat::SpecialChars($_POST["InstitutionContactCity"]),
					"State"				=> CFormat::SpecialChars($_POST["InstitutionContactState"]),
					"Zip"				=> CFormat::SpecialChars($_POST["InstitutionContactZip"]),
					"Campus"			=> CFormat::SpecialChars($_POST["InstitutionContactCampus"]),					
					"OfficePhone"		=> CFormat::SpecialChars($_POST["InstitutionContactOfficePhone"]),
					"MobilePhone"		=> CFormat::SpecialChars($_POST["InstitutionContactMobilePhone"]),
					"Email"				=> CFormat::SpecialChars($_POST["InstitutionContactEmail"]),
					"Active"			=> 1
				);
				
				$InstitutionsUsersID = CTable::Add("Users", $Data);		
				
				$HistoryContent .= "New Institution Contact Added named '".$_POST["InstitutionContactFirstName"]." ".$_POST["InstitutionContactLastName"]."'\n";				
			}
			
			$StartDate = strtotime($_POST["StartDate"]);
			if($StartDate <= 0) $StartDate = "0000-00-00";
			else				$StartDate = date("Y-m-d", $StartDate);
			
			$EndDate = strtotime($_POST["EndDate"]);
			if($EndDate <= 0) 	$EndDate = "0000-00-00";
			else				$EndDate = date("Y-m-d", $EndDate);			

			$Data = Array(			
				"Number" 				=> $_POST["Number"],
				"PONumber"				=> $_POST["PONumber"],
				"InstitutionsID" 		=> $InstitutionsID,
				"InstitutionsUsersID" 	=> $InstitutionsUsersID,
				"StartDate"				=> $StartDate,				
				"EndDate"				=> $EndDate,				
				"Frequency"				=> $_POST["Frequency"],
				"Author"				=> $_POST["Author"],
				"Course"				=> $_POST["Course"],
				"CourseNumber"			=> $_POST["CourseNumber"],
				"Quantity"				=> doubleval($_POST["Quantity"]),
				"StudentEnrollment"		=> $_POST["StudentEnrollment"],
				"Note"					=> $_POST["Note"]
			);
			
			if($SaveType == "Save") {
				$HistoryContent .= "AB saved\n";
			}
			
			if($ID <= 0) {
				$HistoryContent .= "New AB Created\n";
			}
			
			// AB has been saved, and the Status has changed
			if($ID > 0 && $AB->Status != $_POST["Status"] && $SaveType == "Save") {
				$Data["Status"]	= $_POST["Status"];			
				
				// The Status of the AB determines the Step (Step might even be irrelevant, and you might be 
				// able to just remove it)
				if($_POST["Status"] == "New") 					$Step = 1;
				if($_POST["Status"] == "Institution Feedback") 	$Step = 2;
				if($_POST["Status"] == "AB Manager Review") 	$Step = 3;
				if($_POST["Status"] == "Oracle Queue") 			$Step = 4;
				if($_POST["Status"] == "Complete") 				$Step = 5;
				
				$Data["Step"] = $Step;
				
				$HistoryContent .= "AB status changed from '".$AB->Status."' to '".CFormat::SpecialChars($Data["Status"])."'\n";
				
			// AB is advancing to the next Step, ignore if AB changed the Status
			}else if($SaveType == "Advance") {
				// New AB
				if($ID <= 0) {
					$Data["Status"] = "Institution Feedback";
					$Data["Step"] 	= 2;
				}else{
					$Data["Step"] 	= $AB->Step + 1;
					
					if($Data["Step"] == 3) $Data["Status"] = "AB Manager Review";
					if($Data["Step"] == 4) $Data["Status"] = "Oracle Queue";
					if($Data["Step"] == 5) $Data["Status"] = "Complete";										
				}
				
				$HistoryContent .= "AB advanced to '".$Data["Status"]."' step\n";
			}

			if($ID > 0) {				
				if(CTable::Update($this->Table, $ID, $Data) === false) {
					return Array(0, "Unable to edit record in database, please try again.");
				}
			}else{
				$Data["UsersID"]  = CSecurity::GetUsersID(); // Creator
				$Data["PassCode"] = CEncrypt::Encrypt(md5(microtime(true))."AATP!WOOT!");
							
				if(($ID = CTable::Add($this->Table, $Data)) === false) {
					return Array(0, "Unable to add record to database, please try again.");
				}
			}
			
			//------------------------------------------------------------------			
			// John said it's possible for there to be multiple AB Managers / AP Contacts on a single
			// AB in the future
			//------------------------------------------------------------------			
			CTable::RunQuery("DELETE FROM `AuditBillsUsers` WHERE `AuditBillsID` = ".intval($ID));
			
			$Data = Array(
				"AuditBillsID"  => $ID,
				"UsersID"		=> $_POST["ABManagerID"],
				"Type"			=> "AB Manager"
			);
			
			CTable::Add("AuditBillsUsers", $Data);
			
			$Data = Array(
				"AuditBillsID"  => $ID,
				"UsersID"		=> $_POST["APContactID"],
				"Type"			=> "AP Contact"
			);
			
			CTable::Add("AuditBillsUsers", $Data);
			
			$Data = Array(
				"AuditBillsID"  => $ID,
				"UsersID"		=> $_POST["LSCID"],
				"Type"			=> "LSC"
			);
			
			CTable::Add("AuditBillsUsers", $Data);				

			//------------------------------------------------------------------
			// ISBNs
			//------------------------------------------------------------------
			CTable::RunQuery("DELETE FROM `AuditBillsISBNs` WHERE `AuditBillsID` = ".intval($ID));
						
			$ISBNs = json_decode($_POST["ISBNs"], true);

			if(is_array($ISBNs)) {
				foreach($ISBNs as $ISBN) {
					$Data = Array(
						"AuditBillsID"  => $ID,
						"ISBN"			=> CFormat::SpecialChars($ISBN)
					);
					
					CTable::Add("AuditBillsISBNs", $Data);	
				}
			}
			
			//------------------------------------------------------------------
			// Product Solutions
			//------------------------------------------------------------------
			CTable::RunQuery("DELETE FROM `AuditBillsProductSolutions` WHERE `AuditBillsID` = ".intval($ID));
			
			$ProductSolutions = json_decode($_POST["ProductSolutions"], true);
			
			if(is_array($ProductSolutions)) {
				foreach($ProductSolutions as $ProductSolution) {
					// New Product Solution
					if($ProductSolution["ID"] <= 0) {
						$Data = Array(
							"UsersID"			=> CSecurity::GetUsersID(),
							"InstitutionsID"  	=> $InstitutionsID,
							"Timestamp"			=> time(),
							"TimestampUpdated"	=> time(),
							"Name"				=> CFormat::SpecialChars($ProductSolution["Name"]),
							"Price"				=> doubleval($ProductSolution["Price"]),
							"Public"			=> 0,
							"Active"			=> 1
						);
						
						$HistoryContent .= "New Product Solution created named '".CFormat::SpecialChars($_POST["ProductSolutionName"])."'\n";
					
						$ProductSolution["ID"] = CTable::Add("ProductSolutions", $Data);
					}
					
					$Data = Array(
						"AuditBillsID"  		=> $ID,
						"ProductSolutionsID"	=> $ProductSolution["ID"]
					);
					
					CTable::Add("AuditBillsProductSolutions", $Data);		
				}	
			}
			
			//------------------------------------------------------------------
			// Reload AB, add History of what happened
			//------------------------------------------------------------------			
			$AB->OnLoad($ID);
			
			$DataHistory = Array(
				"AuditBillsID"  => $ID,
				"UsersID"		=> CSecurity::GetUsersID(),
				"Timestamp"		=> time(),
				"IP"			=> $_SERVER["REMOTE_ADDR"],
				"Step"			=> $AB->Step,
				"Content"		=> CFormat::SpecialChars($HistoryContent)
			);			
			
			CTable::Add("AuditBillsHistory", $DataHistory);

			return Array($ID, "Record successfully entered / updated.");
		}

		//----------------------------------------------------------------------
		function Delete() {
		 	// Hide Audit Bills, do not Delete
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
				"LSC",
				"InstitutionContact",
				"Institution"
			);
			
			if(in_array($Type, $ValidTypes) == false) return Array(0, "Type not Valid");
			
			$Width  = 300;
			$Height = 310;
			
			if($Type == "Institution") $Height = 156;
			
			$Data = Array(
				"HTML" 		=> $this->FileControl->LoadFile("PreviewBox".$Type.".php", CFILE_TYPE_CONTENTS_OB),
				"Width" 	=> $Width,				
				"Height"	=> $Height
			);
			
			return Array(1, json_encode($Data));
		}
	};

	//==========================================================================
?>
