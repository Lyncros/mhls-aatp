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
	class MMilestones extends CModuleGeneric {
		function __construct() {
			$this->Table		= "Milestones";
			$this->Classname	= "CMilestones";

			parent::__construct();
		}

		//----------------------------------------------------------------------
		function OnExecute() {
			return parent::OnExecute();
		}

		//----------------------------------------------------------------------
		function OnRenderJS() { 
			$this->FileControl->LoadFile("MMilestones.js", CFILE_TYPE_JS);
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
				"CustomerApproval"			=> intval($_POST["CustomerApproval"]),
				"Summary"					=> htmlspecialchars($_POST["Summary"]),
				"ExpectedDeliveryDate"		=> htmlspecialchars($_POST[""]),
				"ActualDeliveryDate"		=> htmlspecialchars($_POST[""]),				
				"PlantAllocated"			=> htmlspecialchars($_POST["PlantAllocated"]),
				"ToDosLists"				=> ($_POST["ToDosLists"])?serialize(json_decode($_POST["ToDosLists"])):"",
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
