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
        
        function Save($MilestoneID, $Data, $Extra) {
			if($MilestoneID > 0) {
				$Temp = new CProjectsMilestones();
				$Temp->OnLoad($MilestoneID);
				$ChangeData = Array(
					"ProjectsMilestonesID"		=> $MilestoneID,
					"Timestamp"					=> time(),
					"UsersID"					=> CSecurity::GetUsersID(),
					"IPAddress"					=> $Extra["REMOTE_ADDR"],
					"Old"						=> serialize($Temp->Rows->Current),
					"New"						=> serialize($Data),
				);
                
				CTable::Add("ProjectsMilestonesChanges", $ChangeData);
				
                return CTable::Update("ProjectsMilestones", $MilestoneID, $Data);
			} else {
				$Data["Created"]				= time();
				$Data["CreatedUsersID"]			= CSecurity::GetUsersID();
				$Data["CreatedIPAddress"]		= $Extra["REMOTE_ADDR"];
				
                return CTable::Add("ProjectsMilestones", $Data);
			}
        }
        
		//======================================================================
		function IsComplete() {
			return $this->Status && $this->Status == "Complete";
		}
		
		function AssignedToUser() {
			return CTable::SelectByID("Users", $this->AssignedTo);
		}
        
        /**
         * Returns a list of ProjectsMilestones that are assigned to the received user.
         */
        function OnLoadByAssignedTo($AssignedToUserId) {
            return $this->OnLoadByQuery("SELECT PM.*, P.ProductNumber, P.School  
                FROM `ProjectsMilestones` AS PM 
                JOIN `Projects` as P ON P.ID = PM.ProjectsID
                WHERE PM.`Deleted` = 0 AND PM.AssignedTo = ".$AssignedToUserId);
        }
	};
?>
