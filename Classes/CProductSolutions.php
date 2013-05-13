<?
	//==========================================================================
	/*
		Table for ProductSolutions

		3/29/2012 8:40 AM
	*/
	//==========================================================================
	class CProductSolutions extends CTable {
		function __construct() {
			$this->Table = "ProductSolutions";
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
