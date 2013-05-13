<?
	//==========================================================================
	/*
		Table for Milestones

		7/5/2012 7:54 AM
	*/
	//==========================================================================
	class CMilestones extends CTable {
		function __construct() {
			$this->Table = "Milestones";
		}

		//----------------------------------------------------------------------
		public static function OnCron() {
			
		}

		//----------------------------------------------------------------------
		function OnLoad($ID) {
			if(parent::OnLoadByID($ID) === false) {
				return false;
			}

			return true;
		}

		//----------------------------------------------------------------------
		function OnInit() {
			
		}
		
		/***********************************************************************
		 *	Static Functions
		 **********************************************************************/
		public static function GetStatusList() {
			return Array(
				"Active"			=> "Active",
				"Complete"			=> "Complete",
			);
		}
	};
?>
