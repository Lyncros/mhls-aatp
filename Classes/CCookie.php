<?
	//==========================================================================
	/*
		Class for managing Cookies

		9/2/2009 9:03 AM
	*/
	//==========================================================================
	class CCookie {
		public static function SetValue($Name, $CookieName, $CookieValue, $Expire = 0) {
			if($Expire <= 0) {
				$Expire = Config::$Options["System"]["Cookie"]["Expire"];
			}

			setcookie($Name."_".$CookieName, $CookieValue, time() + $Expire);
		}

		public static function GetValue($Name, $CookieName) {
			if(!isset($_COOKIE[$Name."_".$CookieName])) return false;

			return $_COOKIE[$Name."_".$CookieName];
		}
	}

	//==========================================================================
?>
