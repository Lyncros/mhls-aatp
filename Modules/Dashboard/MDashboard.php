<?
	//=========================================================================
	class MDashboard extends CModule {
		function __construct() {
			parent::__construct();

			$this->IsSecure = true;
		}

		//---------------------------------------------------------------------
		function OnExecute() {
			$this->Actions["Dashboard"] = Array("OnClick" => "CModule.Load('Dashboard', null);", "Icon" => "Icon_Dashboard");

			if(CSecurity::IsAdmin("Businesses")) {
				$this->Actions["My Business"] = Array("OnClick" => "CModule.Load('MyBusiness', {});", "Icon" => "Icon_Business");
			}

			return parent::OnExecute();
		}

		//---------------------------------------------------------------------
		function OnRenderJS() { 
		}

		//---------------------------------------------------------------------
		function OnRenderCSS() {
			$this->FileControl->LoadFile("style.css", CFILE_TYPE_CSS);			
		}

		//---------------------------------------------------------------------
		function OnRender() {
			$this->ThemeControl->FileControl->LoadFile("header.php");			

			$this->FileControl->LoadFile("Render.php");

			$this->ThemeControl->FileControl->LoadFile("footer.php");
		}

		//----------------------------------------------------------------------
		function OnAJAX($Action) {
			if(parent::CanAccess($Action) == false) {
				return Array(0, "You do not have permission to perform this action");
			}

			return Array(1, "");
		}
	};

	//=========================================================================
?>
