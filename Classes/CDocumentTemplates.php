<?
	//==========================================================================
	/*
		Table for DocumentTemplates

		2/3/2010 7:17 AM
	*/
	//==========================================================================
	class CDocumentTemplates extends CTable {
		function __construct() {
			$this->Table = "DocumentTemplates";
		}

		function OnLoad($ID) {
			if(parent::OnLoadByID($ID) === false) {
				trigger_error("Unable to load (".$this->Table.") : $ID", E_USER_NOTICE);
				return false;
			}

			return true;
		}

		function OnLoadByName($Type, $Name, $SubName, $BusinessesID = 0) {
			$Type		= mysql_real_escape_string($Type);
			$Name		= mysql_real_escape_string($Name);
			$SubName	= mysql_real_escape_string($SubName);

			$BusinessesID = intval($BusinessesID);

			if($BusinessesID <= 0) {
				$BusinessesID = CSecurity::GetBusinessesID();
			}

			if(parent::OnLoadAll("WHERE `Type` = '$Type' && `Name` = '$Name' && `SubName` = '$SubName' && `BusinessesID` = $BusinessesID") === false && 
				parent::OnLoadAll("WHERE `Type` = '$Type' && `Name` = '$Name' && `SubName` = '$SubName' && `Public` = 1") === false) {
				$AttContent = "\nType: $Type, Name: $Name, SubName: $SubName\nPublic Data Set: ".implode(", ", array_keys(CDataParser::GetPublicData()));

				CAttention::PushNotice(CSecurity::GetBusinessesID(), "DocumentTemplates", 0, Config::$Options["Attention"]["DocumentTemplates"].$AttContent);

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
