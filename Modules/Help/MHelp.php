<?
	//==========================================================================
	class MHelp extends CModule {
		function __construct() {
			parent::__construct();
		}

		//----------------------------------------------------------------------
		function OnAJAX($Action) {
			return Array(1, "");
		}

		//----------------------------------------------------------------------
		function OnExecute() {
		}

		//----------------------------------------------------------------------
		function OnRender() {
			$this->ThemeControl->FileControl->LoadFile("header.php");			
			$this->FileControl->LoadFile("Render.php");
			$this->ThemeControl->FileControl->LoadFile("footer.php");
		}
	};

	//==========================================================================
?>
