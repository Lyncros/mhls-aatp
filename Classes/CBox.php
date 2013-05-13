<?
	//==========================================================================
	/*
		A class for generating HTML boxes (alerts, notices, etc)
		
		Could you provide an example use? 
		And reason? other than simplicity, if there is another reason

		It really is here only for simplicity.

		4/10/2009
	*/
	//==========================================================================
	class CBox {
		/**
		 *	
		 *	
		 *	@access private
		 *	@staticvar integer - but a string later
		 */
		static private $LastUID = 0;

		/**
		 *	Return the Opening tags for a box, and optional icons (don't know what the icons are for though)
		 *	
		 *	@static
		 *	@param string $Title
		 *	@param string $TitleOnClick - optional
		 *	@param integer $Height - height of box in pixels
		 *	@param array $Icons - an array of key/value pairs representing 'icon class name' => 'onclick javascript string'
		 *	@param string $Color - optional, defaults to "", part of a longer class name for the box, ie: CBox_{$Color}
		 *	@return string
		 */
		public static function Start($Title, $TitleOnClick = "", $Height = 0, $Icons = Array(), $Color = "") {
			if(strlen($Color) <= 0) {
				$Color = "Default";
			}

			$UID = uniqid();

			$Content = "";

			$Content .= "<div class='CBox CBox_".$Color."' id='CBox_$UID'>\n";
				$Content .= "<div class='CBox_Header CBox_".$Color."_Header'>";
				
				foreach($Icons as $Icon => $OnClick) {
					$Content .= "<div style='float: right;' class='$Icon CBox_Icon' onClick=\"$OnClick\"></div>";
				}
				
				$Content .= "<a href=\"javascript: $TitleOnClick\" class='CBox_Title CBox_".$Color."_Title'>&nbsp;$Title</a></div>\n";
				$Content .= "<div class='CBox_Content CBox_".$Color."_Content' id='CBox_Content_".$UID."'";

				if($Height > 0) {
					$Content .= " style='height: $Height"."px'";
				}
				
				$Content .= ">\n";

			self::$LastUID = $UID;

			return $Content;
		}

		/**
		 *	Return the closing tags for a box
		 *	
		 *	@static
		 *	@return string
		 */
		public static function End() {
			$Content = "";

				$Content .= "</div>\n";
			$Content .= "</div>\n";

			return $Content;
		}

		/**
		 *	Just like the name says
		 *	
		 *	@static
		 *	@return integer | string
		 */
		public static function GetLastUID() {
			return self::$LastUID;
		}

		/**
		 *	????
		 *	
		 *	@static
		 *	@param string $Title
		 *	@return string
		 */
		public static function PageTitle($Title) {
			return '
			<div class="PageTitle">'.$Title.'</div>
			<div class="HR_PageTitle"></div>
			';
		}

		/**
		 *	Return the html string form of a CBox_Alert with given content, and optional
		 *	button to perform an action
		 *	
		 *	@static
		 *	@param string $Content - the message
		 *	@param string $ButtonName - optional, a visible label for the button
		 *	@param string $ButtonClick
		 */
		public static function Alert($Content, $ButtonName = "", $ButtonOnClick = "") {
			echo '
			<div class="CBox_Alert"><p>';

			if(strlen($ButtonName) > 0) {
				echo "<input type='button' value='$ButtonName' onClick=\"$ButtonOnClick\" style='float: right;'/>";
			}

			echo '
				<span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span> 
				'.$Content.'</p>
			</div>
			';
		}

		/**
		 *	Output HTML to the browser with the CBox_Notice class wrapper
		 *	
		 *	@param string $Content
		 *	@param string $ButtonName - default:""
		 *	@param string $ButtonClick - default:""
		 *	@return null
		 */
		public static function Notice($Content, $ButtonName = "", $ButtonOnClick = "") {
			echo '
			<div class="CBox_Notice"><p>';

			if(strlen($ButtonName) > 0) {
				echo "<input type='button' value='$ButtonName' onClick=\"$ButtonOnClick\" style='float: right;'/>";
			}

			echo '
				<span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span> 
				'.$Content.'</p>
			</div>
			';
		}

		/**
		 *	Output HTML to the browser with the CBox_Bar class wrapper
		 *	
		 *	@param string $Content
		 *	@param string $ButtonName - default:""
		 *	@param string $ButtonClick - default:""
		 *	@return null
		 */
		public static function Bar($Content, $ButtonName = "", $ButtonOnClick = "") {
			echo '
			<div class="CBox_Bar"><p>';

			if(strlen($ButtonName) > 0) {
				echo "<input type='button' value='$ButtonName' onClick=\"$ButtonOnClick\" style='float: right;'/>";
			}

			echo '
				'.$Content.'</p>
			</div>
			';
		}
	};

	//==========================================================================
?>
