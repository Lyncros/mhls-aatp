<?
	//==========================================================================
	/*
		Table for Projects Resources

		6/25/2012 10:12 AM
	*/
	//==========================================================================
	class CProjectsResources extends CTable {
		function __construct() {
			$this->Table = "ProjectsResources";
		}

		public static function OnCron() {
			
		}

		function OnLoad($ID) {
			if(parent::OnLoadByID($ID) === false) {
				return false;
			}

			return $this->OnInit();
		}

		function OnInit() {
			return true;
		}
		
		//======================================================================
		function HasFile($ID) {
			$Temp = new CProjectsResources();
			if($Temp->OnLoad($ID) == false) {
				return false;
			}

			if(strlen($Temp->Filename) > 0 && ($File = CData::GetFile(CRESOURCES_DATA_PATH, $Temp->Filename)) !== false) {
				return $File;
			}

			return false;
		}
	};
?>
