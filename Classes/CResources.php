<?
	//==========================================================================
	/*
		Table for Resources

		8/18/2011 9:25 AM
	*/
	//=========================================================================
	class CResources extends CTable {
		function __construct() {
			$this->Table = "Resources";
		}

		function OnLoad($ID) {
			if(parent::OnLoadByID($ID) === false) {
				return false;
			}

			return true;
		}
	};
?>
