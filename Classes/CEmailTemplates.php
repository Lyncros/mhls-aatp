<?
	//==========================================================================
	/*
		Table for EmailTemplates

		4/10/2009
	*/
	//==========================================================================
	class CEmailTemplates extends CTable {
		function __construct() {
			$this->Table = "EmailTemplates";
		}

		function OnLoad($ID) {
			if(parent::OnLoadByID($ID) === false) {
				trigger_error("Unable to load (".$this->Table.") : $ID", E_USER_NOTICE);
				return false;
			}

			return true;
		}

		function OnLoadByName($Type, $Name, $SubName) {
			$Type		= mysql_real_escape_string($Type);
			$Name		= mysql_real_escape_string($Name);
			$SubName	= mysql_real_escape_string($SubName);

			if(parent::OnLoadAll("WHERE `Type` = '$Type' && `Name` = '$Name' && `SubName` = '$SubName'") === false) {
				$AttContent = "\nType: $Type, Name: $Name, SubName: $SubName\nPublic Data Set: ".implode(", ", array_keys(CDataParser::GetPublicData()));

				//CAttention::PushNotice(CSecurity::GetBusinessesID(), "EmailTemplates", 0, Config::$Options["Attention"]["EmailTemplates"].$AttContent);

				return false;
			}

			return true;
		}

		//----------------------------------------------------------------------
		// Static Functions
		//----------------------------------------------------------------------
		public static function GetTypeList() {
			return Array(
				"Module"	=> "Module",
				"Plugin"	=> "Plugin",
				"Template"	=> "Template"
			);
		}
	};

	//==========================================================================
?>
