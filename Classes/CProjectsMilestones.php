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
		function IsComplete() {
			return $this->Status && $this->Status == "Complete";
		}
		
		function AssignedToUser() {
			return CTable::SelectByID("Users", $this->AssignedTo);
		}
        
        function OnLoadByAssignedTo($AssignedToUserId) {
            return $this->OnLoadAll('WHERE `AssignedTo` = ' . $AssignedToUserId . ' AND `Deleted` = 0');
        }
	};
?>
