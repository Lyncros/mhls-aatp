<?
	//==========================================================================
	/*
		Table for Frequencies

		3/29/2012 8:40 AM
	*/
	//==========================================================================
	class CFrequencies extends CTable {
		function __construct() {
			$this->Table = "Frequencies";
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
