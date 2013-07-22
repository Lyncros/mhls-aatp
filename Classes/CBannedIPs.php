<?
	//==========================================================================
	/*
		Table for BannedIPs

		4/10/2009
	*/
	//==========================================================================
	class CBannedIPs extends CTable {
		function __construct() {
			$this->Table = "BannedIPs";
		}

		/**
		 *	Load one bannedIP record
		 *	
		 *	@param integer $ID
		 *	@return boolean
		 */
		function OnLoad($ID) {
			if(parent::OnLoadByID($ID) === false) {
				trigger_error("Unable to load (".$this->Table.") : $ID", E_USER_NOTICE);
				return false;
			}

			return true;
		}

		//======================================================================
		// Static functions
		//======================================================================
		/**
		 *	Detect if an IP address is banned
		 *	
		 *	@static
		 *	@param string $IP - optional, defaults to $_SERVER["REMOTE_ADDR"]
		 *	@return boolean | integer
		 */
		public static function IsBanned($IP = "") {
			if($IP == "") $IP = $_SERVER["REMOTE_ADDR"];

			//Never trust the other person
			$IP = mysql_real_escape_string($IP);

			$BanTable = new CBannedIPs();
			if($BanTable->OnLoadAll("WHERE `IP` = '$IP'") === false) {
				return false;
			}

			if($BanTable->Rows->ExpireMinutes > 0 && ($BanTable->Rows->Timestamp + ($BanTable->Rows->ExpireMinutes * 60)) < time()) {
				CBannedIPs::Unban($IP);
				return false;
			}

			return $BanTable->Rows;
		}

		/**
		 *	Ban an ip address
		 *	
		 *	@static
		 *	@param string $IP - optional, defaults to $_SERVER["REMOTE_ADDR"]
		 *	@param double $ExpireMinutes - optional, defaults to 0, which represents FOREVER
		 *	@param string $Reason - optional
		 *	@return null
		 */
		public static function Ban($IP = "", $ExpireMinutes = 0, $Reason = "") {
			if($IP == "") $IP = $_SERVER["REMOTE_ADDR"];

			if(CBannedIPs::IsBanned($IP)) return;

			$Data = Array(
				"IP"			=> mysql_real_escape_string($IP),
				"Timestamp"		=> time(),
				"ExpireMinutes"	=> doubleval($ExpireMinutes),
				"Reason"		=> mysql_real_escape_string($Reason)
			);

			CTable::Add("BannedIPs", $Data);
		}

		/**
		 *	Remove an ip from the banned list
		 *	
		 *	@static
		 *	@param string $IP - The IP to unban
		 *	@return boolean
		 */
		public static function Unban($IP) {
			if(strlen($IP) <= 0) return false;

			$IP = mysql_real_escape_string($IP);

			$BanTable = new CBannedIPs();
			if($BanTable->OnLoadAll("WHERE `IP` = '$IP'") === false) {
				return false;
			}

			CTable::Delete("BannedIPs", intval($BanTable->Rows->ID));

			return true;
		}
	};
?>
