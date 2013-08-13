<?
	//==========================================================================
	/*
		Class for the ReCAPTCHA library

		http://recaptcha.net/

		8/20/2009 4:01 PM
	*/
	//==========================================================================
	include_once("./Libraries/ReCAPTCHA/recaptchalib.php");

	//==========================================================================
	class CReCAPTCHA {
		private static $PublicKey	= "6LckDOYSAAAAAIhwKzIxJsWkFdzKnodsHhfLwfDh";
		private static $PrivateKey	= "6LckDOYSAAAAAJYriz2TEDRijsnby5P2uPGKTqfz";

		public static function GetHTML() {
			return recaptcha_get_html(self::$PublicKey);
		}
				
		public static function ValidAnswer() {
			$Response = recaptcha_check_answer(self::$PrivateKey, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);

			return $Response->is_valid;
		}
	};

	//==========================================================================
?>
