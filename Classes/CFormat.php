<?
	class CFormat {
		public static function CleanCC($CC) {
			return preg_replace("([^0-9])", "", $CC);
		}

		// http://www.merriampark.com/anatomycc.htm
		public static function GetCCIndustry($CC) {
			$CC = self::CleanCC($CC);

			switch($CC[0]) {
				case 0: return "ISO/TC 68";
				case 1: return "Airline";
				case 2: return "Airline / Other";
				case 3: return "Travel / Entertainment";
				case 4: return "Banking / Financial";
				case 5: return "Banking / Financial";
				case 6: return "Merchandizing / Banking";
				case 7: return "Petroleum";
				case 8: return "Telecommunications / Other";
				case 9: return "National Assignment";
			}

			return "";
		}

		public static function GetCCIssuer($CC) {
			$CC = self::CleanCC($CC);

			$Issuer = "";

			if(strlen($CC) == 13) {
				if($CC[0] == 4) $Issuer = "VISA";
			}

			if(strlen($CC) == 14) {
				if($CC[0] == 3) {
					if($CC[1] == 6 || $CC[1] == 8) $Issuer = "Diners Club/Carte Blance";
					if($CC[1] == 0 && ($CC[2] == 0 || $CC[2] == 1 || $CC[2] == 2 || $CC[2] == 3 || $CC[2] == 4 || $CC[2] == 5)) $Issuer = "Diners Club/Carte Blance";
				}
			}

			if(strlen($CC) == 15) {
				if($CC[0] == 3) {
					if($CC[1] == 4 || $CC[1] == 7) $Issuer = "American Express";
				}
			}

			if(strlen($CC) == 16) {
				if($CC[0] == 4) $Issuer = "VISA";
				if(substr($CC, 0, 2) >= 51 && substr($CC, 0, 2) <= 55 ) $Issuer = "MasterCard";
				if(substr($CC, 0, 4) == "6011") $Issuer = "Discover";
			}

			return $Issuer;
		}

		public static function CC($CC, $Mask = true, $SeparatorChar = "-", $MaskChar = "X") { return self::CreditCard($CC, $Mask, $SeparatorChar, $MaskChar); }
		public static function CreditCard($CC, $Mask = true, $SeparatorChar = "-", $MaskChar = "X") {
			$CC = self::CleanCC($CC);

			$Issuer	= self::GetCCIssuer($CC);

			$NewCC = "";

			if(strlen($CC) <= 4) {
				$CC = str_pad($CC, 16, $MaskChar, STR_PAD_LEFT);
			}

			$j = strlen($CC);
			for($i = 0;$i < strlen($CC);$i++) {
				if($j % 4 == 0 && $j != strlen($CC)) $NewCC .= $SeparatorChar;

				if($Mask && $j > 4) {
					$NewCC .= $MaskChar;
				}else{
					$NewCC .= $CC[$i];
				}

				$j--;
			}

			return $NewCC;
		}

		public static function GetShippingTrackingLink($TrackingNumber) {
			if(stripos($TrackingNumber, "1Z") === false) {
				return "http://fedex.com/Tracking?action=track&cntry_code=us&tracknumber_list=".$TrackingNumber;
			}

			return "http://wwwapps.ups.com/WebTracking/processInputRequest?TypeOfInquiryNumber=T&InquiryNumber1=".$TrackingNumber;
		}

		public static function SplitByCaps($String) {
		    return preg_replace('/([a-z0-9])?([A-Z])/', '$1 $2', $String);
		}

		public static function SpecialChars($String) {
			return htmlspecialchars($String, ENT_COMPAT | ENT_HTML401, "ISO-8859-1", false);
		}
	}
?>
