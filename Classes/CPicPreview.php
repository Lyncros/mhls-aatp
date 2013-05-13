<?
	//==========================================================================
	/*
		Picture Preview File

		Resizes a picture to the specified size. If the ratio flag is on, the picture will be resized
		with the width to height ratio in tact (the longest of the two will become the size specified).
		If the ratio flag is off, the width and height will be resized to the size specified.

		Caches images in the specified folder, and creates a data files that tracks checksum keys,
		to see if an image has updated.

		6/30/2009 9:17 AM

		Author: Tim Jones
	*/
	//==========================================================================
	include_once("Config.php");

	//==========================================================================
	class CPicPreview {
		//----------------------------------------------------------------------
		// Thumbnail Generation Options
		//----------------------------------------------------------------------
		private $File			= "";
		private $Size			= "";
		private $Ratio			= 1;

		private $Width			= 0;
		private $Height			= 0;

		private $AutoCrop		= 0;
		private $AutoCropPos	= "Center Center"; //Left, Top, Right, Bottom, Center
		private	$SizeType		= "Auto";	//Auto, Width, Height, Smallest

		private $Debug			= true;

		private	$IsURL			= false;

		//----------------------------------------------------------------------
		// File Information
		//----------------------------------------------------------------------
		private $Ext			= "";
		private $MimeType		= "";

		private $Data			= Array();

		//----------------------------------------------------------------------
		// Cache Storage
		//----------------------------------------------------------------------
		private $StorageType	= "mySQL"; //mySQL, File, None

		//mySQL
		static public $DatabaseLink	= 0;

		private $DatabaseHost		= "";
		private $DatabaseUsername	= "";
		private $DatabasePassword	= "";
		private $DatabaseName		= "";
		private $DatabaseTable		= "Cache";

		//File
		private $CacheDat		= "data.dat";
		private $CacheData		= Array();

		//All
		public  $CacheFolder	= "./Cache/";
		private $CacheFile		= "";

		private $CacheKey		= "";
		private $CacheKeyStored = "";

		//----------------------------------------------------------------------
		// Other
		//----------------------------------------------------------------------
		private $AbsPath		= "";

		private $FileNotFound	= "./gfx/image-not-available.jpg"; //jpeg file

		//======================================================================
		function __construct() {
			if(strlen($this->AbsPath) <= 0) {
				$this->AbsPath = str_replace("Classes/", "", dirname(__FILE__)."/");
			}

			if(!is_resource(self::$DatabaseLink)) {
				$this->DatabaseHost		= Config::$Options["mySQL"]["Host"];
				$this->DatabaseUsername = Config::$Options["mySQL"]["Username"];
				$this->DatabasePassword = Config::$Options["mySQL"]["Password"];
				$this->DatabaseName		= Config::$Options["mySQL"]["Database"];

				self::$DatabaseLink = mysql_connect($this->DatabaseHost, $this->DatabaseUsername, $this->DatabasePassword, true);

				if(!is_resource(self::$DatabaseLink)) {
					trigger_error("Unable to connect to Database; mySQL said : ".mysql_error(), E_USER_WARNING);
					return;
				}

				if(mysql_select_db($this->DatabaseName, self::$DatabaseLink) == false) {
					trigger_error("Unable to select Database; mySQL said : ".mysql_error(), E_USER_WARNING);
					return;
				}
			}
		}

		//======================================================================
		public function OnLoad($File = "", $Size = "", $Ratio = 1, $AutoCrop = 0, $SizeType = "Auto", $AutoCropPos = "Center Center") {
			//If we do not have any parms, try _GET instead
			if(strlen($File) <= 0) {
				$this->File		= $_GET["CPP_File"]; //The file to resize
				$this->Size		= $_GET["CPP_Size"]; //The new size

				if(isset($_GET["CPP_SizeType"]))	$this->SizeType		= $_GET["CPP_SizeType"];
				if(isset($_GET["CPP_Ratio"]))		$this->Ratio		= $_GET["CPP_Ratio"];
				if(isset($_GET["CPP_AutoCrop"]))	$this->AutoCrop		= $_GET["CPP_AutoCrop"];
				if(isset($_GET["CPP_AutoCropPos"]))	$this->AutoCropPos	= $_GET["CPP_AutoCropPos"];
			}else{
				$this->File			= $File;
				$this->Size			= $Size;
				$this->Ratio		= $Ratio;

				$this->SizeType		= $SizeType;

				$this->AutoCrop		= $AutoCrop;
				$this->AutoCropPos	= $AutoCropPos;
			}

			if(stripos($this->File, "http://") !== false || stripos($this->File, "https://") !== false) {
				$this->IsURL	= true;
				$this->AbsPath	= "";
			}

			$this->Ext = explode(".", $this->File);
			$this->Ext = strtolower(end($this->Ext));

			$this->MimeType = "Content-type: image/";

			switch($this->Ext) {
				case "png":
				case "gif":
				case "jpeg": {
					$this->MimeType .= $this->Ext;
					break;
				}

				case "jpg": {
					$this->MimeType .= "jpeg";
					break;
				}

				default: {
					$Type = exif_imagetype($this->AbsPath.$this->File);

					if($Type == IMAGETYPE_PNG)	{ $this->MimeType .= "png"; $this->Ext = "png"; }
					else if($Type == IMAGETYPE_GIF)	{ $this->MimeType .= "gif"; $this->Ext = "gif"; }
					else if($Type == IMAGETYPE_JPEG) { $this->MimeType .= "jpeg"; $this->Ext = "jpeg"; }
					else {
						$this->MimeType = "jpeg";
						$this->Ext		= "jpeg";
					}

					break;
				}
			}

			//This is the cache image filename
			$this->CacheFile	= md5($this->File)."-".$this->Size."-".$this->SizeType."-".$this->Ratio."-".$this->AutoCrop."-".str_replace(" ", "", $this->AutoCropPos).".".$this->Ext;
			$this->CacheKey		= @sha1_file($this->AbsPath.$this->File);

			$this->AutoCropPos = explode(" ", $this->AutoCropPos);

			$this->OnLoadData();
		}

		//======================================================================
		private function OnLoadData() {
			switch($this->StorageType) {
				case "mySQL": {
					if(is_resource(self::$DatabaseLink)) {
						$Res = mysql_query("SELECT * FROM `".$this->DatabaseTable."` WHERE `CacheFile` = '".mysql_real_escape_string($this->CacheFile, self::$DatabaseLink)."'", self::$DatabaseLink);

						if(mysql_num_rows($Res) <= 0) {
							mysql_query("INSERT INTO `".$this->DatabaseTable."` VALUES ('".mysql_real_escape_string($this->CacheFile, self::$DatabaseLink)."', '".mysql_real_escape_string($this->CacheKey, self::$DatabaseLink)."');", self::$DatabaseLink);
							break;
						}

						$Row = mysql_fetch_assoc($Res);

						$this->CacheKeyStored = $Row["Key"];
					}

					break;
				}

				case "File": {
					if(!file_exists($this->GetCachePath().$this->CacheDat)) {
						$FileHandle = fopen($this->GetCachePath().$this->CacheDat, "w");
						fclose($FileHandle);
					}

					$this->CacheData = unserialize(file_get_contents($this->GetCachePath().$this->CacheDat));

					$this->CacheKeyStored = $this->CacheData[$this->CacheFile];

					break;
				}
			}
		}

		//======================================================================
		private function OnSaveData() {
			if($this->CacheKeyStored == $this->CacheKey) return;

			switch($this->StorageType) {
				case "mySQL": {
					if(is_resource(self::$DatabaseLink)) {
						mysql_query("UPDATE `".$this->DatabaseTable."` SET `Key` = '".mysql_real_escape_string($this->CacheKey, self::$DatabaseLink)."' WHERE `CacheFile` = '".mysql_real_escape_string($this->CacheFile, self::$DatabaseLink)."'", self::$DatabaseLink);
					}

					break;
				}

				case "File": {
					$FileHandle = @fopen($this->GetCachePath().$this->CacheDat, "w");

					@fwrite($FileHandle, serialize($this->CacheData));

					@fclose($FileHandle);

					break;
				}
			}
		}

		//======================================================================
		private function GetCachePath() {
			return $this->AbsPath.$this->CacheFolder;
		}

		//----------------------------------------------------------------------
		public function HasCacheFile() {
			//If this entry doesn't exist in our data file, or has the wrong key, update
			//the cache image
			if($this->CacheKeyStored != $this->CacheKey) {
				if(file_exists($this->GetCachePath().$this->CacheFile)) {
					unlink($this->GetCachePath().$this->CacheFile);
				}

				return false;
			}

			//Does the cache image exist? If so, display it...
			if(!file_exists($this->GetCachePath().$this->CacheFile)) {
				return false;
			}

			return true;
		}

		//=====================================================================
		public static function GetURL($File, $Size, $Ratio = 0, $AutoCrop = 0, $SizeType = "Auto", $AutoCropPos = "Center Center") {
			$CPP_Object = new CPicPreview();
			$CPP_Object->OnLoad($File, $Size, $Ratio, $AutoCrop, $SizeType, $AutoCropPos);

			if($CPP_Object->HasCacheFile()) {
				return "/".str_replace("./", "", $CPP_Object->CacheFolder).$CPP_Object->CacheFile;
			}else{
				return "CPP_".basename($File)."?CPP_File=".urlencode($File)."&CPP_Size=".urlencode($Size)."&CPP_SizeType=".urlencode($SizeType)."&CPP_Ratio=".urlencode($Ratio)."&CPP_AutoCrop=".urlencode($AutoCrop)."&CPP_AutoCropPos=".urlencode($AutoCropPos);
			}
		}

		//=====================================================================
		public static function GetCDNURL($File, $Size, $Ratio = 0, $AutoCrop = 0, $SizeType = "Auto", $AutoCropPos = "Center Center", $CheckCache = true) {
			$Domain = "http".($_SERVER["SERVER_PORT"] == 443 ? "s" : "")."://cdn".(rand() % 5).".yourpromopeople.com/";

			if($CheckCache) {
				$CPP_Object = new CPicPreview();
				$CPP_Object->OnLoad($File, $Size, $Ratio, $AutoCrop, $SizeType, $AutoCropPos);
			}

			if($CheckCache && $CPP_Object->HasCacheFile()) {
				return $Domain.str_replace("./", "", $CPP_Object->CacheFolder).$CPP_Object->CacheFile;
			}else{
				$FakeFilename = str_replace("?", "", basename($File));
				$FakeFilename = str_replace("&", "", $FakeFilename);
				$FakeFilename = str_replace("=", "", $FakeFilename);

				return $Domain."CPP_".$FakeFilename."?CPP_File=".urlencode($File)."&CPP_Size=".urlencode($Size)."&CPP_SizeType=".urlencode($SizeType)."&CPP_Ratio=".urlencode($Ratio)."&CPP_AutoCrop=".urlencode($AutoCrop)."&CPP_AutoCropPos=".urlencode($AutoCropPos);
			}
		}

		//=====================================================================
		function OnRender() {
			if(stripos(strtolower($this->Size), "x") !== false) {
				$Parts = explode("x", strtolower($this->Size));

				$this->Width  = $Parts[0];
				$this->Height = $Parts[1];
			}else{
				$this->Size = doubleval($this->Size);

				$this->Width  = $this->Size;
				$this->Height = $this->Size;
			}

			//-----------------------------------------------------------------
			//If this file doesn't exist, or is a directory, show a blank image instead
			if($this->IsURL) {
			}else
			if(!file_exists($this->AbsPath.$this->File) || is_dir($this->AbsPath.$this->File) == true) {
				$this->MimeType = "Content-type: image/jpeg";

				if(file_exists($this->AbsPath.$this->FileNotFound)) {
					$this->File = $this->FileNotFound;
				}else{
					Header($this->MimeType); //MIME Type
                                        Header('Pragma: cache'); //Allows Outlook to display image
				
					$Surf_New = imagecreatetruecolor($this->Width, $this->Height);

					$White	= imagecolorallocate($Surf_New, 255, 255, 255);
					$Red	= imagecolorallocate($Surf_New, 255, 0, 0);

					imagefill($Surf_New, 0, 0, $White);

					if($this->Debug) {
						if(!file_exists($this->File)) {
							imagestring($Surf_New, 2, 0, 0, "File not Found: ".$this->File, $Red);
						}else
						if(is_dir($this->File) == true) {
							imagestring($Surf_New, 2, 0, 0, "File is a Directory", $Red);
						}else{
							imagestring($Surf_New, 2, 0, 0, "Unknown error", $Red);
						}
					}

					imagejpeg($Surf_New, null, 100); 

					return;
				}
			}

			//-----------------------------------------------------------------
			if($this->HasCacheFile()) {
				Header($this->MimeType); //MIME Type
                                Header('Pragma: cache'); //Allows Outlook to display image

				echo file_get_contents($this->GetCachePath().$this->CacheFile);

				return;
			}

			//-----------------------------------------------------------------
			//... otherwise, show the original, and cache the image
			Header($this->MimeType); //MIME Type
                        Header('Pragma: cache'); //Allows Outlook to display image

			$PictureSize	= getimagesize($this->AbsPath.$this->File);
			$PictureWidth	= $PictureSize[0];
			$PictureHeight	= $PictureSize[1];

			//Prevent a Divide by Zero
			if($PictureWidth <= 0)	$PictureWidth	= 1;
			if($PictureHeight <= 0)	$PictureHeight	= 1;

			if($this->SizeType != "Width" && $this->SizeType != "Height") {
				if($this->SizeType == "Smallest") {
					if($PictureWidth > $PictureHeight) {
						$this->SizeType = "Height";
					}else{
						$this->SizeType = "Width";
					}
				}else{
					if($PictureWidth > $PictureHeight) {
						$this->SizeType = "Width";
					}else{
						$this->SizeType = "Height";
					}
				}
			}

			//Are we resizing to a Ratio?
			if($this->Ratio > 0) {
				if($this->SizeType === "Width") {
					$Perc		= $PictureHeight / $PictureWidth;

					$NewWidth	= $this->Width;
					$NewHeight	= $this->Height * $Perc;

				}else
				if($this->SizeType === "Height") {
					$Perc		= $PictureWidth / $PictureHeight;

					$NewWidth	= $this->Width * $Perc;
					$NewHeight	= $this->Height;
				}
			//If not, both width and height will be the same size
			}else{
				$NewWidth	= $this->Width;
				$NewHeight	= $this->Height;
			}

			//Grab the picture
			switch($this->Ext) {
				case "png": {
					$Surf_Old = imagecreatefrompng($this->AbsPath.$this->File);
					break;
				}

				case "gif": {
					$Surf_Old = imagecreatefromgif($this->AbsPath.$this->File);
					break;
				}

				case "jpeg":
				case "jpg": {
					$Surf_Old = imagecreatefromjpeg($this->AbsPath.$this->File);
					break;
				}
			}

			imagealphablending($Surf_Old, false);
			imagesavealpha($Surf_Old, true);

			//Turn on Anti Aliasing
			if(function_exists("imageantialias")) {
				imageantialias($Surf_Old, true);
			}

			//Create a blank surface with the new width and height
			$Surf_New = imagecreatetruecolor($NewWidth, $NewHeight);

			imagealphablending($Surf_New, true);
			imagesavealpha($Surf_New, true);

			$White = imagecolorallocateAlpha($Surf_New, 255, 255, 255, 127);
			imagefill($Surf_New, 0, 0, $White);

			if($this->AutoCrop) {
				$X = 0;
				$Y = 0;				

				$CopyWidth	= $NewWidth;
				$CopyHeight = $NewHeight;

				if($this->SizeType === "Width") {
					$CopyHeight = ($PictureHeight / $PictureWidth) * $NewWidth;

					if(@$this->AutoCropPos[1] == "Top")		$Y = 0;
					if(@$this->AutoCropPos[1] == "Center")	$Y = ($NewHeight - $CopyHeight) / 2;
					if(@$this->AutoCropPos[1] == "Bottom")	$Y = ($NewHeight - $CopyHeight);
				}else 
				if($this->SizeType === "Height") {
					$CopyWidth = ($PictureWidth / $PictureHeight) * $NewHeight;					

					if(@$this->AutoCropPos[0] == "Left")		$X = 0;
					if(@$this->AutoCropPos[0] == "Center")	$X = ($NewWidth - $CopyWidth) / 2;
					if(@$this->AutoCropPos[0] == "Right")	$X = ($NewWidth - $CopyWidth);
				}			

				ImageCopyResampled($Surf_New, $Surf_Old, $X, $Y, 0, 0, $CopyWidth, $CopyHeight, $PictureWidth, $PictureHeight);
			}else{
				ImageCopyResampled($Surf_New, $Surf_Old, 0, 0, 0, 0, $NewWidth, $NewHeight, $PictureWidth, $PictureHeight);
			}

			switch($this->Ext) {
				case "gif":
				case "png": {
					imagepng($Surf_New, null, 9); 
					imagepng($Surf_New, $this->GetCachePath().$this->CacheFile, 9); 
					break;
				}

				case "jpeg":
				case "jpg": {
					imagejpeg($Surf_New, null, 100); 
					imagejpeg($Surf_New, $this->GetCachePath().$this->CacheFile, 100);
					break;
				}
			}

			$this->OnSaveData();
		}
	};

	//==========================================================================
	// Alias
	//==========================================================================
	class CPP extends CPicPreview {};

	//==========================================================================
	if(isset($_GET["CPP_File"]) && isset($_GET["CPP_Size"])) {
		$CPP_Object = new CPP();
		$CPP_Object->OnLoad();
		$CPP_Object->OnRender();
		die();
	}

	//==========================================================================
?>