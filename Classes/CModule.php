<?
	//==========================================================================
	/*
		Abstract class for creating Modules 

		See CModuleGeneric.php for more information

		4/10/2009
	*/
	//==========================================================================
	class CModule extends CAJAX {
		public $App					= null;

		public $Name				= "Unknown";
		public $Parent				= null;
		public $IsSecure			= false;

		public $FileControl			= null;
		public $ThemeControl		= null;

		public $TableObject			= null;
		public $IsBusModule			= false;

		public $Settings			= null;

		protected $Actions			= Array();

		function __construct($Name = "") {
			if(strlen($Name > 0)) {
				$this->Name = $Name;
			}

			$this->FileControl				= new CFile();
			$this->FileControl->Path		= "/Modules/".$this->Name;
			$this->FileControl->Parent		= $this;

			$this->Settings			= new CSettings();
		}

		function OnInit($ModulePointer, $ThemeControl) {
			$this->ThemeControl							= $ThemeControl;

			$this->FileControl->Parent					= $ModulePointer;
			$this->ThemeControl->FileControl->Parent	= $ModulePointer;

			$this->FileControl->Path = "/Modules/".$this->Name;

			$this->Settings->OnLoad($this->Name, "Module");
		}

		function OnExecute() {
			/*
			if($this->CanAccess(@$_POST["Action"]) == false) {
				$this->Parent->LoadModule($this->Name);
				return false;
			}
			*/

			$this->OnAJAX(@$_POST["Action"]);

			return true;
		}

		function OnRenderCSS() {
		}

		function OnRenderJS() {
		}

		function OnRender() {
		}

		function GetActions() {
			return $this->Actions;
		}

		function GetSettingsOptions() {
			return $this->Settings->Options;
		}

		function CanAccess($Action) {
			if(!CSecurity::CanAccess($this->Name, $Action)) {
				return false;
			}

			return true;
		}

		//======================================================================
		public function OnAJAX($Action) {
			if(CSecurity::IsLoggedIn()) {
				if($Action == "SaveSettings") {
					return $this->SaveSettings();
				}
			}

			return Array(1, "");
		}

		private function SaveSettings() {
			$ModuleName = $_POST["Name"];

			$Settings = new CSettings();
			$Settings->OnLoad($ModuleName, "Module");

			foreach($_POST as $Key => $Value) {
				if(strpos($Key, "CModule_Settings_") !== false) {
					$Name = str_replace("CModule_Settings_", "", $Key);

					$Settings->SetValue($Name, $Value);
				}
			}

			return Array(1, "Success (Save Settings)");
		}

		//======================================================================
		// Static Functions
		//======================================================================
		public static function Exists($Name) {
			if(file_exists("./Modules/".$Name."/M".$Name.".php") && is_dir("./Modules/".$Name."/M".$Name.".php") == false) {
				return true;
			}

			return false;
		}

		public static function LoadObject($Name, $Parent) {
			if(CModule::Exists($Name) == false) return false;

			$IsBusModule = false;

			include_once("./Modules/".$Name."/M".$Name.".php");

			$Classname = "M".$Name;

			if(class_exists($Classname) == false) return false;

			$NewModule			= new $Classname($Name);

			$NewModule->Name		= $Name;
			$NewModule->Parent		= $Parent;
			$NewModule->IsBusModule = $IsBusModule;

			return $NewModule;
		}

		public static function GetAllPermissions() {
			$Folders = scandir("./Modules/", 1);

			array_pop($Folders);
			array_pop($Folders);

			$Permissions = Array();

			sort($Folders);

			foreach($Folders as $Folder) {
				if(is_dir("./Modules/".$Folder) == false) continue;

				$Permissions[$Folder] = CSecurity::GetDefaultPermissionList();

				preg_match_all("/\[Permissions\](.*)\[\-\]/is", file_get_contents("./Modules/".$Folder."/M".$Folder.".php"), $Matches);

				$Parts = explode("\n", $Matches[1][0]);

				foreach($Parts as $Part) {
					$Part = trim($Part);
					$Part = str_replace("\r", "", $Part);

					if(strlen($Part) <= 0) continue;

					$Permissions[$Folder][] = $Part;
				}
			}

			ksort($Permissions);

			return $Permissions;
		}

		public static function GetAllNotifications() {
			$Folders = scandir("./Modules/", 1);

			array_pop($Folders);
			array_pop($Folders);

			$Notifications = Array();

			sort($Folders);

			foreach($Folders as $Folder) {
				if(is_dir("./Modules/".$Folder) == false) continue;

				$NumMatches = preg_match_all("/\[Notifications\](.*)\[\-\]/is", file_get_contents("./Modules/".$Folder."/M".$Folder.".php"), $Matches);

				if($NumMatches !== false && $NumMatches > 0) {
					$Parts = explode("\n", $Matches[1][0]);

					foreach($Parts as $Part) {
						$Part = trim($Part);
						$Part = str_replace("\r", "", $Part);

						if(strlen($Part) <= 0) continue;

						$Notifications[$Folder][] = $Part;
					}
				}
			}

			return $Notifications;
		}
	};

	//==========================================================================
?>
