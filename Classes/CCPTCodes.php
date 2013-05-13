<?
	//==========================================================================
	/*
		Table for CPTCodes

		8/30/2011 6:50 AM
	*/
	//==========================================================================
	class CCPTCodes extends CTable {
		function __construct() {
			$this->Table = "CPTCodes";
		}

		function OnLoad($ID) {
			if(parent::OnLoadByID($ID) === false) {
				return false;
			}

			return true;
		}
	};
?>
