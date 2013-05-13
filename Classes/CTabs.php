<?
	//==========================================================================
	/*
		PHP jQuery wrapper for creating Tabs

		There really needs to be some sort of jQuery UI wrapper class, which
		holds CTabs, and CWindow methods

		4/10/2009
	*/
	//==========================================================================
	class CTabs {
		static $JID		= "";
		static $Count	= 0;

		public static function Init($JID, $Tabs) {
			CTabs::$JID = $JID;

			echo "<div id=\"$JID\" style=\"width: 100%;\">";
			echo "<ul>";

			$i = 0;
			foreach($Tabs as $Tab) {
				echo "<li><a href=\"#$JID"."$i\">$Tab</a></li>";
				++$i;
			}
			echo "</ul>";

			CTabs::$Count = 0;
		}

		public static function InitJS() {
			echo '<script type="text/javascript">';
			echo '$("#'.CTabs::$JID.'").tabs({fx: {opacity: "toggle"} });';
			echo '</script>';
		}

		public static function Done($InitJS = false) {
			echo "</div>";

			if($InitJS) {
				CTabs::InitJS();
			}
		}

		public static function TabStart() {
			$JID = CTabs::$JID;

			echo "<div id=\"$JID".CTabs::$Count."\">";

			++CTabs::$Count;
		}

		public static function TabEnd() {
			echo "</div>";
		}
	}

	//==========================================================================
?>
