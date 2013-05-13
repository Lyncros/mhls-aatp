<?
	//==========================================================================
	/*
		[Permissions]
		[-]
	*/
	//=========================================================================
	class MResources extends CModuleGeneric {
		public $Table = "Resources";

		function __construct() {
			$this->Table		= "Resources";
			$this->Classname	= "CResources";

			parent::__construct();
		}

		//---------------------------------------------------------------------
		function OnExecute() {
			if(@$_GET["Action"] == "DownloadFile") {
				return $this->DownloadFile();
			}

			return parent::OnExecute();
		}

		//---------------------------------------------------------------------
		function OnRenderJS() { 
			$this->FileControl->LoadFile("MResources.js", CFILE_TYPE_JS);
		}

		//----------------------------------------------------------------------
		function OnRenderCSS() {
			$this->FileControl->LoadFile("style.css", CFILE_TYPE_CSS);
		}

		//----------------------------------------------------------------------
		function OnRender() {
			parent::OnRender();
		}

		//----------------------------------------------------------------------
		function OnAJAX($Action) {
			if(parent::CanAccess($Action) == false) {
				return Array(0, "You do not have permission to perform this action");
			}

			return parent::OnAJAX($Action);
		}

		//=====================================================================
		function AddEdit() {
			$ID = $_POST["ID"];

			if($_POST["Filename"] == "" && $ID <= 0) {
				return Array(0, "Please upload a valid File");
			}

			//CTable::Add/Update automatically escapes
			$Data = Array(
				"UsersID"			=> CSecurity::$User->ID,
				"TimestampUpdated"	=> time(),
				"IP"				=> $_SERVER["REMOTE_ADDR"],
				"Title"				=> htmlspecialchars($_POST["Title"]),
				"Filename"			=> $_POST["Filename"],
				"FilenameOriginal"	=> $_POST["FilenameOriginal"],
				"Active"			=> intval($_POST["Active"])
			);

			if($_POST["Filename"] == "") {
				unset($Data["Filename"]);
				unset($Data["FilenameOriginal"]);
			}else{
				CData::AddUploadedTempFile(CRESOURCES_DATA_PATH, $_POST["Filename"]);
			}

			if($ID > 0) {
				CTable::Update("Resources", $ID, $Data);
			}else{
				$Data["Timestamp"] = time();

				CTable::Add("Resources", $Data);
			}

			return Array(1, "Resource successfully ".($ID > 0 ? "edited" : "added").".");
		}

		//----------------------------------------------------------------------
		function Delete() {
			// Don't actually Delete the File. Thanks.

			return parent::Delete();
		}

		//----------------------------------------------------------------------
		function DownloadFile() {
			$Data = Array(
				"ResourcesID"		=> $this->TableObject->ID,
				"UsersID"			=> CSecurity::$User->ID,
				"Timestamp"			=> time(),
				"IP"				=> $_SERVER["REMOTE_ADDR"],
				"Filename"			=> $this->TableObject->Filename,
				"FilenameOriginal"	=> $this->TableObject->FilenameOriginal
			);

			CTable::Add("ResourcesAccess", $Data);

			CData::OutputFile(CRESOURCES_DATA_PATH, $this->TableObject->Filename, $this->TableObject->FilenameOriginal, true, true);
			die();
		}
	};

	//=========================================================================
?>
