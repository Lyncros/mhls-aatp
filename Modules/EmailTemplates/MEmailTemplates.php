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
	class MEmailTemplates extends CModuleGeneric {
		function __construct() {
			$this->Table		= "EmailTemplates";
			$this->Classname	= "CEmailTemplates";

			parent::__construct();
		}

		//----------------------------------------------------------------------
		function OnExecute() {
			if(CSecurity::CanAccess($this->Name, "AddEdit")) {
				$this->Actions["New Email Template"] = Array("OnClick" => "MEmailTemplates.Window_AddEdit(0);", "Icon" => "Icon_Popup");
			}

			return parent::OnExecute();
		}

		//----------------------------------------------------------------------
		function OnRenderJS() { 
			$this->FileControl->LoadFile("MEmailTemplates.js", CFILE_TYPE_JS);
		}

		//----------------------------------------------------------------------
		function OnRender() {
			parent::OnRender();
		}

		//----------------------------------------------------------------------
		function OnAJAX($Action) {
			if(parent::CanAccess($Action) == false) {
				return Array(0, "You do not have permission to perform this action");
			}

			return parent::OnAJAX($Action);
		}

		//======================================================================
		function AddEdit() {
			$ID = $_POST["ID"];

			//CTable::Add/Update automatically escapes
			$Data = Array(
				"BusinessesID"		=> intval(CSecurity::GetBusinessesID()),
				"Type"				=> ($_POST["Type"]),
				"Name"				=> ($_POST["Name"]),
				"SubName"			=> ($_POST["SubName"]),
				"Content"			=> $_POST["Content"],
				"FromName"			=> ($_POST["FromName"]),
				"FromEmail"			=> ($_POST["FromEmail"]),
				"ReplyTo"			=> ($_POST["ReplyTo"]),
				"Subject"			=> ($_POST["Subject"]),
				"Public"			=> 0
			);

			if(CSecurity::IsSuperAdmin()) {
				$Data["Public"] = intval($_POST["Public"]);
			}

			if($ID > 0) {
				if(CTable::Update($this->Table, $ID, $Data) === false) {
					return Array(0, "Unable to add record to database, please try again.");
				}
			}else{
				if(($ID = CTable::Add($this->Table, $Data)) === false) {
					return Array(0, "Unable to add record to database, please try again.");
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
