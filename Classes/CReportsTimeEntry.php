<?
	//==========================================================================
	/*
		Time Entry Report

		10/24/2011 7:42 AM
	*/
	//==========================================================================
	class CReportsTimeEntry extends CReports {
		function __construct() {
			parent::__construct();

			$this->Type = "TimeEntry";
		}

		protected function CreateDB() {
			$Filename = $this->GetFilename();

			try {
				$SQLHandle = new PDO("sqlite:".$Filename);

				$SQLHandle->beginTransaction();

				$SQLHandle->exec('
					CREATE TABLE `Report` (
						`ID` INTEGER PRIMARY KEY, 
						`TimeEntriesID` INTEGER,

						`Name` TEXT,
						`Date` TEXT,
						`PatientsName` TEXT,
						`Minutes` INTEGER,
						`CPTCodes` TEXT,
						`AuthorizationNumber` TEXT
				)');

				$SQLHandle->exec('CREATE INDEX `NameIndex` ON Report(`Name` ASC)');
				$SQLHandle->exec('CREATE INDEX `DateIndex` ON Report(`Date` ASC)');

				$SQLHandle->commit();

				unset($SQLHandle);
			}
			catch(Exception $e) {
				echo $e->getMessage()." :: ".$Filename;
			}
		}

		protected function Run() {
			$Filename = $this->GetFilename();

			$this->NumRows = 0;

			$StartDate	 = date("Y-m-d", strtotime($_GET["StartDate"]));
			$EndDate	 = date("Y-m-d", strtotime($_GET["EndDate"]));
			$MarkEntries = (bool)($_GET["MarkEntries"] == "Yes");
			$ShowMarked  = (bool)($_GET["ShowMarked"] == "Yes");

			try {
				$SQLHandle = new PDO("sqlite:".$Filename);

				$TimeEntries = new CTimeEntries();
				if($TimeEntries->OnLoadAll("WHERE `Date` >= '".$StartDate."' && `Date` <= '".$EndDate."' ".($ShowMarked ? "" : "&& `Report` = 0")." && `Confirmed` = 1") !== false) {
					foreach($TimeEntries->Rows as $Row) {
						$TimeEntries->OnLoadLines();

						$Name		= "Unknown";
						$CPTCodes	= $TimeEntries->GetCPTCodesAsString();

						$User = new CUsers();
						if($User->OnLoadByID($Row->UsersID) !== false) $Name = $User->GetName();

						$SQLHandle->beginTransaction();

						$SQLHandle->exec('
						INSERT INTO `Report` (
							`TimeEntriesID`,
							`Name`,
							`Date`,
							`PatientsName`,
							`Minutes`,
							`CPTCodes`,
							`AuthorizationNumber`
						) VALUES (
							'.$SQLHandle->quote($Row->ID).',
							'.$SQLHandle->quote($Name).',
							'.$SQLHandle->quote($Row->Date).',
							'.$SQLHandle->quote($Row->PatientsFirstName." ".$Row->PatientsLastName).',
							'.$SQLHandle->quote($TimeEntries->GetTotalMinutes()).',
							'.$SQLHandle->quote($CPTCodes).',
							'.$SQLHandle->quote($Row->AuthorizationNumber).'
						)');

						$SQLHandle->commit();

						if($MarkEntries) CTable::Update("TimeEntries", $Row->ID, Array("Report" => "1"));

						$this->NumRows++;
					}
				}

				unset($SQLHandle);
			}
			catch(Exception $e) {
				echo $e->getMessage()." :: ".$Filename;
			}
		}

		function GetRows() {
			$Filename = $this->GetFilename();

			try {
				$SQLHandle = new PDO("sqlite:".$Filename);

				$Iterator = $SQLHandle->query("SELECT * FROM Report ORDER BY Date, Name");
			}
			catch(Exception $e) {
				echo $e->getMessage()." :: ".$Filename;
				return false;
			}

			return $Iterator;
		}
	};

	//==========================================================================
?>
