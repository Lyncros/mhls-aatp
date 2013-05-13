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
	class MUsersGroups extends CModuleGeneric {
		function __construct() {
			$this->Table		= "UsersGroups";
			$this->Classname	= "CUsersGroups";

			parent::__construct();
		}

		//----------------------------------------------------------------------
		function OnExecute() {
			return parent::OnExecute();
		}

		//----------------------------------------------------------------------
		function OnRenderJS() { 
			$this->FileControl->LoadFile("MUsersGroups.js", CFILE_TYPE_JS);
		}

		//----------------------------------------------------------------------
		function OnRenderCSS() { 
			$this->FileControl->LoadFile("style.css", CFILE_TYPE_CSS);
		}

		//----------------------------------------------------------------------
		function OnRender() {
			$Page = $_GET["Page"];

			if($Page == "Add") {
				$this->ThemeControl->FileControl->LoadFile("header.php");
				$this->FileControl->LoadFile("View.php");			
				$this->ThemeControl->FileControl->LoadFile("footer.php");
			}else{
				parent::OnRender();
			}
		}

		//----------------------------------------------------------------------
		function OnAJAX($Action) {
			if(parent::CanAccess($Action) == false) {
				return Array(0, "You do not have permission to perform this action");
			}

			if($Action == "Save") {
				return $this->Save();
			}

			return parent::OnAJAX($Action);
		}

		//=====================================================================
		function AddEdit() {
		}

		//----------------------------------------------------------------------
		function Delete() {
			CTable::RunQuery("DELETE FROM `UsersGroupsConnections` WHERE `UsersGroupsID` = ".intval($_POST["ID"]));

			return parent::Delete();
		}

		//----------------------------------------------------------------------
		function Save() {
			$ID	= intval($_POST["ID"]);

			$Data = Array(
				"TimestampUpdated"	=> time(),
				"Type"				=> "Normal",
				"Name"				=> htmlspecialchars($_POST["Name"]),
				"Active"			=> intval($_POST["Active"])
			);

			if($ID <= 0) {
				$Data["Timestamp"] = time();

				$ID = CTable::Add("UsersGroups", $Data);
			}else{
				CTable::Update("UsersGroups", $ID, $Data);
			}

			CTable::RunQuery("DELETE FROM `UsersGroupsConnections` WHERE `UsersGroupsID` = ".$ID);

			$UserList = json_decode($_POST["UserList"]);
			if(count($UserList) > 0) {
				foreach($UserList as $UsersID) {
					$Data = Array(
						"UsersID"		=> $UsersID,
						"UsersGroupsID" => $ID
					);

					CTable::Add("UsersGroupsConnections", $Data);
				}
			}

			return Array($ID, "User Group saved.");
		}
	};

	//==========================================================================
?>
