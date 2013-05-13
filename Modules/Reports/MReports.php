<?
	//==========================================================================
	class MReports extends CModuleGeneric {
		public $Report = "";

		function __construct() {
			$this->Table		= "Reports";
			$this->Classname	= "CReports";

			parent::__construct();
		}

		//----------------------------------------------------------------------
		function OnExecute() {
			if(CSecurity::CanAccess($this->Name, "AddEdit")) {
				$this->Actions["New Report"] = Array("OnClick" => "MReports.Window_AddEdit(0);", "Icon" => "Icon_Popup");
			}

			$this->Actions["Reports"] = "CModule.Load('Reports', null);";

			if((!isset($_POST["Action"]) || $_POST["Action"] == "") && $this->TableObject->Public == 1) {
				return true;
			}

			$ReportList = Array(
				"Payroll",
				"PayrollProfile",
				"TimeEntry"
			);

			$this->Report = $_GET["Report"];
			if(in_array($this->Report, $ReportList) == false) $this->Report = "";

			return parent::OnExecute();
		}

		//----------------------------------------------------------------------
		function OnRenderJS() { 
			$this->FileControl->LoadFile("MReports.js", CFILE_TYPE_JS);
		}

		//---------------------------------------------------------------------
		function OnRenderCSS() {
			$this->FileControl->LoadFile("style.css", CFILE_TYPE_CSS);			
		}

		//----------------------------------------------------------------------
		function OnRender() {
			$this->ThemeControl->FileControl->LoadFile("header.php");

			if($this->Report != "") {
				$this->FileControl->LoadFile("Report-".$this->Report.".php");
			}else{
				$this->FileControl->LoadFile("Render.php");
			}

			$this->ThemeControl->FileControl->LoadFile("footer.php");
		}

		//----------------------------------------------------------------------
		function OnAJAX($Action) {
			if($Action == "") return Array(1, "");

			return parent::OnAJAX($Action);
		}

		//======================================================================
		function AddEdit() {
			$ID = $_POST["ID"];

			//CTable::Add/Update automatically escapes
			$Data = Array(
				"BusinessesID"	=> intval(CSecurity::GetBusinessesID()),
				"Type"			=> $_POST["Type"],
				"Name"			=> htmlspecialchars($_POST["Name"]),
				"Public"		=> 0
			);

			if(CSecurity::IsSuperAdmin()) {
				$Data["Public"] = intval($_POST["Public"]);
			}

			if($ID > 0) {
				if(CTable::Update($this->Table, $ID, $Data) === false) {
					return Array(0, "Unable to add record to database, please try again.");
				}
			}else{
				if(($ID = CTable::Add($this->Table, $Data)) === false) {
					return Array(0, "Unable to add record to database, please try again.");
				}
			}

			return Array($ID, "Record successfully entered / updated.");
		}

		//----------------------------------------------------------------------
		function Delete() {
			$ID = $_POST["ID"];

			CTable::Update("Reports", $ID, Array("Deleted" => 1));

			return Array(1, "Report successfully Deleted");
		}
	};

	//==========================================================================
?>
