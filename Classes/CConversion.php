<?
	class CConversion {
		public static function MinutesToUnits($Minutes) {
			$Minutes = doubleval($Minutes);

			return ($Minutes / 15);
		}
	}
?>
