<?
	//==========================================================================
	class MInstitution extends CModule {
		public $PageNotice		= "";
		public $SessionControl	= null;

		function __construct() {
			parent::__construct();

			$this->SessionControl = new CSession("MInstitution");

			if(!isset($this->SessionControl->LoginAttempts)) {
				$this->SessionControl->LoginAttempts = 0;
			}
		}

		//----------------------------------------------------------------------
		function OnExecute() {
			if($this->SessionControl->LoginAttempts >= 5) {
				CBannedIPs::Ban("", 60, "You have reached the maximum number of attempts to login.");

				$this->SessionControl->LoginAttempts = 0;

				$this->Parent->LoadModule("Institution");
				return;
			}

			$this->ThemeControl->SetTheme("Institution");

			$Action = @$_GET["Action"];

			if($Action === "CheckPassCode") {
				$this->CheckPassCode();
			}else
			if($Action === "Logout") {
				$this->Logout();
			}
		}

		//----------------------------------------------------------------------
		function OnRender() {
			$this->ThemeControl->FileControl->LoadFile("header.php");

			if($this->SessionControl->LoggedIn) {
				$this->FileControl->LoadFile("Render.php");
			}else{
				$this->FileControl->LoadFile("Error.php");
			}

			$this->ThemeControl->FileControl->LoadFile("footer.php");
		}

		//======================================================================
		function CheckPassCode() {
			$ID   		= intval($_POST["ID"]);		// Institution ID	
			$ABID 		= intval($_POST["ABID"]);   // Audit Bill ID
			$PassCode 	= CEncrypt::Encrypt(preg_replace("[^A-Fa-f0-9]", "", $_POST["PassCode"]));
			
			$AuditBill = new CAuditBills();
			if($AuditBill->OnLoadAll("WHERE `ID` = ".$ABID." && `PassCode` = '".mysql_real_escape_string($PassCode)."'") === false || $AuditBill->InstitutionsID != $ID) {
				$this->PageNotice = "Sorry, the Audit Bill you are trying to view doesn't exist.";
				return false;
			}		

			$this->SessionControl->LoginAttempts = 0;
			$this->SessionControl->LoggedIn 	 = 1;

			return true;
		}

		//----------------------------------------------------------------------
		function Logout() {
			unset($this->SessionControl->LoggedIn);

			$this->Parent->LoadModule("Institution");
			die();
		}
	};

	//==========================================================================
?>
