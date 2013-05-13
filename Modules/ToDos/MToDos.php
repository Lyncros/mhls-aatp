<?
	//==========================================================================
	/*
		[Permissions]
		Customers
		AddEdit
		Delete
		Window_AddEdit
		Window_Delete
		GetUserAutocomplete
		[-]
	*/
	//==========================================================================
	class MToDos extends CModuleGeneric {
		function __construct() {
			$this->Table		= "ToDos";
			$this->Classname	= "CToDos";

			parent::__construct();
		}

		//----------------------------------------------------------------------
		function OnExecute() {
			return parent::OnExecute();
		}

		//----------------------------------------------------------------------
		function OnRenderJS() { 
			$this->FileControl->LoadFile("MToDos.js", CFILE_TYPE_JS);
		}

		//----------------------------------------------------------------------
		function OnRenderCSS() { 
			$this->FileControl->LoadFile("style.css", CFILE_TYPE_CSS);
		}

		//----------------------------------------------------------------------
		function OnRender() {
			$Page = $_GET["Page"];

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

			//CTable::Add/Update automatically escapes
			$Data = Array(
				"Name"						=> htmlspecialchars($_POST["Name"]),
				"Comment"					=> htmlspecialchars($_POST["Comment"]),
				"CommentRequired"			=> intval($_POST["CommentRequired"]),
				"Active"					=> intval($_POST["Active"]),
				"ModifiedUsersID"			=> CSecurity::GetUsersID(),
				"ModifiedIPAddress"			=> $_SERVER["REMOTE_ADDR"],
			);

			if($ID > 0) {
				if(CTable::Update($this->Table, $ID, $Data) === false) {
					return Array(0, "Unable to update record, please try again");
				}
			}else{
				$Data["Created"]			= date("Y-m-d H:i:s");
				$Data["CreatedUsersID"]		= CSecurity::GetUsersID();
				$Data["CreatedIPAddress"]	= $_SERVER["REMOTE_ADDR"];

				if(($ID = CTable::Add($this->Table, $Data)) === false) {
					return Array(0, "Unable to add record, please try again");
				}
			}

			return Array($ID, "Record successfully entered / updated.");
		}

		//----------------------------------------------------------------------
		function Delete() {
			return parent::Delete();
		}
	};

	//==========================================================================
?>
