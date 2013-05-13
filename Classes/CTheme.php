<?
	//==========================================================================
	/*
		Class for the current Theme

		4/10/2009
	*/
	//==========================================================================
	class CTheme {
		public $FileControl = null;
		public $Name		= "";

		function __construct() {
			$this->FileControl = new CFile();
			$this->FileControl->Path = "/Theme/Default/Default";
		}

		function SetTheme($Name = "") {
			$this->Name = $Name;

			if($Name == "") {
				$this->Name = $Name = "Default";

				//No Parms Passed - see if Default exists
				if($BusinessesID == 0) {
					if($this->FileControl->IsDir("") == false) {
						return false;
					}
				}
			}		

			$this->FileControl->Path = "/Theme/Default/".$Name;
			if($this->FileControl->IsDir("") == false) {
				return false;
			}

			return true;
		}
	};

	//==========================================================================
?>
