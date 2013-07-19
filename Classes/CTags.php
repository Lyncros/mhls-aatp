<?
	//==========================================================================
	/*
		Table for Projects Tags
	*/
	//==========================================================================
	class CTags extends CTable {
		function __construct() {
			$this->Table = "Tags";
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
