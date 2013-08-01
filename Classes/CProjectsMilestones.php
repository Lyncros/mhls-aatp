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
        
        function Save($Values) {
            $MilestoneID = intval($Values["MilestoneID"]);
            
			$Data = Array(
				"ProjectsID"			=> intval($Values["ProjectsID"]),
				"Name"					=> htmlspecialchars($Values["Name"]),
				"CustomerApproval"		=> intval($Values["CustomerApproval"]),
				"Summary"				=> htmlspecialchars($Values["Summary"]),
				"EstimatedStartDate"	=> strtotime($Values["EstimatedStartDate"]),
				"ExpectedDeliveryDate"	=> strtotime($Values["ExpectedDeliveryDate"]),
				"ActualDeliveryDate"	=> strtotime($Values["ActualDeliveryDate"]),
				"PlantAllocated"		=> htmlspecialchars($Values["PlantAllocated"]),
				"AssignedTo"			=> intval($Values["AssignedTo"]),
				"Status"				=> (intval($Values["Status"])==0)?"Active":"Complete",
			);
            
			if($MilestoneID > 0) {
				$Temp = new CProjectsMilestones();
				$Temp->OnLoad($MilestoneID);
				$ChangeData = Array(
					"ProjectsMilestonesID"		=> $MilestoneID,
					"Timestamp"					=> time(),
					"UsersID"					=> CSecurity::GetUsersID(),
					"IPAddress"					=> $Values["REMOTE_ADDR"],
					"Old"						=> serialize($Temp->Rows->Current),
					"New"						=> serialize($Data),
				);
                
				CTable::Add("ProjectsMilestonesChanges", $ChangeData);
				
                return CTable::Update("ProjectsMilestones", $MilestoneID, $Data);
			} else {
				$Data["Created"]				= time();
				$Data["CreatedUsersID"]			= CSecurity::GetUsersID();
				$Data["CreatedIPAddress"]		= $Values["REMOTE_ADDR"];
				
                return CTable::Add("ProjectsMilestones", $Data);
			}
        }
        
        function DeleteMilestone($MilestoneID, $UsersID, $RemoteIP) {
			$Data = Array(
				"Deleted"			=> time(),
				"DeletedUsersID"	=> $UsersID,
				"DeletedIPAddress"	=> $RemoteIP,
			);

            return CTable::Update("ProjectsMilestones", $MilestoneID, $Data);
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
