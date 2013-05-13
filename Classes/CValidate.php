<?
	/*
		All Validate methods return true if good, false if bad
	*/
	class CValidate {
		public static function Email($Email) {
			return (preg_match('/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i', $Email) > 0);
		}
	}
?>
