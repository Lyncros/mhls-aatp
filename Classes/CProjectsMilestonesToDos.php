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
        
        /**
         * Returns an array containing every not deleted TODOs that belongs 
         * to the Milestone which ID was received.
         */
        public function OnLoadByMilestone($MilestoneID) {
            return $this->OnLoadAll("WHERE `MilestoneID` = ".$MilestoneID." AND `Deleted` = 0");
        }
        
        public function OnloadByAssignedTo($UserID) {
            return $this->OnLoadAll("WHERE `AssignedTo` = ".$UserID." AND `Deleted` = 0");
        }
		
		//======================================================================
		
	};
?>
