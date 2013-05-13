<?
	//==========================================================================
	/*
		A Class to handle File Uploading in CKEditor

		See "Uploader" plugin in ./js/ckeditor/plugins/uploader/

		Integrates into FileManager Module

		TO DO: Finish Me!

		1/13/2010 9:05 AM
	*/
	//==========================================================================
	class CCKEditorUploader extends CAJAX {
		private $FileManager = null;

		function __construct() {
			if(($this->FileManager = CModule::LoadObject("FileManager", $this)) === false) {
				trigger_error("Unable to load File Manager", E_ERROR);
			}
		}

		function OnAJAX($Action) {
			if($this->FileManager == null) return Array(0, "Action not Found");

			return $this->FileManager->OnAJAX($Action);
		}
	};

	//==========================================================================
?>
