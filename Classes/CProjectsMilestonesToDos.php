<?
	//==========================================================================
	/*
		Table for Projects Milestones To Dos

		7/12/2012 1:10 PM
	*/
	//==========================================================================
	class CProjectsMilestonesToDos extends CTable {
		function __construct() {
			$this->Table = "ProjectsMilestonesToDos";
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
		
	};
?>
