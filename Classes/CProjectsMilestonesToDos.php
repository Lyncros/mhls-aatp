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
        
        function Save($ToDoID, $Data, $Extra) {
			if($ToDoID > 0) {
				$Temp = new CProjectsMilestonesToDos();
				$Temp->OnLoad($ToDoID);
				$ChangeData = Array(
					"ToDoID"			=> $ToDoID,
					"Timestamp"			=> time(),
					"UsersID"			=> CSecurity::GetUsersID(),
					"IPAddress"			=> $Extra["REMOTE_ADDR"],
					"Old"				=> serialize($Temp->Rows->Current),
					"New"				=> serialize($Data),
				);

				CTable::Add("ProjectsMilestonesToDosChanges", $ChangeData);
				
                return CTable::Update("ProjectsMilestonesToDos", $ToDoID, $Data);
			} else {
				$Data["Created"]				= time();
				$Data["CreatedUsersID"]			= CSecurity::GetUsersID();
				$Data["CreatedIPAddress"]		= $Extra["REMOTE_ADDR"];
				
                return CTable::Add("ProjectsMilestonesToDos", $Data);
			}
        }
        
        /**
         * Returns an array containing every not deleted TODOs that belongs 
         * to the Milestone which ID was received.
         */
        public function OnLoadByMilestone($MilestoneID) {
            return $this->OnLoadAll("WHERE `MilestoneID` = ".$MilestoneID." AND `Deleted` = 0");
        }
        
        public function OnloadByAssignedTo($AssignedToUserId) {
            return $this->OnLoadByQuery("SELECT T.*, P.ID as ProjectsID, P.ProductNumber, P.School
                FROM `ProjectsMilestonesToDos` AS T
                JOIN `ProjectsMilestones` AS PM ON T.MilestoneID = PM.ID
                JOIN `Projects` as P ON P.ID = PM.ProjectsID
                WHERE T.`Deleted` = 0 AND T.AssignedTo = ".$AssignedToUserId);
        }
		
		//======================================================================
		
	};
?>
