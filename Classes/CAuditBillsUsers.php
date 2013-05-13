<?
	//==========================================================================
	/*
		Table for AuditBillsUsers

		4/13/2012 8:43 AM
	*/
	//==========================================================================
	class CAuditBillsUsers extends CTable {
		function __construct() {
			$this->Table = "AuditBillsUsers";
		}

		function OnLoad($ID) {
			if(parent::OnLoadByID($ID) === false) {
				return false;
			}

			return true;
		}
	};
?>
