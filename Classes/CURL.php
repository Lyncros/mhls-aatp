<?
	//==========================================================================
	/*
		Class for formatting and manipulating URLs

		9/16/2009 2:21 PM
	*/
	//==========================================================================
	class CURL {

		//======================================================================
		// Static functions
		//======================================================================
		public static function GetDomain($SubDomain = true) {
			if(defined("YPPDEV")) {
				if(isset($_SESSION["DevURL"])) {
					return $_SESSION["DevURL"];
				}
			}

			if($SubDomain) {
				return $_SERVER["SERVER_NAME"];
			}

			$URL = $_SERVER["SERVER_NAME"];
			$URL = explode(".", $URL, 2);

			if(count($URL) <= 2) return $_SERVER["SERVER_NAME"];

			return $URL[1];
		}

		//----------------------------------------------------------------------
		public static function GetSubDomain() {
			$URL = $_SERVER["SERVER_NAME"];
			$URL = explode(".", $URL);

			return $URL[0];
		}

		public static function HasSubDomain() {
			$Domain = CURL::GetDomain(true);

			$URL = $_SERVER["SERVER_NAME"];
			$URL = explode(".", $URL);

			return (count($URL) > 2);
		}

		//----------------------------------------------------------------------
		public static function GetShortDomain() {
			$URL = self::GetDomain(false);

			$URL = str_replace("http:", "", $URL);
			$URL = str_replace("https:", "", $URL);
			$URL = str_replace("/", "", $URL);

			$Parts = explode(".", $URL);

			return $Parts[count($Parts) - 2].".".$Parts[count($Parts) - 1];
		}

		//----------------------------------------------------------------------
		public static function GetBasePage($ReturnRelative = true) {
			$Page = $_SERVER["REQUEST_URI"];
			$Page = explode("/", $Page);
			$Page = end($Page);
			$Page = explode("?", $Page);
			$Page = $Page[0];

			if($ReturnRelative && strlen($Page) <= 0) {
				$Page = "./";
			}

			return $Page;
		}

		//----------------------------------------------------------------------
		public static function GetPageMods() {
			$Mods = explode("/", $_SERVER["REQUEST_URI"]);

			if(count($Mods) <= 2) {
				return Array();
			}

			array_pop($Mods);
			$Mods = array_reverse($Mods);
			array_pop($Mods);
			$Mods = array_reverse($Mods);

			return $Mods;
		}

		//----------------------------------------------------------------------
		public static function HasPageMod($Name) {
			return in_array($Name, self::GetPageMods());
		}

		//----------------------------------------------------------------------
		public static function GetCurrentParms() {
			$Return = "?";

			foreach($_GET as $Key => $Value) {
				$Return .= $Key."=".urlencode($Value)."&";
			}

			return $Return;
		}

		//----------------------------------------------------------------------
		public static function FormatURL($URL, $Parms, $UseGetParms = true, $UseBasePage = false) {
			$CurrentParms = "?";

			if($UseGetParms) {
				foreach($_GET as $Key => $Value) {
					$Skip = false;

					foreach($Parms as $Key2 => $Value2) {
						if($Key == $Key2) $Skip = true;
					}

					if($Skip) continue;

					$CurrentParms .= $Key."=".urlencode($Value)."&";
				}
			}

			if(count($Parms) > 0) {
				foreach($Parms as $Key => $Value) {
					$CurrentParms .= $Key."=".urlencode($Value)."&";
				}
			}

			if(strlen($URL) <= 0 || $URL == "") {
				$URL = explode("/", $_SERVER["REQUEST_URI"]);
				$URL = end($URL);

				$URL = explode("?", $URL);
				$URL = $URL[0];
			}

			if($CurrentParms == "?") $CurrentParms = "";

			$URL = @Config::$Options["System"]["BaseURL"].$URL;

			if(strpos($URL, "http") === false) {
				$URL = str_replace("//", "/", $URL);
			}

			if($UseBasePage) {
				$URL = $URL."/".CURL::GetBasePage();
			}

			$URL = str_replace("/./", "/", $URL);

			return $URL.$CurrentParms;
		}

		//----------------------------------------------------------------------
		public static function GetURL() {
			return "http".($_SERVER["SERVER_PORT"] == 443 ? "s" : "")."://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
		}

		//----------------------------------------------------------------------
		public static function Redirect($URL, $Type = "JS") {
			if($Type == "JS") {
				echo "<script language='Javascript' type='text/javascript'>document.location.href = '$URL';</script>";
			}else{
				Header("Location: $URL");
			}
		}

		//----------------------------------------------------------------------
		public static function GoBack() {
			echo "<script language='Javascript' type='text/javascript'>history.back(-1);</script>";
		}

		//======================================================================
		public static function IsMobile() {
			$MobileBrowsers = Array(
				CURL_BROWSER_TYPE_IPHONE,
				CURL_BROWSER_TYPE_ANDROID
			);

			list($Type, $Version) = self::GetBrowserType();

			return in_array($Type, $MobileBrowsers, false);
		}

		//----------------------------------------------------------------------
		public static function GetBrowserType() {
			$Type = CURL_BROWSER_TYPE_UNKNOWN;

			if(stripos($_SERVER['HTTP_USER_AGENT'], "MSIE") !== false) {
				$Type = CURL_BROWSER_TYPE_IE;
			}else
			if(stripos($_SERVER['HTTP_USER_AGENT'], "Firefox") !== false) {
				$Type = CURL_BROWSER_TYPE_FIREFOX;
			}else
			if(stripos($_SERVER['HTTP_USER_AGENT'], "iPhone") !== false) {
				$Type = CURL_BROWSER_TYPE_IPHONE;
			}else
			if(stripos($_SERVER['HTTP_USER_AGENT'], "Android") !== false) {
				$Type = CURL_BROWSER_TYPE_ANDROID;
			}

			return Array($Type, 1);
		}
	};
?>
