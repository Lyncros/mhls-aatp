<?
	//==========================================================================
	/*
		A debug class for pushing Errors/Notices to Developers

		4/10/2009
	*/
	//==========================================================================

	//==========================================================================
	// Static Class
	//==========================================================================
	class CDebug {
		static private $Timer = 0;
		static private $Counter = 0;

		public static function OnInit() {
			if(defined("DEV")) {
				ini_set("display_errors", "On");
				error_reporting(E_ALL ^ E_NOTICE);

				@set_error_handler(array("CDebug", "OnError"));
			}else{
				error_reporting(0);
			}
		}

		public static function OnError($Number, $Error, $File, $Line) {
			$File = str_replace(dirname(__FILE__)."/", "", $File);
			$Type = CDebug::GetErrorNumberType($Number);

			if($Type == "Notice" || $Type == "Unknown") return;

			if(defined("DEV")) {
				if(self::$Counter < 50) {
					if(mysql_query("INSERT INTO `Debug` VALUES ('', '".$_SERVER["REMOTE_ADDR"]."', ".time().", '".mysql_real_escape_string($Type)."', '".mysql_real_escape_string($File)."',  '".mysql_real_escape_string($Line)."', '".mysql_real_escape_string($Error)."', '".mysql_real_escape_string(serialize($_SERVER))."', '".mysql_real_escape_string(serialize($_SESSION))."', '".mysql_real_escape_string(serialize($_POST))."', '".mysql_real_escape_string(serialize($_GET))."')") === false) {
						echo mysql_error();
					}

					self::$Counter++;

					if(self::$Counter == 50) {
					}
				}
			}
		}

		protected static function GetErrorNumberType($Number) {
			$Type = "";

			switch($Number) {
				case E_NOTICE:
				case E_USER_NOTICE: {
					$Type = "Notice";
					break;
				}

				case E_WARNING:
				case E_USER_WARNING: {
					$Type = "Warning";
					break;
				}

				case E_ERROR:
				case E_USER_ERROR: {
					$Type = "Error";
					break;
				}

				default: {
					$Type = "Unknown";
					break;
				}
			}

			return $Type;
		}

		public static function TimerStart() {
			$mtime = microtime();
			$mtime = explode(" ",$mtime);
			$mtime = $mtime[1] + $mtime[0];

			self::$Timer = $mtime; 
		}

		public static function TimerStop() {
			$mtime = microtime();
			$mtime = explode(" ",$mtime);
			$mtime = $mtime[1] + $mtime[0];
			$endtime = $mtime;

			return ($endtime - self::$Timer);
		}
	};

	//==========================================================================
?>
