<?
	//==========================================================================
	/*
		Table for Reports

		5/12/2010 10:14 AM
	*/
	//==========================================================================
	class CReports extends CTable {
		public $Options = Array();
		public $Type	= "";

		protected $NumRows = 0;

		function __construct() {
			$this->Table = "Reports";
		}

		function OnLoad($ID) {
			if(parent::OnLoadByID($ID) === false) {
				return $this->CreateNew();
			}

			$this->Options = Array();

			$RowTable = new CReportsOptions();
			if($RowTable->OnLoadAll("WHERE `ReportsID` = ".intval($this->ID)) !== false) {
				foreach($RowTable->Rows as $Row) {
					$Item = new CReportsOptions();
					if($Item->OnLoad($Row->ID) == false) continue;

					$this->Options[$Row->ID] = $Item;
				}
			}

			unset($RowTable);

			return true;
		}

		private function CreateNew() {
			$Data = Array(
				"Timestamp" => time(),
				"IP"		=> $_SERVER["REMOTE_ADDR"],
				"UsersID"	=> CSecurity::GetUsersID(),
				"Name"		=> CFormat::SplitByCaps($this->Type),
				"Type"		=> $this->Type
			);

			$ID = CTable::Add("Reports", $Data);

			foreach($_GET as $Key => $Value) {
				if($Key == "ID")		continue;
				if($Key == "Report")	continue;

				CTable::Add("ReportsOptions", Array(
					"ReportsID" => $ID,
					"Key"		=> $Key,
					"Value"		=> $Value
				));
			}

			CData::CreateFile("Reports", $ID.".db", "");

			$this->OnLoad($ID);
			$this->CreateDB();
			$this->Run();

			CTable::Update("Reports", $ID, Array("NumberOfRows" => $this->NumRows));

			return true;
		}

		protected function CreateDB() {
		}

		protected function Run() {
		}

		protected function GetRows() {
			return false;
		}

		protected function GetNumRows() {
			return $this->NumRows;
		}

		function GetFilename() {
			return CData::FileExists("Reports", $this->ID.".db");
		}

		function GetOptionValue($Key) {
			foreach($this->Options as $Option) {
				if($Option->Key == $Key) return $Option->Value;
			}

			return false;
		}
	};

	//==========================================================================
?>
