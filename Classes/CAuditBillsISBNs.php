<?
	//==========================================================================
	/*
		Table for AuditBillsISBNs

		6/6/2012 1:00 PM
	*/
	//==========================================================================
	class CAuditBillsISBNs extends CTable {
		function __construct() {
			$this->Table = "AuditBillsISBNs";
		}

		function OnLoad($ID) {
			if(parent::OnLoadByID($ID) === false) {
				return false;
			}

			return true;
		}
	};
?>
