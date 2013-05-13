<?
	//=========================================================================
	class MSystem extends CModule {
		function __construct() {
			parent::__construct();

			$this->IsSecure = true;
		}

		//---------------------------------------------------------------------
		function OnExecute() {
			$this->Actions["System"] = "CModule.Load('System', null);";

			return parent::OnExecute();
		}

		//---------------------------------------------------------------------
		function OnRenderJS() { 
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

			if($Action === "Window_AddEdit") {
				//return Array(1, $this->FileControl->LoadFile("AddEdit.php", CFILE_TYPE_CONTENTS_OB));
			}

			return Array(1, "");
		}
	};

	//=========================================================================
?>
