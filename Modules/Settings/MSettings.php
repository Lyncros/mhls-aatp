<?
	//==========================================================================
	/*
		[Permissions]
		[-]
	*/
	//==========================================================================
	class MSettings extends CModule {
		function __construct() {
			parent::__construct();
		}

		//----------------------------------------------------------------------
		function OnRenderJS() { 
			$this->FileControl->LoadFile("MSettings.js", CFILE_TYPE_JS);
		}

		//----------------------------------------------------------------------
		function OnRenderCSS() { 
			$this->FileControl->LoadFile("style.css", CFILE_TYPE_CSS);
		}

		//----------------------------------------------------------------------
		function OnRender() {
			$this->ThemeControl->FileControl->LoadFile("header.php");
			$this->FileControl->LoadFile("View.php");			
			$this->ThemeControl->FileControl->LoadFile("footer.php");
		}

		//----------------------------------------------------------------------
		function OnAJAX($Action) {
			if(parent::CanAccess($Action) == false) {
				return Array(0, "You do not have permission to perform this action");
			}

			if($Action == "Save") {
				return $this->Save();
			}

			return Array(0, "");
		}

		//----------------------------------------------------------------------
		function Save() {
			CSystem::SetValue("NewAuthorizationsDays",			$_POST["NewAuthorizationsDays"]);
			CSystem::SetValue("AuthorizationsCloseExpireDays",	$_POST["AuthorizationsCloseExpireDays"]);
			CSystem::SetValue("SystemLocked",					$_POST["SystemLocked"]);

			CSystem::SetValue("ProgressReportsAutoUnlock",		$_POST["ProgressReportsAutoUnlock"]);

			CTable::Update("UsersGroupsPermissions", 6, Array("Access" => $_POST["ProgressReportsOpen"]));

			return Array(1, "System Settings saved.");
		}
	};

	//==========================================================================
?>
