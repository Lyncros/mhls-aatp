<?
	//==========================================================================
	/*
		[Permissions]
		Customers
		AddEdit
		Delete
		Window_AddEdit
		Window_Delete
		GetUserAutocomplete
		[-]
	*/
	//==========================================================================
	class MUsers extends CModuleGeneric {
		function __construct() {
			$this->Table		= "Users";
			$this->Classname	= "CUsers";

			parent::__construct();
		}

		//----------------------------------------------------------------------
		function OnExecute() {
			return parent::OnExecute();
		}

		//----------------------------------------------------------------------
		function OnRenderJS() { 
			$this->FileControl->LoadFile("MUsers.js", CFILE_TYPE_JS);
		}

		//----------------------------------------------------------------------
		function OnRenderCSS() { 
			$this->FileControl->LoadFile("style.css", CFILE_TYPE_CSS);
		}

		//----------------------------------------------------------------------
		function OnRender() {
			$Page = $_GET["Page"];

			if($Page == "Add") {
				$this->ThemeControl->FileControl->LoadFile("header.php");
				$this->FileControl->LoadFile("View.php");			
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
			}

			return parent::OnAJAX($Action);
		}

		//=====================================================================
		function AddEdit() {
			$ID = $_POST["ID"];

			$_POST["Username"] = CFormat::SpecialChars($_POST["Username"]);

			if($ID > 0 && $this->TableObject->Username != $_POST["Username"] && CUsers::AccountExists($_POST["Username"])) {
				return Array(0, "The Username entered already exists.");
			}

			//CTable::Add/Update automatically escapes
			$Data = Array(
				"BusinessesID"		=> intval(CSecurity::GetBusinessesID()),
				"TimestampUpdated"	=> time(),
				"Username"			=> $_POST["Username"],
				"Company"			=> CFormat::SpecialChars($_POST["Company"]),
				"FirstName"			=> CFormat::SpecialChars($_POST["FirstName"]),
				"LastName"			=> CFormat::SpecialChars($_POST["LastName"]),
				"Address1"			=> CFormat::SpecialChars($_POST["Address1"]),
				"Address2"			=> CFormat::SpecialChars($_POST["Address2"]),
				"City"				=> CFormat::SpecialChars($_POST["City"]),
				"State"				=> CFormat::SpecialChars($_POST["State"]),
				"Zip"				=> CFormat::SpecialChars($_POST["Zip"]),
				"Country"			=> CFormat::SpecialChars($_POST["Country"]),
				"Phone"				=> CFormat::SpecialChars($_POST["Phone"]),
				"Fax"				=> CFormat::SpecialChars($_POST["Fax"]),
				"Email"				=> CFormat::SpecialChars($_POST["Email"]),
				"PhoneSMS"			=> CFormat::SpecialChars($_POST["PhoneSMS"])
			);

			if(CSecurity::IsAdmin($this->Name)) {
				$Data["UsersGroupsID"] = intval($_POST["UsersGroupsID"]);
			}

			$UpdatedPassword = false;

			if(strlen($_POST["NewPassword1"]) > 0) {
				if($_POST["NewPassword1"] !== $_POST["NewPassword2"]) {
					return Array(0, "Passwords entered do not Match");
				}else
				if(strlen($_POST["NewPassword1"]) < 6) {
					return Array(0, "The New Password entered must be at least 6 characters long");
				}else{
					$Data["Password"] = CEncrypt::Encrypt($_POST["NewPassword1"]);

					$UpdatedPassword = true;
				}
			}

			if($ID > 0) {
				if(CTable::Update($this->Table, $ID, $Data) === false) {
					return Array(0, "Unable to update record, please try again");
				}else if($UpdatedPassword) {
					$Parms = Array(
						"Username"	=> $_POST["Username"],
						"Password"	=> $_POST["NewPassword1"]
					);

					CNotifier::PushEmail($_POST["Email"], "Module", "Users", "New Password", $Parms);
				}
			}else{
				$Data["Timestamp"] = time();

				if(($ID = CTable::Add($this->Table, $Data)) !== false) {
					$Parms = Array(
						"Username"	=> $_POST["Username"],
						"Password"	=> $_POST["NewPassword1"]
					);

					CNotifier::PushEmail($_POST["Email"], "Module", "Users", "New", $Parms);

					if($_POST["AddToAddressBook"] == 1) {
						$Data = Array(
							"BusinessesID"			=> intval(CSecurity::GetBusinessesID()),
							"UsersID"				=> $ID,
							"Company"				=> CFormat::SpecialChars($_POST["Company"]),
							"FirstName"				=> CFormat::SpecialChars($_POST["FirstName"]),
							"LastName"				=> CFormat::SpecialChars($_POST["LastName"]),
							"Address1"				=> CFormat::SpecialChars($_POST["Address1"]),
							"Address2"				=> CFormat::SpecialChars($_POST["Address2"]),
							"City"					=> CFormat::SpecialChars($_POST["City"]),
							"State"					=> CFormat::SpecialChars($_POST["State"]),
							"Zip"					=> CFormat::SpecialChars($_POST["Zip"]),
							"Country"				=> CFormat::SpecialChars($_POST["Country"]),
							"Phone"					=> CFormat::SpecialChars($_POST["Phone"]),
							"Fax"					=> CFormat::SpecialChars($_POST["Fax"]),
							"Email"					=> CFormat::SpecialChars($_POST["Email"])
						);

						CTable::Add("Addresses", $Data);
					}
				}else{
					return Array(0, "Unable to add record, please try again");
				}
			}

			return Array($ID, "Record successfully entered / updated.");
		}

		//----------------------------------------------------------------------
		function Delete() {
			return parent::Delete();
		}

		//----------------------------------------------------------------------
		function GetUserAutocomplete() {
			$q = $_GET["q"];

			$Content = "";

			$UserTable = new CUsers();
			if($UserTable->OnLoadAll("WHERE `BusinessesID` = ".intval(CSecurity::GetBusinessesID())." && `Username` LIKE '%".mysql_real_escape_string($q)."%' ORDER BY `Username` ASC") == false) {
				return Array(-1, "No Users Available");
			}

			if(count($UserTable->Rows) <= 0) {
				return Array(-1, "No Users Available");
			}

			foreach($UserTable->Rows as $Row) {
				$Content .= $Row->Username."|".$Row->ID."\n";
			}

			return Array(-1, $Content);
		}

		//----------------------------------------------------------------------
		function Save() {
			$ID	= intval($_POST["ID"]);
			
			if($_POST["Type"] == "Institution") {
				$_POST["UsersGroupsID"] = 0;
			}

			$Data = Array(
				"InstitutionsID"		=> intval($_POST["InstitutionsID"]),			
				"UsersGroupsID"			=> intval($_POST["UsersGroupsID"]),
				"TimestampUpdated"		=> time(),
				"Type"					=> $_POST["Type"],
				"Username"				=> $_POST["Username"],
				"Title"					=> CFormat::SpecialChars($_POST["Title"]),
				"FirstName"				=> CFormat::SpecialChars($_POST["FirstName"]),
				"MiddleInitial"			=> CFormat::SpecialChars($_POST["MiddleInitial"]),
				"LastName"				=> CFormat::SpecialChars($_POST["LastName"]),
				"Address1"				=> CFormat::SpecialChars($_POST["Address1"]),
				"Address2"				=> CFormat::SpecialChars($_POST["Address2"]),
				"City"					=> CFormat::SpecialChars($_POST["City"]),
				"State"					=> CFormat::SpecialChars($_POST["State"]),
				"Zip"					=> CFormat::SpecialChars($_POST["Zip"]),
				"Campus"				=> CFormat::SpecialChars($_POST["Campus"]),				
				"OfficePhone"			=> CFormat::SpecialChars($_POST["OfficePhone"]),
				"MobilePhone"			=> CFormat::SpecialChars($_POST["MobilePhone"]),				
				"Email"					=> CFormat::SpecialChars($_POST["Email"]),
				"Active"				=> intval($_POST["Active"])
			);

			if($_POST["Password1"] != "") {
				if($_POST["Password1"] != $_POST["Password2"]) {
					return Array(0, "The two passwords entered must match.");
				}else
				if(strlen($_POST["Password1"]) < 8) {
					return Array(0, "The new password entered must be at least 8 characters long.");
				}
				
				$Data["Password"] = CEncrypt::Encrypt($_POST["Password1"]);
			}

			if($ID <= 0) {
				$Data["Timestamp"] = time();

				$ID = CTable::Add("Users", $Data);
			}else{
				CTable::Update("Users", $ID, $Data);
			}			

			return Array($ID, "User Profile saved.");
		}
	};

	//==========================================================================
?>
