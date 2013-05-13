<?
	//==========================================================================
	/*
		Table for AuditBills

		3/28/2012 12:52 PM
	*/
	//==========================================================================
	class CAuditBills extends CTable {
		public $ISBNs 				= Array();	
		public $ProductSolutions 	= Array();
		public $Users 				= Array();
		
		function __construct() {
			$this->Table = "AuditBills";
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
			$this->ProductSolutions = Array();		
			
			$Temp = new CAuditBillsProductSolutions();
			if($Temp->OnLoadAll("WHERE `AuditBillsID` = ".$this->ID) !== false) {
				foreach($Temp->Rows as $Row) {
					$Item = new CAuditBillsProductSolutions();
					$Item->OnLoad($Row->ID);
					
					$this->ProductSolutions[$Row->ID] = $Item;
				}
			}
						
			$this->Users = Array();		
			
			$Temp = new CAuditBillsUsers();
			if($Temp->OnLoadAll("WHERE `AuditBillsID` = ".$this->ID) !== false) {
				foreach($Temp->Rows as $Row) {
					$Item = new CAuditBillsUsers();
					$Item->OnLoad($Row->ID);
					
					$this->Users[$Row->ID] = $Item;
				}
			}
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
			
			foreach($this->Users as $UserLink) {
				if($UserLink->Type != "LSC") continue;
							
				$User = new CUsers();
				if($User->OnLoad($UserLink->UsersID) !== false) {
					$UserList[$User->ID] = $User;
				}
			}
			
			return $UserList;
		}		
		
		//======================================================================
		function GetStatusList() {
			return Array(
				"New" 					=> "New",
				"Institution Feedback" 	=> "Institution Feedback", 
				"AB Manager Review" 	=> "AB Manager Review", 
				"Oracle Queue" 			=> "Oracle Queue", 
				"Complete" 				=> "Complete"
			);
		}
		
		function GetStatus() {
			if($this->Step4Timestamp > 0) {
				return "<strong style='color:#546b1c;'>complete</strong>";
			} else {
				return "<strong style='color:#961f1d;'>incomplete</strong>";
			}
		}
	};
?>
