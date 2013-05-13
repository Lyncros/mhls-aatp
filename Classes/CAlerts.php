<?
	//==========================================================================
	/*
		Table for Alerts

		8/16/2011 2:53 PM
	*/
	//==========================================================================
	class CAlerts extends CTable {
		function __construct() {
			$this->Table = "Alerts";
		}

		function OnLoad($ID) {
			if(parent::OnLoadByID($ID) === false) {
				return false;
			}

			return true;
		}

		function OnLoadByUsersID($UsersID, $IncludeHidden = false) {
			if(parent::OnLoadAll("WHERE `UsersID` = ".intval($UsersID)." ".($IncludeHidden ? "" : " && `Hidden` = 0")." ORDER BY `Timestamp` DESC") === false) {
				return false;
			}

			return true;
		}

		public static function GetNumUnreadAlerts($UsersID, $IncludeHidden = false) {
			return CTable::NumRows("Alerts", "WHERE `UsersID` = ".intval($UsersID)." && `Read` = 0 ".($IncludeHidden ? "" : " && `Hidden` = 0"));
		}
	};

	//==========================================================================
?>
