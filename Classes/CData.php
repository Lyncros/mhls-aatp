<?
	//==========================================================================
	/*
		A class for manipulating files/folders within the Data folder. The Data
		folder is by other classes for storing files and such (see 
		CProducts.php).

		Also provides a Cron service for clearing Uploads inside the Temp folder

		4/10/2009
	*/
	//==========================================================================
	class CData extends CAJAX {
		public static $PathData = "./Data/";
		public static $PathTemp = "./Temp/";

		/**
		 *
		 *
		 *
		 */
		function OnAJAX($Action) {
			if($Action == "UploadTemp") {
				$File = $_FILES["Filedata"];

				$TempFile = $File["tmp_name"];
				$Filename = CData::GenerateName($File['name']);

				list($ErrorCode, $Error) = CData::CheckUploadedFile($File);

				if($ErrorCode != 1) {
					return Array(0, $Error);
				}

				if(move_uploaded_file($TempFile, CData::$PathTemp.$Filename) == false) {
					return Array(0, "");
				}

				return Array(1, $Filename);
			}

			if($Action == "UploadResource") {
				$File = $_FILES["Filedata"];

				$TempFile = $File["tmp_name"];
				$Filename = CData::GenerateName($File['name']);

				list($ErrorCode, $Error) = CData::CheckUploadedFile($File);

				if($ErrorCode != 1) {
					return Array(0, $Error);
				}

				if(move_uploaded_file($TempFile, CData::$PathData.CRESOURCES_DATA_PATH."/".$Filename) == false) {
					return Array(0, "");
				}

				return Array(1, $Filename);
			}

			return Array(1, "");
		}

		/**
		 *
		 *
		 *
		 */
		public static function OnCron() {
			$Expire = 60 * 60 * 2; //2 Hours

			$Files = scandir(CData::$PathTemp);

			array_pop($Files);
			array_pop($Files);

			foreach($Files as $File) {
				if(is_dir(CData::$PathTemp.$File)) continue;

				if(filemtime(CData::$PathTemp.$File) + $Expire < mktime()) {
					unlink(CData::$PathTemp.$File);
				}
			}
		}

		/**
		 *
		 *
		 *
		 */
		public static function BuildPath($Folder, $File) {
			return CData::$PathData.$Folder."/".$File;
		}

		/**
		 *
		 *
		 *
		 */
		public static function FileExists($Folder, $File) {
			if(strlen($Folder) <= 0)	return false;
			if(strlen($File) <= 0)		return false;

			if(CData::FolderExists($Folder) == false) return false;

			if(file_exists(CData::$PathData.$Folder."/".$File)) {
				return CData::$PathData.$Folder."/".$File;
			}

			return false;
		}

		/**
		 *
		 *
		 *
		 */
		public static function FolderExists($Folder) {
			if(strlen($Folder) <= 0) return false;			

			if(file_exists(CData::$PathData.$Folder."/") == false || is_dir(CData::$PathData.$Folder."/") == false) {
				return false;
			}

			return true;
		}

		/**
		 *
		 *
		 *
		 */
		public static function GetFile($Folder, $File) {
			if(CData::FileExists($Folder, $File) == false) {
				return false;
			}

			return CData::$PathData.$Folder."/".$File;
		}

		/**
		 *
		 *
		 *
		 */
		public static function GetFiles($Folder) {
			if(CData::FolderExists($Folder) == false) {
				return false;
			}

			$Files = scandir(CData::$PathData.$Folder."/", 1);			
	
			if(count($Files) <= 0) {
				return false;
			}

			array_pop($Files);
			array_pop($Files);

			sort($Files);

			return $Files;
		}

		/**
		 *
		 *
		 *
		 */
		public static function GetFilesDetailed($Folder) {
			if(CData::FolderExists($Folder) == false) {
				return false;
			}

			$Files = scandir(CData::$PathData.$Folder."/");
	
			if(count($Files) <= 0) {
				return false;
			}

			sort($Files);

			$NewFiles = Array();

			foreach($Files as $File) {
				if($File == "." || $File == "..") continue;

				$Type = "File";

				if(is_dir(CData::$PathData.$Folder."/".$File)) $Type = "Folder";

				$NewFiles[] = Array(
					"Type"		=> $Type,
					"Path"		=> CData::$PathData.$Folder,
					"Filename"	=> $File,
					"Created"	=> filectime(CData::$PathData.$Folder."/".$File),
					"Modified"	=> filemtime(CData::$PathData.$Folder."/".$File)
				);
			}

			return $NewFiles;
		}

		/**
		 *
		 *
		 *
		 */
		public static function GetNumFolders($Folder) {
			if(CData::FolderExists($Folder) == false) {
				return false;
			}

			$Files = CData::GetFilesDetailed($Folder);

			$Count = 0;

			foreach($Files as $File) {
				if($File["Type"] == "Folder") {
					++$Count;
				}
			}

			return $Count;
		}

		public static function CreateFile($Folder, $Filename, $Content) {
			return file_put_contents(CData::$PathData.$Folder."/".$Filename, $Content);
		}


		/**
		 *
		 *
		 *
		 */
		public static function CreateFolder($Folder) {
			if(CData::FolderExists($Folder)) {
				return false;
			}

			mkdir(CData::$PathData.$Folder);

			return chmod(CData::$PathData.$Folder, 0777);
		}

		/**
		 *
		 *
		 *
		 */
		public static function RemoveFile($Folder, $File) {
			if(CData::FileExists($Folder, $File) == false) {
				return false;
			}

			unlink(CData::$PathData.$Folder."/".$File);

			return true;
		}

		/**
		 *
		 *
		 *
		 */
		public static function RemoveFolder($Folder, $Folder2, $Recursive = false) {
			if(CData::FileExists($Folder, $Folder2) == false) {
				return false;
			}

			if($Recursive) {
				$Files = self::GetFilesDetailed($Folder."/".$Folder2);

				foreach($Files as $File) {
					if($File["Type"] == "Folder") {
						self::RemoveFolder($Folder."/".$Folder2, $File["Filename"], true);
					}else{
						self::RemoveFile($Folder."/".$Folder2, $File["Filename"]);
					}
				}
			}

			rmdir(CData::$PathData.$Folder."/".$Folder2);

			return true;
		}

		/**
		 *
		 *
		 *
		 */
		public static function AddUploadedTempFile($Folder, $Filename) {
			if(CData::FolderExists($Folder) == false) {
				return false;
			}

			if(copy(CData::$PathTemp.$Filename, CData::$PathData.$Folder."/".$Filename) == false) {
				return false;
			}

			if(unlink(CData::$PathTemp.$Filename) == false) {
				return false;
			}

			return true;
		}

		/**
		 *
		 *
		 *
		 */
		public static function AddUploadedFile($Folder, $File) {
			if(CData::FolderExists($Folder) == false) {
				return false;
			}

			$TempFile = $File["tmp_name"];
			$Filename = CData::GenerateName($File['name']);

			if(@move_uploaded_file($TempFile, CData::$PathData.$Folder."/".$Filename) == false) {
				trigger_error("Unable to move uploaded file : $Folder/$File", E_USER_WARNING);
				return false;
			}

			return $Filename;
		}

		/**
		 *
		 *
		 *
		 */
		public static function AddURLFile($Folder, $URL) {
			if(CData::FolderExists($Folder) == false) {
				return false;
			}

			$Filename = basename($URL);
			$Filename = CData::GenerateName($Filename);

			if(copy($URL, CData::$PathData.$Folder."/".$Filename) === false) {
				return false;
			}

			return $Filename;
		}

		/**
		 *
		 *
		 *
		 */
		public static function MoveFile($Folder, $DestFolder, $Filename) {
			if(CData::FileExists($Folder, $Filename) == false) {
				return false;
			}

			return rename(CData::$PathData.$Folder."/".$Filename, CData::$PathData.$DestFolder."/".$Filename);
		}

		/**
		 *
		 *
		 *
		 */
		public static function RenameFile($Folder, $Filename, $NewFilename) {
			if(CData::FileExists($Folder, $Filename) == false) {
				return false;
			}

			return rename(CData::$PathData.$Folder."/".$Filename, CData::$PathData.$Folder."/".$NewFilename);
		}

		/**
		 *
		 *
		 *
		 */
		public static function RenameFolder($PathSrc, $FolderSrc, $PathDest, $FolderDest) {
			if(CData::FileExists($PathSrc, $FolderSrc) == false) {
				return false;
			}

			if(CData::FolderExists($PathDest) == false) {
				return false;
			}

			return rename(CData::$PathData.$PathSrc."/".$FolderSrc, CData::$PathData.$PathDest."/".$FolderDest);
		}

		/**
		 *
		 *
		 *
		 */
		public static function OutputFile($Folder, $File, $DownloadFilename = "", $Header = true, $ForceDownload = false) {
			if(CData::FileExists($Folder, $File) == false) {
				return false;
			}

			if(strlen($DownloadFilename) <= 0) {
				$DownloadFilename = $File;
			}

			if($Header) {
				if(strlen($DownloadFilename) > 0) {
					$MimeType = CData::GetMimeType($Folder, $DownloadFilename);
				}else{
					$MimeType = CData::GetMimeType($Folder, $File);
				}

				Header("Content-type: $MimeType");

				if($ForceDownload) {
					Header("Pragma: public");
					Header("Content-disposition: attachment;filename=\"".$DownloadFilename."\"");
					Header("Content-length: ".filesize(CData::$PathData.$Folder."/".$File));
				}
			}

			$FileHandle = fopen(CData::$PathData.$Folder."/".$File, "r");

			while(!feof($FileHandle)) {
				echo fread($FileHandle, 1024);
			}

			fclose($FileHandle);
		}

		/**
		 *
		 *
		 *
		 */
		public static function GetMimeType($Folder, $File) {
			//if(CData::FileExists($Folder, $File) == false) {
			//	return false;
			//}

			$MimeType = "";

			//I should be using the commented out code below
			$Ext = explode(".", $File);
			$Ext = end($Ext);
			$Ext = strtolower($Ext);

			switch($Ext) {
				case "jpg":
				case "jpeg": { $MimeType = "image/jpeg"; break;	}
				case "gif": { $MimeType = "image/gif"; break; }
				case "png": { $MimeType = "image/png"; break; }
				case "bmp": { $MimeType = "image/x-ms-bmp"; break; }
				case "tif":
				case "tiff": { $MimeType = "image/tiff"; break; }

				case "pdf" : { $MimeType = "application/pdf"; break; }

				case "htm": 
				case "html": { $MimeType = "text/html"; break; }
				case "txt": { $MimeType = "text/plain"; break; }

				default: {
					$MimeType = "application/octet-stream";
					break;
				}
			}

			/*$FileInfo = finfo_open(FILEINFO_MIME);

			if(!$FileInfo) {
				return false;
			}

			$MimeType = finfo_file($FileInfo, CData::$PathData.$Folder."/".$File);

			finfo_close($FileInfo);*/

			return $MimeType;
		}

		/**
		 *	Do error checking on a form uploaded file
		 *	returns an array with the first element representing
		 *	the error code (integer) and the second element
		 *	representing the error string.
		 *	You can optionally use the second parameter to 
		 *	supply a list of specific file extensions that you
		 *	want the file filter for.
		 *
		 *	use: CData::CheckUploadedFile($_FILES['myuploadedfile'])
		 *
		 *	@param array $File
		 *	@param array $AllowedExtensions
		 *	@return array
		 */
		public static function CheckUploadedFile($File, $AllowedExtensions = Array()) {
			$ErrorCode	= 1;
			$Error		= "";

			if(count($AllowedExtensions) <= 0) {
				$AllowedExtensions[] = "png";
				$AllowedExtensions[] = "jpg";
				$AllowedExtensions[] = "jpeg";
				$AllowedExtensions[] = "gif";
				$AllowedExtensions[] = "bmp";
				$AllowedExtensions[] = "tif";

				$AllowedExtensions[] = "mpg";
				$AllowedExtensions[] = "mpeg";
				$AllowedExtensions[] = "flv";
				$AllowedExtensions[] = "mov";
				$AllowedExtensions[] = "avi";
				$AllowedExtensions[] = "mp4";
				$AllowedExtensions[] = "mkv";
				$AllowedExtensions[] = "m4v";
				$AllowedExtensions[] = "wmv";

				$AllowedExtensions[] = "m4a";
				$AllowedExtensions[] = "mp3";
				$AllowedExtensions[] = "ogg";
				$AllowedExtensions[] = "wav";
				$AllowedExtensions[] = "flac";

				$AllowedExtensions[] = "psd";
				$AllowedExtensions[] = "eps";
				$AllowedExtensions[] = "pdf";
				$AllowedExtensions[] = "ai";
				$AllowedExtensions[] = "svg";

				$AllowedExtensions[] = "html";
				$AllowedExtensions[] = "js";
				$AllowedExtensions[] = "css";
				$AllowedExtensions[] = "xml";

				$AllowedExtensions[] = "zip";
				$AllowedExtensions[] = "7z";
				$AllowedExtensions[] = "tar";
				$AllowedExtensions[] = "gz";
				$AllowedExtensions[] = "rar";

				$AllowedExtensions[] = "txt";
				$AllowedExtensions[] = "log";
				$AllowedExtensions[] = "rtf";
				$AllowedExtensions[] = "doc";
				$AllowedExtensions[] = "docx";
				$AllowedExtensions[] = "xls";
				$AllowedExtensions[] = "xlsx";
				$AllowedExtensions[] = "ppt";
				$AllowedExtensions[] = "pptx";
				$AllowedExtensions[] = "csv";

				$AllowedExtensions[] = "ttf";
				$AllowedExtensions[] = "otf";
			}

			if($File["error"] == 0) {
			}else if($File["size"] > 0) {
				switch($File["error"]) {
					case UPLOAD_ERR_INI_SIZE: 
					case UPLOAD_ERR_FORM_SIZE: {
						$ErrorCode	= 0;
						$Error		= "The file '".$File['name']."' you are trying to upload is too large";
						break;
					}

					case UPLOAD_ERR_PARTIAL:
					case UPLOAD_ERR_NO_FILE: {
						$ErrorCode	= 0;
						$Error		= "There was an error while uploading your file, please try again";
						break;
					}

					case UPLOAD_ERR_NO_TMP_DIR: {
						$ErrorCode	= 0;
						$Error		= "There is no temporary upload folder, please notify the administrator.";
						break;
					}

					case UPLOAD_ERR_CANT_WRITE: {
						$ErrorCode	= 0;
						$Error		= "Unable to write file to disk, please notify the administrator.";
						break;
					}

					case UPLOAD_ERR_EXTENSION: {
						$ErrorCode	= 0;
						$Error		= "You cannot upload this type of file (".$File['name'].").";
						break;
					}
				}
			}

			if(count($AllowedExtensions) > 0) {
				$Ext = explode(".", $File['name']);

				if(count($Ext) <= 1) {
					$ErrorCode	= 0;
					$Error		= "Invalid file extension";
				}else{
					$Ext = end($Ext);
					$Ext = strtolower($Ext);

					$Found = false;

					foreach($AllowedExtensions as $Extension) {
						$Extension = strtolower($Extension);

						if($Ext == $Extension) {
							$Found = true;
							break;
						}
					}

					if($Found == false) {
						$ErrorCode	= 0;
						$Error		= "Invalid file extension";
					}
				}
			}else{
				$ErrorCode	= 0;
				$Error		= "Invalid file extension";
			}

			return Array($ErrorCode, $Error);
		}

		/**
		 *	Remove unwanted characters from a file name
		 *	and ensure uniqueness by prepending a hash string
		 *
		 *	@param string $File
		 *	@return string
		 */
		public static function GenerateName($File) {
			$File = str_replace(" ", "", $File);
			$File = str_replace("=", "", $File);
			$File = str_replace("/", "", $File);
			$File = str_replace("\\", "", $File);
			$File = str_replace("$", "", $File);
			$File = str_replace("?", "", $File);
			$File = str_replace("&", "", $File);
			$File = str_replace("+", "", $File);

			$Name = md5(microtime())."-".$File;

			return $Name;
		}

		/**
		 *	Remove unwanted characters from a file path
		 *
		 *	@param string $Path
		 *	@return string
		 */
		public static function SanitizePath($Path) {
			$Path = str_replace("..", "", $Path);
			$Path = str_replace("$", "", $Path);
			$Path = str_replace("\\", "/", $Path);
			$Path = str_replace("//", "/", $Path);

			//Fix me - Should be some RegEx

			return $Path;
		}

		/**
		 *	Remove unwanted characters from a file name
		 *
		 *	@param string $Filename
		 *	@return string
		 */
		public static function SanitizeFilename($Filename) {
			$Filename = str_replace(" ", "", $Filename);
			$Filename = str_replace("..", "", $Filename);
			$Filename = str_replace("$", "", $Filename);
			$Filename = str_replace("/", "", $Filename);
			$Filename = str_replace("\\", "", $Filename);
			$Filename = str_replace(":", "", $Filename);
			$Filename = str_replace("@", "", $Filename);
			$Filename = str_replace("#", "", $Filename);
			$Filename = str_replace("$", "", $Filename);
			$Filename = str_replace("%", "", $Filename);
			$Filename = str_replace("^", "", $Filename);
			$Filename = str_replace("*", "", $Filename);
			$Filename = str_replace("`", "", $Filename);
			$Filename = str_replace("~", "", $Filename);

			return $Filename;
		}

		public static function CreateTempFile() {
			$Filename = md5(microtime()).sha1(microtime());

			file_put_contents(CData::$PathTemp.$Filename, "");

			return $Filename;
		}
	};

	//==========================================================================
?>
