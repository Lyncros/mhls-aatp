<?
	//==========================================================================
	/*
		5/6/2010 9:22 AM
	*/
	//==========================================================================
	class CSystem {
		private static $CurrentOrdersID		= 0;
		private static $CurrentQuotesID		= 0;
		private static $CurrentStoresID		= 0;
		private static $CurrentBlogsID		= 0;
		private static $CurrentTemplateName	= "";

		public static function GetBusinessesObject() {
			$BusinessesID = CSecurity::GetBusinessesID();

			if($BusinessesID == 0) return false;

			$BusClass = new CBusinesses();
			if($BusClass->OnLoad($BusinessesID) == false) {
				return false;
			}

			return $BusClass;
		}

		public static function BusinessIsNormal() {
			if(($BusClass = self::GetBusinessesObject()) === false) return false;

			return $BusClass->IsNormal();
		}

		public static function BusinessIsPromo() {
			if(($BusClass = self::GetBusinessesObject()) === false) return false;

			return $BusClass->IsPromo();
		}

		public static function BusinessIsActive() {
			if(($BusClass = self::GetBusinessesObject()) === false) return true;

			return $BusClass->Active;
		}

		public static function GetCurrentBlog($ReturnObject = false) {
			if($ReturnObject) {
				$Blog = new CBlogs();
				if($Blog->OnLoad(self::$CurrentBlogsID) == false) {
					return self::$CurrentBlogsID;
				}

				return $Blog;
			}

			return self::$CurrentBlogsID;
		}

		public static function GetCurrentOrder($ReturnObject = false) {
			if($ReturnObject) {
				$Order = new COrders();
				if($Order->OnLoad(self::$CurrentOrdersID) == false) {
					return self::$CurrentOrdersID;
				}

				return $Order;
			}

			return self::$CurrentOrdersID;
		}

		public static function GetCurrentQuote($ReturnObject = false) {
			if($ReturnObject) {
				$Quote = new CQuotes();
				if($Quote->OnLoad(self::$CurrentQuotesID) == false) {
					return self::$CurrentQuotesID;
				}

				return $Quote;
			}

			return self::$CurrentQuotesID;
		}

		public static function GetCurrentStore($ReturnObject = false) {
			if($ReturnObject) {
				$Store = new CStores();
				if($Store->OnLoad(self::$CurrentStoresID) == false) {
					return self::$CurrentStoresID;
				}

				return $Store;
			}

			return self::$CurrentStoresID;
		}

		public static function GetCurrentTemplate($ReturnObject = false) {
			return self::$CurrentTemplateName;
		}

		public static function SetCurrentBlog($ID) {
			self::$CurrentBlogsID = $ID;
		}

		public static function SetCurrentOrder($ID) {
			self::$CurrentOrdersID = $ID;
		}

		public static function SetCurrentQuote($ID) {
			self::$CurrentQuotesID = $ID;
		}

		public static function SetCurrentStore($ID) {
			self::$CurrentStoresID = $ID;
		}

		public static function SetCurrentTemplate($Name) {
			self::$CurrentTemplateName = $Name;
		}

		public static function GetAllNotifications() {
			$Files = scandir("./", 1);

			array_pop($Files);
			array_pop($Files);

			$Notifications = Array();

			sort($Files);

			foreach($Files as $File) {
				if(stripos($File, ".php") === false || is_dir("./".$File)) continue;

				$NumMatches = preg_match_all("/\[Notifications\](.*)\[\-\]/is", file_get_contents("./".$File), $Matches);

				if($NumMatches !== false && $NumMatches > 0) {
					$Parts = explode("\n", $Matches[1][0]);

					foreach($Parts as $Part) {
						$Part = trim($Part);
						$Part = str_replace("\r", "", $Part);

						if(strlen($Part) <= 0) continue;

						$Notifications["System"][] = $Part;
					}
				}
			}

			return $Notifications;
		}

		public static function GetValue($Name) {
			$Name = mysql_real_escape_string($Name);

			$SysTable = new CTable("System");
			if($SysTable->OnLoadAll("WHERE `Name` = '".$Name."'") === false) {
				return false;
			}

			return $SysTable->Value;
		}

		public static function SetValue($Name, $Value) {
			$Name = mysql_real_escape_string($Name);

			$Data = Array(
				"Name"	=> $Name,
				"Value"	=> $Value
			);

			$SysTable = new CTable("System");
			if($SysTable->OnLoadAll("WHERE `Name` = '".$Name."'") === false) {
				CTable::Add("System", $Data);
			}else{
				CTable::Update("System", $SysTable->ID, $Data);
			}

			return true;
		}
	}

	//==========================================================================
?>
