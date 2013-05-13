<?
	//==========================================================================
	/*
		[Permissions]
		AddEdit
		Delete
		Window_AddEdit
		Window_Delete
		[-]
	*/
	//==========================================================================
	class MProductSolutions extends CModuleGeneric {
		function __construct() {
			$this->Table		= "ProductSolutions";
			$this->Classname	= "CProductSolutions";

			parent::__construct();
		}

		//----------------------------------------------------------------------
		function OnExecute() {
			return parent::OnExecute();
		}

		//----------------------------------------------------------------------
		function OnRenderJS() { 
			$this->FileControl->LoadFile("MProductSolutions.js", CFILE_TYPE_JS);
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
				"InstitutionsID"	=> intval($_POST["InstitutionsID"]),
				"UsersID"			=> CSecurity::GetUsersID(),
				"TimestampUpdated"	=> time(),
				"Name"				=> CFormat::SpecialChars($_POST["Name"]),
				"Description"		=> CFormat::SpecialChars($_POST["Description"]),
				"Price"				=> doubleval($_POST["Price"]),
				"Public"			=> intval($_POST["Public"]),
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
