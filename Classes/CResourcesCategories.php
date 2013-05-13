<?
	//==========================================================================
	/*
		Table for ResourcesCategories

		6/29/2012 8:30 AM
	*/
	//==========================================================================
	class CResourcesCategories extends CTable {
		function __construct() {
			$this->Table = "ResourcesCategories";
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
