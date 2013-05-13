<?
	//==========================================================================
	/*
		Table for Projects Milestones

		7/5/2012 1:10 PM
	*/
	//==========================================================================
	class CProjectsMilestones extends CTable {
		function __construct() {
			$this->Table = "ProjectsMilestones";
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
