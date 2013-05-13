<?
	//==========================================================================
	/*
		Table for NotifierSMSQueue

		1/28/2010 7:09 AM
	*/
	//==========================================================================
	class CNotifierSMSQueue extends CTable {
		function __construct() {
			$this->Table = "NotifierSMSQueue";
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
