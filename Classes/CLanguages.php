<?
	//==========================================================================
	/*
		Table for Languages

		3/18/2010
	*/
	//==========================================================================
	class CLanguages extends CTable {
		function __construct() {
			$this->Table = "Languages";
		}
		
		function OnLoad($ID) {
			if(parent::OnLoadByID($ID) === false) {
				trigger_error("Unable to load (".$this->Table.") : $ID", E_USER_NOTICE);
				return false;
			}

			return true;
		}

		//======================================================================
		// Static functions
		//======================================================================
		
	};
?>
