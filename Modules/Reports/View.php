<?
	//	ini_set("display_errors", "On");
	//	error_reporting(E_ALL);

	$Report = $this->Parent->TableObject;

	echo CBox::PageTitle("Run Report // ".$Report->Name);

	if($_POST["Submit"]) {
		if($Report->Type == "Custom") {
			if(($CustomReport = $Report->GetCustomObject()) === false) {
				echo "Bad Report";
			}else{
				if(($Return = $CustomReport->OnRun($_POST)) === false) {
					echo CBox::Error("There was an error running this Report, please click back and try again.");
				}else{
					list($Filename, $TempFilename) = $Return;

					list(CData::$PathData, CData::$PathTemp) = Array(CData::$PathTemp, CData::$PathData);

					CData::OutputFile("./", $TempFilename, $Filename);

					list(CData::$PathData, CData::$PathTemp) = Array(CData::$PathTemp, CData::$PathData);
				}
			}
		}else{
		}
	}else{
		echo CBox::Start("Options");
		echo "<form method='post'>";
		echo "<center>";
		echo "<br/>";
		echo "<table style='width: 40%;' align='center'>";

		if($Report->Type == "Custom") {
			if(($CustomReport = $Report->GetCustomObject()) === false) {
				echo "Bad Report";
			}else{
				$Options = $CustomReport->GetOptions();

				foreach($Options as $Option) {
					switch($Option["Type"]) {
						case "Choice": {
							echo CForm::AddDropdown($Option["Label"], $Option["Name"], $Option["Values"], "");
							break;
						}

						case "Timestamp": {
							echo CForm::AddTimestamp($Option["Label"], $Option["Name"], time());
							break;
						}
					}
				}
			}
		}else{
			echo CForm::AddTimestamp("Start Timestamp", "StartTimestamp", $Report->StartTimestamp);
			echo CForm::AddTimestamp("End Timestamp", "EndTimestamp", $Report->EndTimestamp);
		}

		echo "</table>";
		echo "<br/><input type='submit' name='Submit' value='Submit'/><br/><br/>";
		echo "</center>";
		echo "</form>";
		echo CBox::End();
	}
?>