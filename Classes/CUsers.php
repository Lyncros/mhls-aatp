<?
	//==========================================================================
	/*
		Table for Users

		SuperAdmin overrides all Permissions

		4/10/2009
	*/
	//==========================================================================
	class CUsers extends CTable {
		public $Group		= null;
		public $Preferences	= Array();

		function __construct() {
			$this->Table = "Users";
		}

		function OnLoad($ID) {
			if(parent::OnLoadByID($ID) === false) {
				//trigger_error("Unable to load (".$this->Table.") : $ID", E_USER_NOTICE);
				return false;
			}

			$this->Group = new CUsersGroups();
			if($this->Group->OnLoad($this->UsersGroupsID) === false) {
				$this->Group = null;
			}

			return true;
		}
		
		function GetName() {
			if(strlen($this->FirstName) > 0 || strlen($this->LastName) > 0) {
				return $this->FirstName." ".$this->LastName;
			}

			return "Unknown";
		}

		function GetInitials() {
			return strtoupper($this->FirstName[0].$this->LastName[0]);
		}

		function GetPassword() {
			return CEncrypt::Decrypt($this->Password);
		}

		function IsSuperAdmin() {
			if($this->Group == null) return false;

			return ($this->Group->SuperAdmin == 1);
		}

		function CanAccess($Name, $Action = "", $Type = "Module") {
			if($this->Group && $this->Group->CanAccess($Name, $Action, $Type)) {
				return true;
			}

			return ($this->IsSuperAdmin());
		}

		//======================================================================
		// Static functions
		//======================================================================
		function ValidLogin($Username, $Password) {
			$SUsername = mysql_real_escape_string($Username);
			$SPassword = mysql_real_escape_string(CEncrypt::Encrypt($Password));

			$Rows = CTable::OnLoadByQuery("SELECT * FROM `Users` WHERE `Username` = '$SUsername' && `Password` = '$SPassword' && `Active` = 1");

			if($Rows === false) {
				CLoginAttempts::AddAttempt(0, $Username, $Password, 0);
				return false;
			}

			CLoginAttempts::AddAttempt($Rows->ID, $Username, $Password, 1);

			return $Rows->ID;
		}
        
		// Shouldn't be here
        function IsValidEmail($Email) {
			return CValidate::Email($Email);
        }
        
		// Shouldn't be here
        function IsStrongPassword($Password){
            
            $isStrong = false;
            $minPasswordLength = Config::$Options['MyAccount']['MinPasswordLength'];
            
            if(count($Password) <= 3 || count($Password) <= $minPasswordLength){
                $isStrong = false;
            }
            
            if(preg_match('/(?=^.{'.$minPasswordLength.',}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/', $Password))
                $isStrong = true;
            else
                $isStrong = false;
                         
            return $isStrong;             
        }
        
        function AccountExists($Username) {
			$Username = mysql_real_escape_string($Username);

            return (CTable::NumRows("Users", "WHERE `Username` = '$Username'") > 0);
            
        }

		public static function StartPasswordReset($ID) {
			$UserClass = new CUsers();
			if($UserClass->OnLoadByID($ID) === false) return false;

			$PassCode = CUsers::GeneratePassCode();

			$Data = Array(
				"ResetTimestamp"	=> time(),
				"ResetPassCode"		=> CEncrypt::Encrypt($PassCode)
			);

			CTable::Update("Users", $ID, $Data);

			return $PassCode;
		}

		public static function GeneratePassCode() {
			return substr(md5(microtime()), 0, 24);
		}

		public static function ValidResetPassCode($PassCode) {
			$PassCode = mysql_real_escape_string(CEncrypt::Encrypt($PassCode));

			$ExpireTimestamp = time() - (60 * 30); // 30 minutes

            return (CTable::NumRows("Users", "WHERE `ResetPassCode` = '$PassCode' && `ResetTimestamp` > ".$ExpireTimestamp) > 0);
		}

		public static function GetUserByResetPassCode($PassCode) {
			if(self::ValidResetPassCode($PassCode) == false) return false;

			$PassCode = mysql_real_escape_string(CEncrypt::Encrypt($PassCode));

			$UserClass = new CUsers();
			if($UserClass->OnLoadAll("WHERE `ResetPassCode` = '$PassCode'") === false) {
				return false;
			}

			return $UserClass;
		}
		
		public static function GetAllAssignableToMilestone() {
			$UsersGroups = CTable::Select("UsersGroups", "WHERE `AssignableToMilestone` = 1");
			
			return CUsers::GetAllUsersOrderedByLastName($UsersGroups);
		}
		
		public static function GetAllAssignableToTodos() {
			$UsersGroups = CTable::Select("UsersGroups", "WHERE `AssignableToTODO` = 1");
			
			return CUsers::GetAllUsersOrderedByLastName($UsersGroups);
		}
		
		public static function GetAllAssignableToMilestoneTodos() {
			$UsersGroups = CTable::Select("UsersGroups", "WHERE `AssignableToMilestoneTODO` = 1");

			return CUsers::GetAllUsersOrderedByLastName($UsersGroups);			
		}
		
		public static function GetAllUsersOrderedByLastName($UsersGroups) {
			return CTable::Select("Users","WHERE `UsersGroupsID` IN (".implode(",", $UsersGroups->RowsToArray("ID")).") && `Active` = 1 ORDER BY `LastName`");
		}
	};
?>
