<?
	//==========================================================================
	/*
		[Permissions]
		View
		ClearAll
		[-]
	*/
	//=========================================================================
	class MDebug extends CModuleGeneric {
		public $Table = "Debug";

		function __construct() {
			$this->Table		= "Debug";
			$this->Classname	= "CDebug";

			parent::__construct();
		}

		//---------------------------------------------------------------------
		function OnExecute() {
			if(CSecurity::CanAccess($this->Name, "AddEdit")) {
				//$this->Actions["New Debug"] = "MDebug.Window_AddEdit(0);";
			}

			//Only Super Admins can access this Module
			if(CSecurity::IsSuperAdmin() == false) {
				$this->Parent->LoadModule("Dashboard");
				return;
			}

			return parent::OnExecute();
		}

		//---------------------------------------------------------------------
		function OnRenderJS() { 
			$this->FileControl->LoadFile("MDebug.js", CFILE_TYPE_JS);
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

			if($Action == "ClearAll") {
				return $this->ClearAll();
			}

			return parent::OnAJAX($Action);
		}

		//======================================================================
		function ClearAll() {
			$Query = "DELETE FROM `".$this->Table."`";

			if(CTable::RunQuery($Query) === false) {
				return Array(0, "Unable to delete Login Attempts");
			}

			return Array(1, "Success");
		}
	};

	//==========================================================================
?>
