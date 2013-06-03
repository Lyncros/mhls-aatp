<?
	//==========================================================================
	/*
		Table for Projects To Dos

		10/31/2012 8:42 AM
	*/
	//==========================================================================
	class CProjectsToDos extends CTable {
		function __construct() {
			$this->Table = "ProjectsToDos";
		}

		public static function OnCron() {
			
		}

		//----------------------------------------------------------------------
		function OnLoad($ID) {
			if(parent::OnLoadByID($ID) === false) {
				return false;
			}

			return $this->OnInit();
		}

		//----------------------------------------------------------------------
		function OnInit() {
			return true;
		}
		
		//======================================================================
		function AssignedToUser() {
			return CTable::SelectByID("Users", $this->AssignedTo);
		}
	};
?>
