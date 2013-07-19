<?
	//==========================================================================
	/*
		Table for Projects

		6/21/2012 8:50 AM
	*/
	//==========================================================================
	class CProjects extends CTable {
		public $ISBNs 					= Array();	
		public $ProductSolutions 		= Array();
		
		public $Users 					= Array();
		public $DistrictManagers		= Array();
		public $SalesReps				= Array();
		public $LSCs					= Array();
		public $LSSs					= Array();
		public $LSRs					= Array();
		public $JuniorCreativeAnalysts	= Array();
		public $CreativeAnalysts		= Array();
		public $CreativeConsultants		= Array();
		public $InstitutionalSalesReps	= Array();
		public $ProductManagers			= Array();
		
		public $Milestones				= Array();
		public $ToDos					= Array();
		public $ProductTypes			= Array();
		public $Tags					= Array();
		
		function __construct() {
			$this->Table = "Projects";
		}
		
		public static function GetAllStatus() {
			return array("1"=>"Live","2"=>"Dead","3"=>"Delayed","4"=>"Completed");
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
			$this->OnLoadUsers();
			$this->OnLoadMilestones();
			$this->OnLoadToDos();
			$this->OnLoadProductTypes();
			$this->OnLoadTags();
			
			return true;
		}
		
		function OnLoadUsers() {
			$DistrictManagers = new CTable("ProjectsDistrictManagers");
			if($DistrictManagers->OnLoadAll("WHERE `ProjectsID` = ".$this->ID) !== false) {
				foreach($DistrictManagers->Rows as $Row) {
					$this->DistrictManagers[] = $Row->UsersID;
				}
			}
			
			$SalesReps = new CTable("ProjectsSalesReps");
			if($SalesReps->OnLoadAll("WHERE `ProjectsID` = ".$this->ID) !== false) {
				foreach($SalesReps->Rows as $Row) {
					$this->SalesReps[] = $Row->UsersID;
				}
			}
			
			$LSCs = new CTable("ProjectsLSCs");
			if($LSCs->OnLoadAll("WHERE `ProjectsID` = ".$this->ID) !== false) {
				foreach($LSCs->Rows as $Row) {
					$this->LSCs[] = $Row->UsersID;
				}
			}
			
			$LSSs = new CTable("ProjectsLSSs");
			if($LSSs->OnLoadAll("WHERE `ProjectsID` = ".$this->ID) !== false) {
				foreach($LSSs->Rows as $Row) {
					$this->LSSs[] = $Row->UsersID;
				}
			}
			
			$LSRs = new CTable("ProjectsLSRs");
			if($LSRs->OnLoadAll("WHERE `ProjectsID` = ".$this->ID) !== false) {
				foreach($LSRs->Rows as $Row) {
					$this->LSRs[] = $Row->UsersID;
				}
			}
			
			$JuniorCreativeAnalysts = new CTable("ProjectsJuniorCreativeAnalysts");
			if($JuniorCreativeAnalysts->OnLoadAll("WHERE `ProjectsID` = ".$this->ID) !== false) {
				foreach($JuniorCreativeAnalysts->Rows as $Row) {
					$this->JuniorCreativeAnalysts[] = $Row->UsersID;
				}
			}
			
			$CreativeAnalysts = new CTable("ProjectsCreativeAnalysts");
			if($CreativeAnalysts->OnLoadAll("WHERE `ProjectsID` = ".$this->ID) !== false) {
				foreach($CreativeAnalysts->Rows as $Row) {
					$this->CreativeAnalysts[] = $Row->UsersID;
				}
			}
			
			$CreativeConsultants = new CTable("ProjectsCreativeConsultants");
			if($CreativeConsultants->OnLoadAll("WHERE `ProjectsID` = ".$this->ID) !== false) {
				foreach($CreativeConsultants->Rows as $Row) {
					$this->CreativeConsultants[] = $Row->UsersID;
				}
			}
			
			$InstitutionalSalesReps = new CTable("ProjectsInstitutionalSalesReps");
			if($InstitutionalSalesReps->OnLoadAll("WHERE `ProjectsID` = ".$this->ID) !== false) {
				foreach($InstitutionalSalesReps->Rows as $Row) {
					$this->InstitutionalSalesReps[] = $Row->UsersID;
				}
			}
			
			$ProductManagers = new CTable("ProjectsProductManagers");
			if($ProductManagers->OnLoadAll("WHERE `ProjectsID` = ".$this->ID) !== false) {
				foreach($ProductManagers->Rows as $Row) {
					$this->ProductManagers[] = $Row->UsersID;
				}
			}
		}
		
		function OnLoadMilestones() {
			$Milestones = new CProjectsMilestones();
			if($Milestones->OnLoadAll("WHERE `ProjectsID` = ".$this->ID." && `Deleted` = 0 ORDER BY `Created` ASC") !== false) {
				foreach($Milestones->Rows as $Row) {
					$Milestone = new CProjectsMilestones();
					if($Milestone->OnLoad($Row->ID) === false) continue;
					
					$this->Milestones[$Row->ID] = $Milestone;
				}
			}
			unset($Milestones);
		}
		
		function OnLoadToDos() {
			$ToDos = new CProjectsToDos();
			if($ToDos->OnLoadAll("WHERE `ProjectsID` = ".$this->ID." && `Deleted` = 0 ORDER BY `Created` ASC") !== false) {
				foreach($ToDos->Rows as $Row) {
					$ToDo = new CProjectsToDos();
					if($ToDo->OnLoad($Row->ID) === false) continue;
					
					$this->ToDos[$Row->ID] = $ToDo;
				}
			}
			unset($ToDos);
		}
		
		function OnLoadProductTypes() {
			$Types = new CTable("ProjectsProductTypes");
			if($Types->OnLoadAll("WHERE `ProjectsID` = ".$this->ID) !== false) {
				foreach($Types->Rows as $Row) {
					$ProductType = new CProductTypes();
					if($ProductType->OnLoad($Row->ProductTypesID) === false) continue;

					$this->ProductTypes[intval($ProductType->ID)] = $ProductType->Name;
				}
				asort($this->ProductTypes);
			}
			unset($Types);
		}
		
		function OnLoadTags() {
			$ProjectsTags = new CTable("ProjectsTags");
			if($ProjectsTags->OnLoadAll("WHERE `ProjectsID` = ".$this->ID)) {
				foreach($ProjectsTags->Rows as $Row) {
					$Tag = new CTags();
					if(! $Tag->OnLoad($Row->TagsID)) continue;

					$this->Tags[intval($Tag->ID)] = $Tag->Name;
				}
				asort($this->Tags);
			}
			unset($ProjectsTags);
		}
		
		function OnLoadISBNs() {
			$Temp = new CAuditBillsISBNs();
			if($Temp->OnLoadAll("WHERE `AuditBillsID` = ".$this->ID." ORDER BY `ISBN` ASC") !== false) {
				foreach($Temp->Rows as $Row) {
					$Item = new CAuditBillsISBNs();
					$Item->OnLoad($Row->ID);
					
					$this->ISBNs[$Row->ID] = $Item;
				}
			}		
		}
		
		function GetABManagers() {
			$UserList = Array();
			
			foreach($this->Users as $UserLink) {
				if($UserLink->Type != "AB Manager") continue;
				
				$User = new CUsers();
				if($User->OnLoad($UserLink->UsersID) !== false) {
					$UserList[$User->ID] = $User;
				}
			}
			
			return $UserList;
		}
		
		function GetAPContacts() {
			$UserList = Array();
			
			foreach($this->Users as $UserLink) {
				if($UserLink->Type != "AP Contact") continue;
							
				$User = new CUsers();
				if($User->OnLoad($UserLink->UsersID) !== false) {
					$UserList[$User->ID] = $User;
				}
			}
			
			return $UserList;
		}
		
		function GetLSCs() {
			$UserList = Array();
			$User = new CUsers();
			
			foreach($this->LSCs as $UserID) {				
				if($User->OnLoad($UserID) !== false) {					
					$UserList[] = $User->Rows->Current;
				}
			}
			
			return $UserList;
		}
		
		function GetDistrictManagersCompleteNames() {			
			return $this->GetUserCompleteNames($this->DistrictManagers);
		}
		
		function GetLSCsCompleteNames() {			
			return $this->GetUserCompleteNames($this->LSCs);
		}
		
		function GetLSSsCompleteNames() {			
			return $this->GetUserCompleteNames($this->LSSs);
		}
		
		function GetLSRsCompleteNames() {			
			return $this->GetUserCompleteNames($this->LSRs);
		}
		
		function GetJuniorCreativeAnalystsCompleteNames() {
			return $this->GetUserCompleteNames($this->JuniorCreativeAnalysts);
		}
		
		function GetCreativeAnalystsCompleteNames() {
			return $this->GetUserCompleteNames($this->CreativeAnalysts);
		}
		
		function GetCreativeConsultantsCompleteNames() {
			return $this->GetUserCompleteNames($this->CreativeConsultants);
		}
		
		function GetInstitutionalSalesRepsCompleteNames() {
			return $this->GetUserCompleteNames($this->InstitutionalSalesReps);
		}

		function StatusName() {
			$StatusList = CProjects::GetAllStatus();
			return (array_key_exists($this->Status,$StatusList))
						? $StatusList[$this->Status] 
						: "";		
		}
		
		function GetMilestonCompletionPercentage() {
			$CompleteMilestonesNumber = 0;
			$MilestoneNumber = count($this->Milestones);
			if($MilestoneNumber > 0) {
				foreach($this->Milestones as $Milestone) 
					if($Milestone->Status == "Complete") 
						$CompleteMilestonesNumber++;
				
				return ($CompleteMilestonesNumber / $MilestoneNumber)*100;
			} else {
				return 0;
			}
		}
		
		//======================================================================
		function GetStatusList() {
			return Array(
				"Approved" 			=> "Approved",
				"In Production" 	=> "In Production",
				"Open Lead" 		=> "Open Lead",
				"Printing" 			=> "Printing",
			);
		}
		
		function GetStatus() {
			if($this->Step4Timestamp > 0) {
				return "<strong style='color:#546b1c;'>complete</strong>";
			} else {
				return "<strong style='color:#961f1d;'>incomplete</strong>";
			}
		}
		
		//----------------------------------------------------------------------
		function GetLastModified() {
			$LastModified = $this->Modified;
			
			$ProjectMessages = new CProjectsMessages();
			$ProjectMessages->OnLoadAll("WHERE `ProjectsID` = ".$this->ID." ORDER BY `Created` DESC LIMIT 1");
			if($ProjectMessages->Created > $LastModified) {
				$LastModified = $ProjectMessages->Created;
			}
			$ProjectResources = new CProjectsResources();
			$ProjectResources->OnLoadAll("WHERE `ProjectsID` = ".$this->ID." ORDER BY `Created` DESC LIMIT 1");
			if($ProjectResources->Created > $LastModified) {
				$LastModified = $ProjectResources->Created;
			}
			$ProjectMilestones = new CProjectsMilestones();
			if($ProjectMilestones->OnLoadAll("WHERE `ProjectsID` = ".$this->ID."") !== false) {
				foreach($ProjectMilestones->Rows as $Row) {
					if($Row->Created > $LastModified) {
						$LastModified = $Row->Created;
					}
					$ProjectMilestoneChanges = new CTable("ProjectsMilestonesChanges");
					if($ProjectMilestoneChanges->OnLoadAll("WHERE `ProjectsMilestonesID` = ".$Row->ID." ORDER BY `Timestamp` DESC LIMIT 1") !== false) {
						foreach($ProjectMilestoneChanges->Rows as $MilestoneChange) {
							if($MilestoneChange->Timestamp > $LastModified) {
								$LastModified = $MilestoneChange->Timestamp;
							}
						}
					}
					$ProjectMilestoneToDos = new CProjectsMilestonesToDos();
					if($ProjectMilestoneToDos->OnLoadAll("WHERE `MilestoneID` = ".$Row->ID) !== false) {
						foreach($ProjectMilestoneToDos->Rows as $ToDo) {
							if($ToDo->Created > $LastModified) {
								$LastModified = $ToDo->Created;
							}
							$ProjectMilestoneToDosChanges = new CTable("ProjectsMilestonesToDosChanges");
							if($ProjectMilestoneToDosChanges->OnLoadAll("WHERE `ToDoID` = ".$ToDo->ID." ORDER BY `Timestamp` DESC LIMIT 1") !== false) {
								foreach($ProjectMilestoneToDosChanges->Rows as $ToDoChange) {
									if($ToDoChange->Timestamp > $LastModified) {
										$LastModified = $ToDoChange->Timestamp;
									}
								}
							}
						}
					}
				}
			}
			
			return $LastModified;
		}
		
		//----------------------------------------------------------------------
		function GetLastTouchedDays() {
			$Diff = time() - $this->GetLastModified();
			$Diff = floor($Diff / (60 * 60 * 24));
			return $Diff;
		}
		
		//----------------------------------------------------------------------
		function GetProductTypesList() {
			$ProductTypes = "";
			$Separator = "";
			foreach($this->ProductTypes as $ProductTypesID => $ProductTypesName) {
				//$ProductType = new CProductTypes;
				//$ProductType->OnLoad($ProductTypesID);
				$ProductTypes .= $Separator . $ProductTypesName;
				$Separator = ", ";
			}
			
			if($ProductTypes) return $ProductTypes;
			return "N/A";
		}
		
		//----------------------------------------------------------------------
		function GetUsers($UserType) {
			$UserList	= "";
			$User		= new CUsers();
			$Separator	= "";
			foreach($this->{$UserType} as $UserID) {
				if($User->OnLoad($UserID) !== false) {
					$UserList .= $Separator . $User->LastName . ", " . $User->FirstName;
					$Separator = "; ";
				}
			}
			
			if($UserList) {
				return $UserList;
			} else {
				return "N/A";
			}
		}
		//----------------------------------------------------------------------
		function GetFriendlyStatus() {
			$status = CProjects::GetAllStatus();
			
			return $status[$this->Status];
		}
		//----------------------------------------------------------------------
		//----------------------------------------------------------------------
		private function GetUserCompleteNames($UserIDs) {
			$UserList = Array();
			$User = new CUsers();
			
			foreach($UserIDs as $UserID)
				if($User->OnLoad($UserID) !== false)
					$UserList[] = $User->GetName();				
			
			
			return $UserList;
		}
	};
?>
