<?
	//==========================================================================
	/*
		Class to provide authentication and security for Modules/Plugins

		4/10/2009
	*/
	//=========================================================================
	class CSecurity {
		static public $User				= null;
		static public $SessionControl	= null;

		function __construct() {			
		}

		public static function &GetSessionControl() {
			if(self::$SessionControl == null) {
				self::$SessionControl = new CSession("CSecurity");
			}

			return self::$SessionControl;
		}

		public static function OnInit() {
			if(!isset(self::GetSessionControl()->UsersID)) {
				self::GetSessionControl()->UsersID = 0;
			}

			$UsersID = self::GetSessionControl()->UsersID;

			if($UsersID > 0) {
				self::$User = new CUsers();
				if(self::$User->OnLoad($UsersID) === false) {
					self::Logout();
				}
			}

			return true;
		}

		public static function Login($UsersID) {
			self::GetSessionControl()->UsersID = intval($UsersID);
		}

		public static function Logout() {
			unset(self::GetSessionControl()->UsersID);
		}

		public static function IsLoggedIn() {
			if(isset(self::GetSessionControl()->UsersID) && self::GetSessionControl()->UsersID > 0) {
				return true;
			}

			return false;
		}

		public static function GetUsersID() {
			if(self::GetSessionControl()->UsersID > 0) {
				return self::GetSessionControl()->UsersID;
			}

			return 0;
		}
		
		public static function GetUsersGroupsID() {
			if(self::$User == null || self::$User->Group == null) return 0;
			
			return self::$User->Group->ID;
		}

		public static function CanAccess($Name, $Action = "", $Type = "Module") {
			if($Name === "Login") return true;
            
			if(self::IsLoggedIn() && ($Name == "Dashboard" || $Name == "Terms")) {
				return true;
			}

			if(self::IsLoggedIn() == false || self::$User == null) return false;

			if(strlen($Action) <= 0) {
				$Action = "Access";
			}

			return self::$User->CanAccess($Name, $Action, $Type);
		}

		public static function IsAdmin($Name, $Type = "Module") {
			if(self::IsLoggedIn() == false || self::$User == null || self::$User->Group == null) return false;

			return self::$User->Group->CanAccess($Name, "Admin", $Type);
		}

		public static function IsSuperAdmin() {
			if(self::IsLoggedIn() == false || self::$User == null) return false;

			return self::$User->IsSuperAdmin();
		}

		public static function CanAccessRecord($ID, $Table) {
			if(self::IsSuperAdmin())	return true;
			if($ID <= 0)				return true;

			$Item = new CTable($Table);
			if($Item->OnLoadByID($ID) === false) {
				return false;
			}

			if($Item->UsersID == self::GetUsersID()) return true;

			return false;
		}
	};
?>
