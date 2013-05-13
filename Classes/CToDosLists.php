<?
	//==========================================================================
	/*
		Table for To Dos Lists

		10/30/2012 8:54 AM
	*/
	//==========================================================================
	class CToDosLists extends CTable {
		function __construct() {
			$this->Table = "ToDosLists";
		}

		//----------------------------------------------------------------------
		public static function OnCron() {
			
		}

		//----------------------------------------------------------------------
		function OnLoad($ID) {
			if(parent::OnLoadByID($ID) === false) {
				return false;
			}

			return true;
		}

		//----------------------------------------------------------------------
		function OnInit() {
			
		}
		
		//----------------------------------------------------------------------
		function GetMembersArray() {
			return unserialize($this->Members);
		}
		
		/***********************************************************************
		 *	Static Functions
		 **********************************************************************/
		public static function GetStatusList() {
			return Array(
				"Active"			=> "Active",
				"Complete"			=> "Complete",
			);
		}
	};
?>
