<?
	//==========================================================================
	/*
		Table for Institutions

		3/29/2012 8:40 AM
	*/
	//==========================================================================
	class CInstitutions extends CTable {
		function __construct() {
			$this->Table = "Institutions";
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
	};
?>
