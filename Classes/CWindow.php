<?
	//==========================================================================
	/*
		Basic functions regarding AJAX Popup Windows (See CWindow.js)

		4/10/2009
	*/
	//==========================================================================
	class CWindow extends CAJAX {
		public function OnAJAX($Action) {
			if($Action == "SaveWindow") {
				return self::SaveWindow();
			}else
			if($Action == "UnsaveWindow") {
				return self::UnsaveWindow();
			}
		}

		public function SaveWindow() {
			$WindowID	= $_POST["WindowID"];
			$Title		= $_POST["Title"];
			$Content	= $_POST["Content"];
			$Width		= $_POST["Width"];
			$Height		= $_POST["Height"];

			if($WindowID >= 0) {
				return Array(1, $WindowID);
			}else{
				$Settings = new CSettings();
				$Settings->OnLoad("Windows", "System");

				$Windows = self::GetSavedWindows();

				$Windows[] = Array(
					"Title"		=> $Title,
					"Content"	=> $Content,
					"Width"		=> $Width,
					"Height"	=> $Height
				);

				$Settings->SetValue("Windows", serialize($Windows));

				end($Windows);

				return Array(1, key($Windows));
			}
		}

		public function UnsaveWindow() {
			$WindowID = $_POST["WindowID"];

			$Settings = new CSettings();
			$Settings->OnLoad("Windows", "System");

			$Windows = self::GetSavedWindows();

			unset($Windows[$WindowID]);

			$Settings->SetValue("Windows", serialize($Windows));

			return Array(1, 0);
		}

		public static function GetSavedWindows() {
			$Settings = new CSettings();
			$Settings->OnLoad("Windows", "System");

			$Windows = unserialize($Settings->GetValue("Windows"));

			return $Windows;
		}

		public static function TabsInit($WindowID, $Tabs) {
			CTabs::Init("CWindow_".$WindowID."_Tab_Area", $Tabs);
		}

		public static function TabsDone() {
			CTabs::Done();
		}

		public static function TabStart() {
			CTabs::TabStart();
		}

		public static function TabEnd() {
			CTabs::TabEnd();
		}

		public static function SetTitle($WindowID, $Title) {
			echo "<input type='hidden' id=\"CWindow_$WindowID"."_NewTitle\" value=\"".str_replace("\"", "\\\"", $Title)."\"/>";
		}
	}

	//==========================================================================
?>
