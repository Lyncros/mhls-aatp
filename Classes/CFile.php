<?
	//==========================================================================
	/*
		A Class for manipulating Files (used by Modules/Plugins to load their
		respective files inside their folders). Can also be used by Modules and
		Plugins to load other Modules/Plugins files.

		4/10/2009
	*/
	//==========================================================================
	define("CFILE_TYPE_NORMAL",			0);
	define("CFILE_TYPE_CONTENTS",		1);
	define("CFILE_TYPE_CONTENTS_OB",	2);
	define("CFILE_TYPE_JS",				3);
	define("CFILE_TYPE_CSS",			4);
	define("CFILE_TYPE_INCLUDE",		5);

	//==========================================================================
	class CFile {
		public $Path	= "";
		public $Parent	= null;

		function __construct() {
			$this->Path = "/";
		}

		//======================================================================
		function LoadFile($File, $Type = CFILE_TYPE_NORMAL) {
			if(self::FileExists($File) == false || self::IsDir($File) == true) {
				return false;
			}

			if($Type == CFILE_TYPE_NORMAL) {
				include_once(self::GetRealPath().$File);
			}else
			if($Type == CFILE_TYPE_CONTENTS) {
				return file_get_contents(self::GetRealPath().$File);
			}else
			if($Type == CFILE_TYPE_CONTENTS_OB) {
				ob_start();
				include(self::GetRealPath().$File);
				$Contents = ob_get_contents();
				ob_end_clean();

				return $Contents;
			}else
			if($Type == CFILE_TYPE_JS) {
				echo "<script type=\"text/javascript\" src=\"".$this->Path."/".$File."\"></script>\n";
			}else
			if($Type == CFILE_TYPE_CSS) {
				echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$this->Path."/".$File."\">\n";
			}else
			if($Type == CFILE_TYPE_INCLUDE) {
				include(self::GetRealPath().$File);
			}

			return true;
		}

		//----------------------------------------------------------------------
		function ScanDir($Path) {
			if(self::IsDir($Path) == false) {
				return false;
			}

			$Files = scandir(self::GetRealPath().$Path, 1);

			array_pop($Files);
			array_pop($Files);

			sort($Files);

			return $Files;
		}

		//----------------------------------------------------------------------
		function FileExists($File) {
			if(file_exists(self::GetRealPath().$File) == false) {
				return false;
			}

			return true;
		}

		//----------------------------------------------------------------------
		function IsDir($Path) {
			if(is_dir(self::GetRealPath().$Path) == false) {
				return false;
			}

			return true;
		}

		//----------------------------------------------------------------------
		function ChangeDir($Path) {
			if(chdir(self::GetRealPath().$Path) == false) {
				return false;
			}

			return true;
		}

		//----------------------------------------------------------------------
		function GetRealPath() {
			$RealPath = $this->Path;

			if(substr($this->Path, 0, 1) == "/") {
				$RealPath = "./".substr($this->Path, 1);
			}

			if(substr($RealPath, -1) !== "/") {
				$RealPath = $RealPath."/";
			}

			return $RealPath;
		}

		//======================================================================
		// Static Functions
		//======================================================================
		public static function LoadExternFile($Request, $Name, $File, $Type = CFILE_TYPE_NORMAL) {
			$FileControl = new CFile();

			switch($Request) {
				case "Module": {
					if(is_dir("./Modules/".$Name) == false) return false;

					$FileControl->Path = "/Modules/".$Name;

					break;
				}

				case "Plugin": {
					if(is_dir("./Plugins/".$Name) == false) return false;

					$FileControl->Path = "/Plugins/".$Name;

					break;
				}

				case "Template": {
					if(is_dir("./Templates/".$Name) == false) return false;

					$FileControl->Path = "/Templates/".$Name;

					break;
				}

				default: {
					return false;
				}
			}

			return $FileControl->LoadFile($File, $Type);
		}
	};

	//==========================================================================
?>
