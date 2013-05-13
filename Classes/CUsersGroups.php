<?
	//==========================================================================
	/*
		Table for UsersGroups

		SuperAdmin overrides all Permissions

		8/9/2011 7:29 AM
	*/
	//=========================================================================
	class CUsersGroups extends CTable {
		public $Permissions		= Array();

		function __construct() {
			$this->Table = "UsersGroups";
		}

		function OnLoad($ID) {
			if(parent::OnLoadByID($ID) === false) {
				//trigger_error("Unable to load (".$this->Table.") : $ID", E_USER_NOTICE);
				return false;
			}

			$this->Permissions = Array();

			if(!$this->Rows->SuperAdmin) {
				$PerRows = new CUsersGroupsPermissions();
				if($PerRows->OnLoadAll("WHERE `UsersGroupsID` = ".intval($ID)." ORDER BY `Name` ASC, `Action` ASC") !== false) {
					foreach($PerRows->Rows as $Row) {
						$NewPer = new CUsersGroupsPermissions();

						if($NewPer->OnLoad($Row->ID) !== false) {
							$this->Permissions[$Row->ID] = $NewPer;
						}

						unset($NewPer);
					}
				}
			}

			return true;
		}

		/*
		function CanAccess($Name, $Action, $Type) {
			if($this->SuperAdmin) return true;

			foreach($this->Permissions as $Per) {
				if($Type != $Per->Type) continue;

				if($Action === "Admin") {
					if($Per->Name === $Name && ($Per->Action === $Action)) {
						return true;
					}
				}else{
					if($Per->Name === $Name && ($Per->Action === $Action || $Per->Action === "Admin")) {
						return true;
					}
				}
			}

			return false;
		}
		*/
		function CanAccess($Name, $Action, $Type) {
			foreach($this->Permissions as $Per) {
				if($Type != $Per->Type) continue;

				if($Per->Name === $Name && $Per->Action === $Action && $Per->Access == 1) return true;
			}

			return $this->IsSuperAdmin();
		}

		function IsSuperAdmin() {
			return (bool)($this->SuperAdmin);
		}
	};
?>
