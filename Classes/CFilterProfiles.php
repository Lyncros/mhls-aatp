<?
	//==========================================================================
	/*
		Table for Filter Profiles

		7/31/2012 9:00 AM
	*/
	//==========================================================================
	class CFilterProfiles extends CTable {
		function __construct() {
			$this->Table = "FilterProfiles";
		}

		public static function OnCron() {
			
		}

		function OnLoad($ID) {
			if(parent::OnLoadByID($ID) === false) {
				return false;
			}

			return true;
		}

		function OnInit() {
			
		}
		
		function LoadByName($Name) {
			$Row = parent::OnLoadAll("WHERE `Name` LIKE '$Name'");
			if($Row === false) {
				return false;
			}

			return $Row->Current;
		}
	};
?>
