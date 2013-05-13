<?
	//==========================================================================
	/*
		Table for ReportsOptions

		10/24/2011 6:47 AM
	*/
	//==========================================================================
	class CReportsOptions extends CTable {
		public $Cells = Array();

		function __construct() {
			$this->Table = "ReportsOptions";
		}

		function OnLoad($ID) {
			if(parent::OnLoadByID($ID) === false) {
				trigger_error("Unable to load (".$this->Table.") : $ID", E_USER_NOTICE);
				return false;
			}

			return true;
		}
	};

	//==========================================================================
?>
