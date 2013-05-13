<?
	//==========================================================================
	/*
		Table for Projects Messages

		7/11/2012 9:10 AM
	*/
	//==========================================================================
	class CProjectsMessages extends CTable {
		function __construct() {
			$this->Table = "ProjectsMessages";
		}

		public static function OnCron() {
			
		}

		function OnLoad($ID) {
			if(parent::OnLoadByID($ID) === false) {
				return false;
			}

			return $this->OnInit();
		}

		function OnInit() {
			return true;
		}
		
		//======================================================================
		
	};
?>
