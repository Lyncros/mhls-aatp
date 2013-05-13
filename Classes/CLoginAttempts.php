<?
	//==========================================================================
	/*
		Table for LoginAttempts

		4/10/2009
	*/
	//==========================================================================
	class CLoginAttempts extends CTable {
		function __construct() {
			$this->Table = "LoginAttempts";
		}

		function OnLoad($ID) {
			if(parent::OnLoadByID($ID) === false) {
				trigger_error("Unable to load (".$this->Table.") : $ID", E_USER_NOTICE);
				return false;
			}

			return true;
		}

		//----------------------------------------------------------------------
		// Static Functions
		//----------------------------------------------------------------------
		public static function AddAttempt($UsersID, $Username, $Password, $Success, $IP = "") {
			if($IP == "") $IP = $_SERVER["REMOTE_ADDR"];

			$Data = Array(
				"IP"			=> $IP,
				"Timestamp"		=> time(),
				"UsersID"		=> intval($UsersID),
				"Username"		=> $Username,
				"Password"		=> CEncrypt::Encrypt($Password),
				"Success"		=> $Success
			);

			return CTable::Add("LoginAttempts", $Data);
		}
	};

	//==========================================================================
?>
