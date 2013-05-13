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
	class MInstitutions extends CModuleGeneric {
		function __construct() {
			$this->Table		= "Institutions";
			$this->Classname	= "CInstitutions";

			parent::__construct();
		}

		//----------------------------------------------------------------------
		function OnExecute() {
			return parent::OnExecute();
		}

		//----------------------------------------------------------------------
		function OnRenderJS() { 
			$this->FileControl->LoadFile("MInstitutions.js", CFILE_TYPE_JS);
		}

		//----------------------------------------------------------------------
		function OnRenderCSS() { 
			$this->FileControl->LoadFile("style.css", CFILE_TYPE_CSS);
		}

		//----------------------------------------------------------------------
		function OnRender() {
			$Page = $_GET["Page"];

			parent::OnRender();
		}

		//----------------------------------------------------------------------
		function OnAJAX($Action) {
			if(parent::CanAccess($Action) == false) {
				return Array(0, "You do not have permission to perform this action");
			}

			return parent::OnAJAX($Action);
		}

		//=====================================================================
		function AddEdit() {
			$ID = $_POST["ID"];

			//CTable::Add/Update automatically escapes
			$Data = Array(
				"UsersID"			=> CSecurity::GetUsersID(),
				"TimestampUpdated"	=> time(),
				"Name"				=> CFormat::SpecialChars($_POST["Name"]),
				"Address1"			=> CFormat::SpecialChars($_POST["Address1"]),
				"Address2"			=> CFormat::SpecialChars($_POST["Address2"]),
				"City"				=> CFormat::SpecialChars($_POST["City"]),
				"State"				=> CFormat::SpecialChars($_POST["State"]),
				"Zip"				=> CFormat::SpecialChars($_POST["Zip"]),
				"Active"			=> intval($_POST["Active"])
			);

			if($ID > 0) {
				if(CTable::Update($this->Table, $ID, $Data) === false) {
					return Array(0, "Unable to update record, please try again");
				}
			}else{
				$Data["Timestamp"] = time();

				if(($ID = CTable::Add($this->Table, $Data)) === false) {
					return Array(0, "Unable to add record, please try again");
				}
			}

			return Array($ID, "Record successfully entered / updated.");
		}

		//----------------------------------------------------------------------
		function Delete() {
			return parent::Delete();
		}
	};

	//==========================================================================
?>
