<?php
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
	class MMilestoneList extends CTemplateModule {
		function __construct() {
			$this->Table		= "Vendors";
			$this->Classname	= "CVendors";

			parent::__construct("./Modules/MilestoneList/Views");
		}

		//----------------------------------------------------------------------
		function OnExecute() {
			return parent::OnExecute();
		}

		//----------------------------------------------------------------------
		function OnRenderJS() { 
			$this->FileControl->LoadFile("MVendors.js", CFILE_TYPE_JS);
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
						
			$Data = Array(
				"Name"				=> CFormat::SpecialChars($_POST["Name"]),
				"Modified"				=> time(),
				"ModifiedUsersID"		=> CSecurity::GetUsersID(),
				"ModifiedIPAddress"		=> $_SERVER["REMOTE_ADDR"]		
			);

			if($ID > 0) {				
				if(CTable::Update($this->Table, $ID, $Data) === false) {
					return Array(0, "Unable to update record, please try again");
				}
			}else{				
				$Data["Created"]			= time();
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
