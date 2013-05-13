<?
	//==========================================================================
	/*
		[Permissions]
		[-]
	*/
	//=========================================================================
	class MMyAccount extends CModule {
		function __construct() {
			//$this->IsSecure = true;

			parent::__construct();
		}

		//---------------------------------------------------------------------
		function OnExecute() {
			return parent::OnExecute();
		}

		//---------------------------------------------------------------------
		function OnRenderJS() { 
			$this->FileControl->LoadFile("MMyAccount.js", CFILE_TYPE_JS);
		}

		//----------------------------------------------------------------------
		function OnRender() {
			$this->ThemeControl->FileControl->LoadFile("header.php");
			$this->FileControl->LoadFile("Render.php");
			$this->ThemeControl->FileControl->LoadFile("footer.php");
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

		//----------------------------------------------------------------------
		function Save() {
			$DataChanges = Array(
				"UsersID"					=> CSecurity::GetUsersID(),
				"Timestamp"					=> time(),
				"IP"						=> $_SERVER["REMOTE_ADDR"],

				"Title"						=> CSecurity::$User->Title,			
				"FirstName"					=> CSecurity::$User->FirstName,
				"MiddleInitial"				=> CSecurity::$User->MiddleInitial,
				"LastName"					=> CSecurity::$User->LastName,
				"Address1"					=> CSecurity::$User->Address1,
				"Address2"					=> CSecurity::$User->Address2,
				"City"						=> CSecurity::$User->City,
				"State"						=> CSecurity::$User->State,
				"Zip"						=> CSecurity::$User->Zip,
				
				"OfficePhone"				=> CSecurity::$User->OfficePhone,
				"MobilePhone"				=> CSecurity::$User->MobilePhone,				
				
				"Email"						=> CSecurity::$User->Email,

				"Password"					=> CSecurity::$User->Password,
				"CurrentPassword"			=> CEncrypt::Encrypt($_POST["CurrentPassword"]),
				"NewPassword1"				=> CEncrypt::Encrypt($_POST["Password1"]),
				"NewPassword2"				=> CEncrypt::Encrypt($_POST["Password2"]),
				"Success"					=> 0
			);

			$Data = Array(
				"TimestampUpdated" => time(),

				"Title"						=> CFormat::SpecialChars($_POST["Title"]),			
				"FirstName"					=> CFormat::SpecialChars($_POST["FirstName"]),
				"MiddleInitial" 			=> CFormat::SpecialChars($_POST["MiddleInitial"]),
				"LastName"					=> CFormat::SpecialChars($_POST["LastName"]),
				"Address1"					=> CFormat::SpecialChars($_POST["Address1"]),
				"Address2"					=> CFormat::SpecialChars($_POST["Address2"]),
				"City"						=> CFormat::SpecialChars($_POST["City"]),
				"State"						=> CFormat::SpecialChars($_POST["State"]),
				"Zip"						=> CFormat::SpecialChars($_POST["Zip"]),
				"OfficePhone"				=> CFormat::SpecialChars($_POST["OfficePhone"]),
				"MobilePhone"				=> CFormat::SpecialChars($_POST["MobilePhone"]),				
				"Email"						=> CFormat::SpecialChars($_POST["Email"]),
			);

			if($_POST["Password1"] != "" || $_POST["CurrentPassword"] != "") {
				if($_POST["CurrentPassword"] != CSecurity::$User->GetPassword()) {
					CTable::Add("UsersUpdates", $DataChanges);
					return Array(0, "The 'Current Password' entered is incorrect.");
				}else
				if($_POST["Password1"] != $_POST["Password2"]) {
					CTable::Add("UsersUpdates", $DataChanges);
					return Array(0, "The two passwords entered do not match.");
				}else
				if(strlen($_POST["Password1"]) < 8) {
					CTable::Add("UsersUpdates", $DataChanges);
					return Array(0, "Your new password must be at least 8 characters long.");
				}

				$Data["Password"] = CEncrypt::Encrypt($_POST["Password1"]);
			}
			
			for($i = 0;;$i++) {
				if(!isset($_POST["Notice_".$i."_Values"])) break;

				$DataNotifications	= json_decode($_POST["Notice_".$i."_Values"]);

				//$Popup	= intval($_POST["Notice_".$i."_Popup"]);
				$Email	= intval($_POST["Notice_".$i."_Email"]);
				//$SMS	= intval($_POST["Notice_".$i."_SMS"]);

				$SubData = Array(
					//"BusinessesID" => intval(CSecurity::GetBusinessesID()), 
					"UsersID"	=> intval(CSecurity::GetUsersID()),
					"Type"		=> $DataNotifications->Type,
					"Name"		=> $DataNotifications->Name,
					"SubName"	=> $DataNotifications->SubName,
					//"Popup"		=> $Popup,
					"Email"		=> $Email,
					//"SMS"		=> $SMS
				);

				$NSubClass = new CNotifierSubscriptions();
				if($NSubClass->OnLoadByUsersID(CSecurity::GetUsersID(), $DataNotifications->Type, $DataNotifications->Name, $DataNotifications->SubName) === false) {
					CTable::Add("NotifierSubscriptions", $SubData);
				}else{
					CTable::Update("NotifierSubscriptions", $NSubClass->ID, $SubData);
				}
			}

			if(CTable::Update("Users", CSecurity::GetUsersID(), $Data) === false) {
				CTable::Add("UsersUpdates", $DataChanges);
				return Array(0, "Unable to update your Information, please try again.");
			}

			unset($Data["TimestampUpdated"]);
			unset($Data["Password"]);

			$DataChanges["Success"] = 1;
			CTable::Add("UsersUpdates", $DataChanges);

			//------------------------------------------------------------------
			// Send Email to Admins
			//------------------------------------------------------------------
			//CNotifier::Push("Module", "MyAccount", "Profile Updated", $DataChanges);

			//------------------------------------------------------------------

			if(CSecurity::$User->Email == "" && $_POST["Email"] != "") {
				return Array(2, "Success");
			}else{
				return Array(1, "Success");
			}
		}
	};

	//=========================================================================
?>
