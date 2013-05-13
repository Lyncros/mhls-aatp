<?
	//==========================================================================
	/*
		Class with method for archiving files

		3/17/2010
	*/	
	//==========================================================================
	class CZip {
		function __construct() {	
		}

		//======================================================================
		// Static functions
		//======================================================================
		public static function Archive($Name, $Files) {
			$Archive = "./Data/" . CZIP_DATA_PATH . "/" . CData::GenerateName($Name) . ".zip";
			
			if(is_array($Files) === false || count($Files) <= 0) {
				return Array(0, "Invalid argument: expecting array");
			}
			
			$Zip = new ZipArchive();
			if(($Open = $Zip->open($Archive, ZIPARCHIVE::CREATE)) !== true) {
				return Array(0, "Error opening archive: " . $Open);
			}
			
			foreach($Files as $File) {
				$Zip->addfile($File);
			}
			
			if($Zip->close() === false) {
				return Array(0, "Error closing archive");
			}
			
			return Array(1, $Archive);
		}
	};
?>
