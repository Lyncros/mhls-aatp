<?
	//==========================================================================
	/*
		Table for AuditBillsProductSolutions

		4/13/2012 8:43 AM
	*/
	//==========================================================================
	class CAuditBillsProductSolutions extends CTable {
		function __construct() {
			$this->Table = "AuditBillsProductSolutions";
		}

		function OnLoad($ID) {
			if(parent::OnLoadByID($ID) === false) {
				return false;
			}

			return true;
		}
	};
?>
