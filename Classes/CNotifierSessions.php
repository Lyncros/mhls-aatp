<?
	//==========================================================================
	/*
		Table for NotifierSessions

		1/28/2010 7:09 AM
	*/
	//==========================================================================
	class CNotifierSessions extends CTable {
		function __construct() {
			$this->Table = "NotifierSessions";
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
