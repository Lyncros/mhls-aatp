<?
	//==========================================================================
	/*
		Main Application class, creates Database connection, starts Security,
		and loads appropriate Module.

		4/10/2009
	*/
	//==========================================================================    
	include_once("Config.php");

	//==========================================================================
	class CApp {
		private	$FatalError			= false;		//If true, halts code and displays error

		private $ModuleName			= "";			//The name of the requested Module
		private	$PageName			= "";			//The name of the requested Page
		private	$URL				= "";			//The name of the requested URL

		private $Module				= null;			//Holds our Module object

		private $Theme				= null;			//Holds our current Theme

		private static $Instance	= null;			//Instance of CApp

		public $Settings			= null;			//Holds User settings (again, should be somewhere else... )

		//======================================================================
		/**
		 *	A lot of stuff going on in this __construct
		 *	Error reporting initialization
		 *	Registry construction
		 *	Session initialization
		 *	Database connection establishment - weird save to CPicPreview::$DatabaseLink
		 *	Default timezone set
		 *	Magic quote cleanup
		 *	Security initialization
		 *	Theme setup
		 *	Security check
		 *	Banned IP check
		 *
		 */
		function __construct() {			
			CDebug::OnInit();

			//For classes to access at any time (GetInstance)
			self::$Instance = $this;
                                
			CSession::OnInit();

			//------------------------------------------------------------------
			// Connect to mySQL
			//------------------------------------------------------------------
			$Connection = @mysql_connect(Config::$Options["mySQL"]["Host"], Config::$Options["mySQL"]["Username"], Config::$Options["mySQL"]["Password"]);

			if($Connection === false) {
				die("The Database connection is invalid. Please check your configuration options. [001]");
				return;
			}

			if(@mysql_select_db(Config::$Options["mySQL"]["Database"], $Connection) === false) {
				die("The Database connection is invalid. Please check your configuration options. [002]");
				return;
			}

			//------------------------------------------------------------------
			// Timezone Stuff
			//------------------------------------------------------------------
			date_default_timezone_set(Config::$Options["System"]["Timezone"]);

			mysql_query("SET time_zone = 'America/Indianapolis'");

			//------------------------------------------------------------------			
			CPicPreview::$DatabaseLink = $Connection; // For Cache

			// Magic Quotes is evil
			$_POST	= array_map(array("CTable", "RemoveMagicQuotes"), $_POST);
			$_GET	= array_map(array("CTable", "RemoveMagicQuotes"), $_GET);

			CSecurity::OnInit();

			$this->Theme = new CTheme(); // Load Theme Object (used for modules)

			//------------------------------------------------------------------
			// If logged in, load Settings
			//------------------------------------------------------------------
			if(CSecurity::IsLoggedIn()) {
				$this->Settings	= new CSettings();
				$this->Settings->OnLoad("App", "System");
			}

			//------------------------------------------------------------------
			// If we are banned, show Fatal Error
			//------------------------------------------------------------------
			if(($Rows = CBannedIPs::IsBanned()) !== false) {
				$Timeleft		= "";
				$Reason			= "";
				$BanTimePhrase	= "permanently";

				if(strlen($Rows->Reason) > 0) {
					$Reason = "<b>Reason:</b><br/>".$Rows->Reason."<br/><br/>";
				}

				if($Rows->ExpireMinutes > 0) {
					$Minutes = ($Rows->Timestamp + ($Rows->ExpireMinutes * 60)) - time();
					$Minutes = floor($Minutes / 60);

					$BanTimePhrase = "temporarily";

					$Timeleft = "Please wait ".$Minutes." minutes before continuing.<br/><br/>";
				}

				$this->OnFatalError("Your IP '".$_SERVER["REMOTE_ADDR"]."' has been <b>$BanTimePhrase</b> banned. If you feel this is an error, please contact the system administrator referencing this page's URL.<br/><br/>$Reason $Timeleft <b>Contact:</b> banned-ip [at] jhspecialty.com");
			}
		}

		//----------------------------------------------------------------------
		function __destruct() {
			CSession::OnDone();
		}

		//----------------------------------------------------------------------
		/**
		 *	Event driven method for driving the request
		 *	Detect banned IP
		 *	Determine domain
		 *	Determine requested page
		 *	Determine if the requested page is a module
		 *	If Module, handle Module request
		 *	
		 */
		function OnExecute() {
			if($this->FatalError) return false;
			
			if(CBannedIPs::IsBanned() !== false) {
				return false;
			}

			//------------------------------------------------------------------
			// Grab the requested Page
			//------------------------------------------------------------------
			$Parts = explode("/", $_SERVER["REQUEST_URI"]);
			$Parts = end($Parts);
			
			$Parts = explode("?", $Parts);
			$Parts = $Parts[0];
			
			$this->URL			= CURL::GetDomain();
			$this->PageName		= urldecode($Parts);
			
			//if(substr($this->PageName, 0, 1) == ".") {
			$this->ModuleName = $this->PageName;	//substr($this->PageName, 0);
			//}
			//------------------------------------------------------------------
			// Is this requested Page a module?
			//------------------------------------------------------------------				
			if(strlen($this->ModuleName) > 0 && CModule::Exists($this->ModuleName)) {
			
				//Set the Module theme to Default
				if($this->Theme->SetTheme() == false) {
					$this->OnFatalError("The Default Theme cannot be Found");
					return false;
				}
				
				$this->Module = CModule::LoadObject($this->ModuleName, $this); //If so, try load it
				
				//Is this a valid Module?
				if($this->Module === false) {
					die();
					if(CSecurity::IsLoggedIn() == false)	$this->LoadModule("Login");
					else									$this->LoadModule("Dashboard");

					return false;
				}

				if(CSecurity::IsLoggedIn() && CSecurity::$User->Email == "" && $this->Module->Name != "MyAccount" && $this->Module->Name != "Login") {
					$this->LoadModule("MyAccount", Array(), false, false, false);
					return false;
				}

				$this->Module->App = $this;

				//We're not Logged in, but trying to access a module
				//Go to the Login screen
				if(CSecurity::IsLoggedIn() == false && $this->Module->IsSecure) {
					$SessionControl = new CSession("CSystem");
					$SessionControl->DestinationURL = $_SERVER["REQUEST_URI"];

					$this->LoadModule("Login");
					return false;
				}

				//Do we have access to this module?
				if($this->Module->IsSecure && CSecurity::CanAccess($this->Module->Name, "Access") == false) {
					$this->LoadModule("Dashboard", Array(), false, false);
					return false;
				}
				
				//Execute the Module
				$this->Module->OnInit($this->Module, $this->Theme);
				$this->Module->OnExecute();

			} else if(strlen($this->ModuleName) > 0 && $this->ModuleName == PROJECTS_EXPORT_WITHOUT_AUTH) {
				$Parts = explode("/", $_SERVER["REQUEST_URI"]);
				
				die();
			} else {
				
				$this->LoadModule("Login");
				return false;
			}

			if(!$this->Module) {
				return false;
			}

			//Start Rendering!
			$this->OnRender();

			return true;
		}

		//======================================================================
		/**
		 *	Event driven request for distributing a RenderCSS event
		 *	to the appropriate objects
		 *	
		 */
		function OnRenderCSS() {
			$this->Module->OnRenderCSS();
		}

		//----------------------------------------------------------------------
		/**
		 *	Event driven request for distributing a RenderJS event to the 
		 *	appropriate objects
		 *	
		 */
		function OnRenderJS() {
			$this->Module->OnRenderJS();
		}

		//----------------------------------------------------------------------
		/**
		 *	Event driven request for distributing a Render event to the 
		 *	appropriate objects
		 *	
		 */
		function OnRender() {
			$this->Module->OnRender();
		}

		//----------------------------------------------------------------------
		/**
		 *	Delivers a Fatal Error to the browser
		 *	
		 *	@param string $Error
		 */
		function OnFatalError($Error) {
			$this->Theme->SetTheme("Fatal");

			$this->Theme->FileControl->LoadFile("header.php");
			echo $Error;
			$this->Theme->FileControl->LoadFile("footer.php");

			$this->FatalError = true;
		}

		//======================================================================
		/**
		 *	Redirect a request to a module using a given set of parameters and javascript
		 *	
		 *	@param string $Module - name of the module
		 *	@param array $Params - a key/value array capable of being passed to http_build_query
		 *	@param boolean $UseGetParams - optional, false by default
		 *	@param boolean $CheckSecurity - optional, true by default
		 *	@return boolean
		 */
		function LoadModule($Module, $Parms = Array(), $UseGetParms = false, $CheckSecurity = true, $RedirectJS = true) {
			if($CheckSecurity && !CSecurity::CanAccess($Module)) {
				return false;
			}
			
			if(CModule::Exists($Module)) {			
				if($RedirectJS) {					
					echo "<script language='Javascript' type='text/javascript'>document.location.href = \"".CURL::FormatURL("/$Module", $Parms, $UseGetParms)."\";</script>";
				}else{
					die('test2');
					Header("Location: ".CURL::FormatURL("/$Module", $Parms, $UseGetParms));
				}

				return true;
			}

			return false;
		}

		//======================================================================
		/**
		 *	
		 *	@return CApp
		 */
		public static function GetInstance() {
			return self::$Instance;
		}

		//----------------------------------------------------------------------
		/**
		 *
		 *	@return CModule
		 */
		function GetModule() {
			return $this->Module;
		}

		//----------------------------------------------------------------------
		/**
		 *
		 *	@return string
		 */
		function GetModuleName() {
			if($this->Module == null) return "";

			return $this->Module->Name;
		}

		//----------------------------------------------------------------------
		/**
		 *
		 *	@return CTheme
		 */
		function GetTheme() {
			return $this->Theme;
		}
	};

	//==========================================================================
?>
