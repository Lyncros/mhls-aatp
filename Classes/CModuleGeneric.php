<?
	//==========================================================================
	/*
		Generic Module class Setup demonstrating a basic Module setup (used by
		several system modules)

		Permissions demonstrate how to setup Action Access permissions (used by
		UsersGroupsPermissions and CSecurity)

		4/20/2009
	*/
	//==========================================================================
	/*
		[Permissions]
		AddEdit
		Delete
		Window_AddEdit
		Window_Delete
		[-]
	*/
	//==========================================================================
	class CModuleGeneric extends CModule {
		public $Table		= "";
		public $Classname	= "";

		function __construct() {
			parent::__construct();

			//$this->IsSecure = true;

			$ID = isset($_GET["ID"]) ? intval($_GET["ID"]) : intval(@$_POST["ID"]);

			if($ID > 0 && strlen($this->Classname) >= 0) {
				$this->TableObject = new $this->Classname();

				if($this->TableObject->OnLoad($ID) === false) {
					$this->TableObject = null;
				}
			}else{
				$this->TableObject = null;
			}
		}

		//----------------------------------------------------------------------
		function OnExecute() {
			return parent::OnExecute();
		}

		//----------------------------------------------------------------------
		function OnRender() {
			$this->ThemeControl->FileControl->LoadFile("header.php");

			if($this->TableObject == null) {
				$this->FileControl->LoadFile("Render.php");
			}else{
				$this->FileControl->LoadFile("View.php");
			}

			$this->ThemeControl->FileControl->LoadFile("footer.php");
		}

		//----------------------------------------------------------------------
		function OnAJAX($Action) {
			if(parent::CanAccess($Action) == false) {
				return Array(0, "You do not have permission to perform this action");
			}

			if(CSecurity::IsAdmin($this->Name)) {
				if($Action === "AddEdit") {
					return $this->AddEdit();
				}else
				if($Action === "Delete") {
					return $this->Delete();
				}else
				if($Action === "Window_AddEdit") {
					return Array(1, $this->FileControl->LoadFile("AddEdit.php", CFILE_TYPE_CONTENTS_OB));
				}else
				if($Action === "Window_Delete") {
					return Array(1, $this->FileControl->LoadFile("Delete.php", CFILE_TYPE_CONTENTS_OB));
				}
			}

			return Array(1, "Action not Found");
		}

		//=====================================================================
		function AddEdit() {
			return Array(1, "");
		}

		//----------------------------------------------------------------------
		function Delete() {
			$ID = intval($_POST["ID"]);

			if($ID > 0) {
				if($this->TableObject) {
					if($this->TableObject->OnDelete()) {
						return Array($ID, "Record successfully removed.");
					}
				}
			}

			return Array(0, "Unable to remove record, please try again.");
		}
	};

	//==========================================================================
?>
