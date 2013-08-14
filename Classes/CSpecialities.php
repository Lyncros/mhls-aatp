<?
	//==========================================================================
	/*
		Table for Projects Tags
	*/
	//==========================================================================
	class CSpecialities extends CTable {
		function __construct() {
			$this->Table = "Specialities";
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
