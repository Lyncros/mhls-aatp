<?
	//==========================================================================
	/*
		Table for UsersGroupsPermissions

		8/9/2011 7:29 AM
	*/
	//==========================================================================
	class CUsersGroupsPermissions extends CTable {
		function __construct() {
			$this->Table = "UsersGroupsPermissions";
		}

		function OnLoad($ID) {
			if(parent::OnLoadByID($ID) === false) {
				//trigger_error("Unable to load (".$this->Table.") : $ID", E_USER_NOTICE);
				return false;
			}

			return true;
		}
	};
?>
