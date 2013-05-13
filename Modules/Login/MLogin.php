<?
	//==========================================================================
	class MLogin extends CModule {
		public $PageNotice		= "";
		public $SessionControl	= null;

		function __construct() {
			parent::__construct();

			$this->SessionControl = new CSession("MLogin");

			if(!isset($this->SessionControl->LoginAttempts)) {
				$this->SessionControl->LoginAttempts = 0;
			}
		}

		//----------------------------------------------------------------------
		function OnAJAX($Action) {
			if($Action == "IsLoggedIn") {
				return $this->IsLoggedIn();
			}else
			if($Action == "Logout") {
				return $this->Logout();
			}

			return Array(1, "");
		}

		//----------------------------------------------------------------------
		function OnExecute() {
			if($this->SessionControl->LoginAttempts >= Config::$Options["Login"]["MaxAttempts"]) {
				CBannedIPs::Ban("", Config::$Options["Login"]["BanMinutes"], "You have reached the maximum number of attempts to login.");

				$this->SessionControl->LoginAttempts = 0;

				$this->Parent->LoadModule("Login");
				return;
			}

			$this->ThemeControl->SetTheme("Login");

			$Action = @$_GET["Action"];

			if($Action === "Login") {
				$this->Login();
			}else
			if($Action === "Logout") {
				$this->Logout();
			}else
			if($Action === "LostPassword") {
				$this->LostPassword();
			}else
			if($Action === "ResetPassword") {
				$this->ResetPassword();
			}else{
				$_POST["Username"] = CCookie::GetValue("MLogin", "Username");
			}

			$PassCode = (isset($_GET["PassCode"]) ? $_GET["PassCode"] : $_POST["PassCode"]);

			if(isset($_GET["Reset"]) && CUsers::ValidResetPassCode($PassCode) == false && !isset($_GET["Success"])) {
				CApp::GetInstance()->LoadModule("Login");
				return;
			}

			if(CSecurity::IsLoggedIn()) {
				$this->Parent->LoadModule("Dashboard");
			}
		}

		//----------------------------------------------------------------------
		function OnRender() {
			$this->ThemeControl->FileControl->LoadFile("header.php");

			if(isset($_GET["CheckInbox"])) {
				$this->FileControl->LoadFile("CheckInbox.php");
			}else
			if(isset($_GET["Reset"])) {
				if(isset($_GET["Success"])) {
					$this->FileControl->LoadFile("Success.php");
				}else{
					$this->FileControl->LoadFile("Reset.php");
				}
			}else{
				$this->FileControl->LoadFile("Render.php");
			}

			$this->ThemeControl->FileControl->LoadFile("footer.php");
		}

		//======================================================================
		function IsLoggedIn() {
			if(CSecurity::IsLoggedIn()) {
				return Array(1, 1);
			}

			return Array(1, 0);
		}

		//======================================================================
		function Login() {
			$Username = @$_POST["Username"];
			$Password = @$_POST["Password"];

			if(!$Username) $Username = "";
			if(!$Password) $Password = "";

			if(($ID = CUsers::ValidLogin($Username, $Password)) === false) {
				$this->SessionControl->LoginAttempts = $this->SessionControl->LoginAttempts + 1;

				$this->PageNotice = "Unable to login, please try again";
				return false;
			}			

			$this->SessionControl->LoginAttempts = 0;

			$this->PageNotice = "You have been logged in, please wait while you are redirected ";

			if($_POST["RememberMe"]) {
				CCookie::SetValue("MLogin", "Username", $Username);
			}else{
				CCookie::SetValue("MLogin", "Username", "");
			}

			CSecurity::Login($ID);
			CSecurity::OnInit();

			if(CSystem::GetValue("SystemLocked") == 1 && CSecurity::IsAdmin() == false) {
				$this->PageNotice = "This system has been locked for Maintenance.";
				CSecurity::Logout();
				return;
			}

			$this->SessionControl->SetSection("CSystem");

			if(strlen($this->SessionControl->DestinationURL) > 0) {
				CURL::Redirect($this->SessionControl->DestinationURL);
			}else{
				$this->Parent->LoadModule("Dashboard");
			}

			if($_POST["KeepSessionAlive"]) {
				$this->SessionControl->KeepSessionAlive = 1;
			}else{
				$this->SessionControl->KeepSessionAlive = 0;
			}

			CCookie::SetValue("MLogin", "KeepSessionAlive", $this->SessionControl->KeepSessionAlive);

			unset($this->SessionControl->DestinationURL);

			return true;
		}

		//----------------------------------------------------------------------
		function Logout() {
			CSecurity::Logout();

			$this->Parent->LoadModule("Login");
		}

		//----------------------------------------------------------------------
		function LostPassword() {
			$Username = mysql_real_escape_string($_POST["Username"]);

			$User = new CUsers();
			$UserReturn = $User->OnLoadAll("WHERE `Username` = '$Username'");

			$Data = Array(
				"UsersID"		=> ($UserReturn !== false ? $User->ID : 0),
				"Timestamp"		=> time(),
				"IP"			=> $_SERVER["REMOTE_ADDR"],
				"Username"		=> $Username,
				"PassCode"		=> "",
				"Success"		=> 0
			);
	
			if($UserReturn === false) {
				$this->PageNotice = "The username entered cannot be found";
			}else
			if(($PassCode = CUsers::StartPasswordReset($User->ID)) === false) {
				$this->PageNotice = "There was a problem with your request, please try again.";
			}else{
				$this->PageNotice = "Please check your email inbox for instructions";

				$Data["PassCode"]	= CEncrypt::Encrypt($PassCode);
				$Data["Success"]	= 1;

				CNotifier::PushEmail($User->Email, "Module", "Login", "Lost Password", Array("Username" => $User->Username, "PassCode" => $PassCode)); //Push to Person Registering

				CApp::LoadModule("Login", Array("CheckInbox" => "1"), false, false);
			}

			CTable::Add("ResetPasswordRequests", $Data);

			return true;
		}

		//----------------------------------------------------------------------
		function ResetPassword() {
			$PassCode		= $_POST["PassCode"];

			$NewPassword1	= $_POST["Password1"];
			$NewPassword2	= $_POST["Password2"];

			$User = CUsers::GetUserByResetPassCode($PassCode);

			$Data = Array(
				"UsersID"		=> ($User !== false ? $User->ID : 0),
				"Timestamp"		=> time(),
				"IP"			=> $_SERVER["REMOTE_ADDR"],
				"PassCode"		=> CEncrypt::Encrypt($PassCode),
				"OldPassword"	=> "",
				"NewPassword1"	=> CEncrypt::Encrypt($NewPassword1),
				"NewPassword2"	=> CEncrypt::Encrypt($NewPassword2),
				"PassCodeValid" => (CUsers::ValidResetPassCode($PassCode) ? 1 : 0),
				"Success"		=> 0
			);

			if($NewPassword1 !== $NewPassword2) {
				$this->PageNotice = "The two passwords entered do not match.";
			}else
			if(strlen($NewPassword1) < 8) {
				$this->PageNotice = "Your new password must be at least 8 characters long.";
			}else
			if(CUsers::ValidResetPassCode($PassCode) == false) {
				$this->PageNotice = "There was a problem resetting your password, please try again.";
			}else
			if($User === false) {
				$this->PageNotice = "There was a problem resetting your password, please try again.";
			}else{
				$Data2 = Array(
					"Password"			=> CEncrypt::Encrypt($NewPassword1),
					"ResetTimestamp"	=> 0,
					"ResetPassCode"		=> ""
				);

				if(CTable::Update("Users", $User->ID, $Data2) == false) {
					$this->PageNotice = "There was a problem resetting your password, please try again.";
				}else{
					$Data["OldPassword"]	= $User->Password; // Already encrypted
					$Data["Success"]		= 1;

					CTable::Add("ResetPasswordAttempts", $Data);

					CApp::LoadModule("Login", Array("Reset" => "1", "Success" => "1"), false, false);
					return true;
				}
			}

			$Data["PageNotice"] = $this->PageNotice;

			CTable::Add("ResetPasswordAttempts", $Data);

			return false;
		}
	};

	//==========================================================================
?>
