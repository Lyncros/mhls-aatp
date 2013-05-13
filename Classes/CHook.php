<?
	//==========================================================================
	/*
		7/15/2010 2:17 PM
	*/
	//==========================================================================
	class CHook {
		public static $Plugins = Array();

		public static function Register($Callback, $Name) {
			if(is_callable($Callback) === false) return false;

			if(!isset(self::$Plugins[$Name])) self::$Plugins[$Name] = Array();

			self::$Plugins[$Name][] = $Callback;
		}

		public static function Call($Object, $Name) {
			if(!isset(self::$Plugins[$Name])) return false;

			foreach(self::$Plugins[$Name] as $Callback) {
				call_user_func($Callback, $Object);
			}

			return true;
		}
	}
?>
