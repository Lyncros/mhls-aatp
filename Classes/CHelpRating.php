<?
	//==========================================================================
	/*
		Table for HelpRating

		See CHelp.php

		4/10/2009
	*/
	//==========================================================================
	class CHelpRating extends CTable {
		function __construct() {
			$this->Table = "HelpRating";
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
