<?
	//==========================================================================
	/*
		A service class that delegates certain tasks for cron operations

		4/10/2009
	*/
	//==========================================================================
	class CCron extends CAJAX {
		function __construct() {
		}

		//----------------------------------------------------------------------
		function OnAJAX($Action) {
			if($Action == "Run") {
				return $this->OnRun();
			}

			return Array(1, "");
		}

		//----------------------------------------------------------------------
		function OnRun() {
			//------------------------------------------------------------------
			// Data
			//------------------------------------------------------------------
			$Timestamp = CSystem::GetValue("CronDataTimestamp");

			if($Timestamp + (60 * 60) < time()) {
				CData::OnCron();

				CSystem::SetValue("CronDataTimestamp", time());
			}

			CNotifier::OnCron();
			CAuthorizations::OnCron();
			CProgressReports::OnCron();

			return Array(1, "");
		}
	};

	//==========================================================================
?>
