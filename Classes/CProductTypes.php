<?
	//==========================================================================
	/*
		Table for Product Types

		7/26/2012 9:00 AM
	*/
	//==========================================================================
	class CProductTypes extends CTable {
		function __construct() {
			$this->Table = "ProductTypes";
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
