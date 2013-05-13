<?
	//==========================================================================
	/*
		[Permissions]
		[-]
	*/
	//=========================================================================
	class MAlerts extends CModuleGeneric {
		public $Table = "Alerts";

		function __construct() {
			$this->Table		= "Alerts";
			$this->Classname	= "CAlerts";

			parent::__construct();
		}

		//---------------------------------------------------------------------
		function OnExecute() {
			return parent::OnExecute();
		}

		//---------------------------------------------------------------------
		function OnRenderJS() { 
			$this->FileControl->LoadFile("MAlerts.js", CFILE_TYPE_JS);
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

			if($Action == "SetRead") {
				return $this->SetRead();
			}else
			if($Action == "SetHidden") {
				return $this->SetHidden();
			}

			return parent::OnAJAX($Action);
		}

		//=====================================================================
		function AddEdit() {
			$ID = $_POST["ID"];

			if($ID > 0) {
				$UID		= $this->TableObject->UID;
				$Timestamp	= ($_POST["UpdateTimestamp"] ? time() : $this->TableObject->Timestamp);
			}else{
				$UID		= md5(microtime(true)."FUNTIME!");
				$Timestamp  = time();
			}

			//CTable::Add/Update automatically escapes
			$Data = Array(
				"UID"			=> $UID,
				"Timestamp"		=> $Timestamp,
				"IP"			=> $_SERVER["REMOTE_ADDR"],
				"Title"			=> htmlspecialchars($_POST["Title"]),
				"Content"		=> htmlspecialchars($_POST["Content"])
			);

			if($_POST["MarkUnread"]) {
				$Data["Read"]	= 0;
				$Data["Hidden"] = 0;
			}

			$EmailList  = Array();
			$UserList	= Array();

			$EmailList[CSecurity::$User->Email] = CSecurity::$User->Email;
			$UserList[CSecurity::$User->ID]		= CSecurity::$User->ID;

			//------------------------------------------------------------------
			// Groups
			//------------------------------------------------------------------
			$GroupList = json_decode($_POST["GroupList"]);

			foreach($GroupList as $GroupID) {
				$UserGroup = new CUsersGroups();
				if($UserGroup->OnLoadByID($GroupID) !== false) {
					$UserGroup->OnLoadConnections();

					foreach($UserGroup->Connections as $Connection) {
						$User = new CUsers();
						if($User->OnLoadByID($Connection->UsersID) === false) continue;

						$EmailList[$User->Email]	= $User->Email;
						$UserList[$User->ID]		= $User->ID;
					}
				}
			}

			//------------------------------------------------------------------
			// Providers
			//------------------------------------------------------------------
			if($_POST["SendToProviders"]) {
				$Users = new CUsers();
				if($Users->OnLoadAll("WHERE `Type` = 'Provider'") !== false) {
					foreach($Users->Rows as $Row) {
						$EmailList[$Row->Email] = $Row->Email;
						$UserList[$Row->ID]		= $Row->ID;
					}
				}
			}

			//------------------------------------------------------------------
			// Admins
			//------------------------------------------------------------------
			if($_POST["SendToAdmins"]) {
				$Users = new CUsers();
				if($Users->OnLoadAll("WHERE `Type` = 'Admin'") !== false) {
					foreach($Users->Rows as $Row) {
						$EmailList[$Row->Email] = $Row->Email;
						$UserList[$Row->ID]		= $Row->ID;
					}
				}
			}

			//------------------------------------------------------------------
			// Add Alerts to each User / Send out Emails
			//------------------------------------------------------------------
			foreach($UserList as $UsersID) {
				if($ID > 0) {
					$Alert = new CAlerts();
					if($Alert->OnLoadAll("WHERE `UID` = '".mysql_real_escape_string($UID)."' && `UsersID` = ".intval($UsersID)) !== false) {
						CTable::Update("Alerts", $Alert->ID, $Data);
					}else{
						$Data["UsersID"] = $UsersID;
						CTable::Add("Alerts", $Data);
					}
				}else{
					$Data["UsersID"] = $UsersID;
					CTable::Add("Alerts", $Data);
				}
			}

			if($_POST["SendEmail"]) {
				foreach($EmailList as $Email) {
					if($Email == "") continue;

					CNotifier::PushEmail($Email, "Module", "Alerts", "Alert", $Data);
				}
			}

			return Array(1, "Alert successfully ".($ID > 0 ? "edited" : "added").".");
		}

		//----------------------------------------------------------------------
		function Delete() {
			CTable::RunQuery("DELETE FROM `Alerts` WHERE `UID` = '".mysql_real_escape_string($this->TableObject->UID)."'");

			return Array(1, "Alert successfully deleted.");
		}

		//----------------------------------------------------------------------
		function SetRead() {
			$Read = intval($_POST["Read"]);

			if($this->TableObject->UsersID != CSecurity::GetUsersID()) {
				return Array(0, "Sorry, there was an unexpected error. Please try again. 1");
			}

			if(CTable::Update("Alerts", $this->TableObject->ID, Array("Read" => $Read)) === false) { 
				return Array(0, "Sorry, there was an unexpected error. Please try again. 2");
			}

			return Array(1, "Success");
		}

		//----------------------------------------------------------------------
		function SetHidden() {
			$Hidden = intval($_POST["Hidden"]);

			if($this->TableObject->UsersID != CSecurity::GetUsersID()) {
				return Array(0, "Sorry, there was an unexpected error. Please try again. 1");
			}

			if(CTable::Update("Alerts", $this->TableObject->ID, Array("Hidden" => $Hidden)) === false) { 
				return Array(0, "Sorry, there was an unexpected error. Please try again. 2");
			}

			return Array(1, "Success");
		}
	};

	//=========================================================================
?>
