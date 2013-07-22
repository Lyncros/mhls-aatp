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
	//=========================================================================
	class MBannedIPs extends CModuleGeneric {
		public $Table = "BannedIPs";

		function __construct() {
			$this->Table		= "BannedIPs";
			$this->Classname	= "CBannedIPs";

			parent::__construct();
		}

		//---------------------------------------------------------------------
		function OnExecute() {
			if(CSecurity::CanAccess($this->Name, "AddEdit")) {
				$this->Actions["New Banned IP"] = Array("OnClick" => "MBannedIPs.Window_AddEdit(0);", "Icon" => "Icon_Popup");
			}

			return parent::OnExecute();
		}

		//---------------------------------------------------------------------
		function OnRenderJS() { 
			$this->FileControl->LoadFile("MBannedIPs.js", CFILE_TYPE_JS);
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

			if($Action == "Ban") {
				CBannedIPs::Ban($_POST["IP"]);

				return Array(1, "");
			}else
			if($Action == "Unban") {
				CBannedIPs::Unban($_POST["IP"]);

				return Array(1, "");
			}

			return parent::OnAJAX($Action);
		}

		//=====================================================================
		function AddEdit() {
			$ID = $_POST["ID"];

			//CTable::Add/Update automatically escapes
			$Data = Array(
				"BusinessesID"		=> intval(CSecurity::GetBusinessesID()),
				"IP"				=> htmlspecialchars($_POST["IP"]),
				"ExpireMinutes"		=> doubleval($_POST["ExpireMinutes"]),
				"Reason"			=> htmlspecialchars($_POST["Reason"])
			);

			if($ID > 0) {
				if(CTable::Update($this->Table, $ID, $Data) === false) {
					return Array(0, "Unable to add record to database, please try again.");
				}
			}else{
				$Data["Timestamp"] = time();

				if(($ID = CTable::Add($this->Table, $Data)) === false) {
					return Array(0, "Unable to add record to database, please try again.");
				}
			}

			return Array($ID, "Record successfully entered / updated.");
		}

		//---------------------------------------------------------------------
		function Delete() {
			return parent::Delete();
		}
	};

	//=========================================================================
?>
