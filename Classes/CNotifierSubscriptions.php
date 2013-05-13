<?
	//==========================================================================
	/*
		Table for NotifierSubscriptions

		1/28/2010 7:09 AM
	*/
	//==========================================================================
	class CNotifierSubscriptions extends CTable {
		function __construct() {
			$this->Table = "NotifierSubscriptions";
		}

		function OnLoad($ID) {
			if(parent::OnLoadByID($ID) === false) {
				//trigger_error("Unable to load (".$this->Table.") : $ID", E_USER_NOTICE);
				return false;
			}

			return true;
		}

		function OnLoadAllByType($Type, $Name, $SubName, $ProjectsID = 0) {
			$Type		= mysql_real_escape_string($Type);
			$Name		= mysql_real_escape_string($Name);
			$SubName	= mysql_real_escape_string($SubName);

			if(parent::OnLoadAll("WHERE `ProjectsID` = ".$ProjectsID." && `Type` = '".$Type."' && `Name` = '".$Name."' && `SubName` = '".$SubName."'") === false) {
				//trigger_error("Unable to load (".$this->Table.") : $Type, $Name, $SubName, $BusinessesID", E_USER_WARNING);
				return false;
			}

			return true;
		}

		function OnLoadByUsersID($UsersID, $Type, $Name, $SubName, $ProjectsID = 0) {
			$UsersID	= intval($UsersID);
			$Type		= mysql_real_escape_string($Type);
			$Name		= mysql_real_escape_string($Name);
			$SubName	= mysql_real_escape_string($SubName);

			if(parent::OnLoadAll("WHERE `UsersID` = ".$UsersID." && `ProjectsID` = ".$ProjectsID." && `Type` = '".$Type."' && `Name` = '".$Name."' && `SubName` = '".$SubName."'") === false) {
				//trigger_error("Unable to load (".$this->Table.") : $ID", E_USER_NOTICE);
				return false;
			}

			return true;
		}
	};

	//==========================================================================
?>
