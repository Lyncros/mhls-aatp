<?
	//==========================================================================
	/*
		Table for Help

		8/13/2010 7:36 AM
	*/
	//==========================================================================
	class CHelp extends CTable {
		public $Ratings = Array();

		function __construct() {
			$this->Table = "Help";
		}

		function OnLoad($ID) {
			if(parent::OnLoadByID($ID) === false) {
				trigger_error("Unable to load (".$this->Table.") : $ID", E_USER_NOTICE);
				return false;
			}

			return $this->OnInit();
		}

		function OnLoadByPage($Page) {
			$Page = mysql_real_escape_string($Page);

			if(parent::OnLoadAll("WHERE `Page` = '$Page'") === false) {
				trigger_error("Unable to load (".$this->Table.") : $ID", E_USER_NOTICE);
				return false;
			}

			return $this->OnInit();
		}

		function OnInit() {
			$this->Ratings = Array();

			$TempTable = new CHelpRating();
			if($TempTable->OnLoadAll("WHERE `HelpID` = ".intval($this->ID)." ORDER BY `Timestamp` DESC") !== false) {
				foreach($TempTable->Rows as $Row) {
					$Item = new CHelpRating();
					if($Item->OnLoad($Row->ID) == false) continue;

					$this->Ratings[$Row->ID] = $Item;
				}
			}

			return true;
		}

		function OnDelete() {
			foreach($this->Ratings as $Rating) {
				$Rating->OnDelete();
			}

			return CTable::OnDelete();
		}

		//----------------------------------------------------------------------
		function GetRating() {
			if(count($this->Ratings) <= 0) {
				return -1;
			}

			$Total = 0;

			foreach($this->Ratings as $Rating) {
				$Total += $Rating->Rating;
			}

			return $Total / count($this->Rating);
		}
	};

	//==========================================================================
?>
