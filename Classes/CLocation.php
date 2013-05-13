<?
	//==========================================================================
	/*
		Utility Class for Location methods

		3/17/2010 4:04 PM

		To Do:
			- Add Methods for Distance, GeoLocation, Valid Address (UPS offers service),
				Suggested Address
	*/
	//==========================================================================
	class CLocation {
		private static $Mode = CLOCATION_MODE_BUSINESS;

		public static function SetMode($Mode) {
			if($Mode < CLOCATION_MODE_ALL || $Mode >= CLOCATION_MODE_LAST) return false;

			self::$Mode = $Mode;

			return true;
		}

		public static function FindCityByZip($Zip, $Country = "US") {
		}

		public static function FindStateByZip($Zip, $Country = "US") {
		}

		public static function GetCities($State, $Country = "US")  {
		}

		public static function GetStates($Country = "US")  {
		}

		public static function GetCountries($Country = "US") {
		}

		public static function GetSuggestedCurrency($Country) {
		}
	};
?>
