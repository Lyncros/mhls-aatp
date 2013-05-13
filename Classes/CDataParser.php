<?
	//==========================================================================
	/*
		A class that will look for special YPP Data tags, and parse them

		12/22/2009 9:35 AM

		Commands:
		 - System	- Retrieve a value from the system
		 - Data		- Public data set on a per class need basis
		 - Loop		- Loop through a given set of Data

		 - Dataset	- Retrieve a value from a dataset
		 - Business - 
		 - Order	- 
		 - Product	- 
		 - Store	- Retrieve a value from a store
		 - Template	-
	*/
	//==========================================================================
	class CDataParser {
		private static $PublicData	= Array();
		private static $LoopData	= Array();

		public static $Debug		= false;

		public static function GetPublicData() {
			return self::$PublicData;
		}

		public static function SetPublicData($Key, $Value) {
			self::$PublicData[$Key] = $Value;
		}

		public static function SetPublicDataAll($Data) {
			self::$PublicData = $Data;
		}

		public static function ClearPublicData() {
			self::$PublicData = Array();
		}

		public static function Parse($Data) {
			$NewData = Array();

			foreach($Data as $Key => $Value) {
				foreach($Value->Current as $RowKey => $RowValue) {
					$NewData[$Key][$RowKey] = self::ParseString($RowValue);
				}
			}

			return new CDataParserIterator($NewData);
		}

		public static function ParseString($String) {
			$Token		= Array();
			$Parents	= Array();

			$TokenObj	= &$Token;

			$StrLength	= strlen($String);

			//Tokenize
			for($i = 0;$i < $StrLength;$i++) {
				$Char = $String{$i};

				if($i+1 < $StrLength) {
					$NextChar = $String{$i+1};
				}else{
					$NextChar = "";
				}

				if($Char == "[" && $NextChar == "[") {
					$Pos	= $i+2;

					$Tag	= "";
					$Parms	= Array();

					for(;$Pos < $StrLength;$Pos++) {
						$Char = $String{$Pos};

						if($Char == " " || $Char == "]") break;

						$Tag .= $Char;
					}

					$ParmName		= "";
					$ParmValue		= "";

					$ParmMode		= "GetName";
					$ParmInQuote	= false;

					for(;$Pos < $StrLength;$Pos++) {
						$Char2 = $String{$Pos};

						if($Pos+1 < $StrLength) {
							$NextChar2	= $String{$Pos+1};
						}else{
							$NextChar2	= "";
						}

						if($Char2 == "]" && $NextChar2 == "]" && $ParmInQuote == false) {
							$Parms[$ParmName] = $ParmValue;
							break;
						}else
						if($Char2 == " " && $ParmInQuote == false) {
							if($ParmName != "") {
								$Parms[$ParmName] = $ParmValue;
							}

							$ParmName	= "";
							$ParmValue	= "";

							$ParmMode	= "GetName";
						}else
						if($ParmMode == "GetName") {
							if($Char2 == "=") {
								$ParmMode = "GetValue";
							}else{
								$ParmName .= $Char2;
							}
						}else
						if($ParmMode == "GetValue") {
							if($Char2 == "\"") {
								$ParmInQuote = !$ParmInQuote;
							}else{
								$ParmValue .= $Char2;
							}
						}
					}

					if($Tag == "EndLoop" || $Tag == "EndIf") {
						$TokenObj = &$Parents[count($Parents) - 1];

						array_pop($Parents);
					}

					$TokenObj[] = Array(
						"Tag"		=> $Tag,
						"Pos"		=> $i,
						"Length"	=> $Pos - $i + 2,
						"Parms"		=> $Parms,
						"Children"	=> Array()
					);

					if($Tag == "Loop" || $Tag == "If") {
						$Parents[] = &$TokenObj;

						$TokenObj = &$TokenObj[count($TokenObj) - 1]["Children"];
					}
				}
			}

			return self::DoParse($String, $Token, 0, self::$PublicData);
		}

		private static function DoParse($String, $Tokens, $PosOffset = 0, $Data, $DataIterator = null, $DataName = "") {
			static $Level = -1;

			++$Level;

			$MarginLeft = 0;

			for($k = 0;$k < $Level;$k++) {
				$MarginLeft += 50;
			}

			for($i = 0;$i < count($Tokens);$i++) {
				$Token = $Tokens[$i];

				if($i + 1 < count($Tokens)) {
					$NextToken = $Tokens[$i+1];
				}else{
					$NextToken = null;
				}

				$ReplaceValue	= "";

				$ReplacePos		= $Token["Pos"] + $PosOffset;
				$ReplaceLength	= $Token["Length"];

				switch($Token["Tag"]) {
					case "System":		{ $ReplaceValue = self::ParseSystem($Token["Parms"], $DataIterator);					break; }
					case "Data":		{ $ReplaceValue = self::ParseData($Token["Parms"], $Data, $DataIterator, $DataName);	break; }

					case "Business":	{ $ReplaceValue = self::ParseBusiness($Token["Parms"], $DataIterator);					break; }
					case "Order":		{ $ReplaceValue = self::ParseOrder($Token["Parms"], $DataIterator);						break; }
					case "Quote":		{ $ReplaceValue = self::ParseQuote($Token["Parms"], $DataIterator);						break; }
					case "Store":		{ $ReplaceValue = self::ParseStore($Token["Parms"], $DataIterator);						break; }
					case "Template":	{ $ReplaceValue = self::ParseTemplate($Token["Parms"], $DataIterator);					break; }

					case "If": {
						if($NextToken == null || $NextToken["Tag"] !== "EndIf") break;

						$Condition = $Token["Parms"]["Condition"];

						$Parts = explode(" ", $Condition, 3);

						$Left		= self::ParseConditionVariable($Parts[0], $Token, $Data, $DataIterator, $DataName);
						$Operand	= $Parts[1];
						$Right		= self::ParseConditionVariable($Parts[2], $Token, $Data, $DataIterator, $DataName);

						$ConditionTrue = false;

						switch($Operand) {
							case "!=":	{ if($Left != $Right)	$ConditionTrue = true; break; }
							case "==":	{ if($Left == $Right)	$ConditionTrue = true; break; }
							case "===": { if($Left === $Right)	$ConditionTrue = true; break; }
							case ">":	{ if($Left > $Right)	$ConditionTrue = true; break; }
							case "<":	{ if($Left < $Right)	$ConditionTrue = true; break; }
							case ">=":	{ if($Left >= $Right)	$ConditionTrue = true; break; }
							case "<=":	{ if($Left <= $Right)	$ConditionTrue = true; break; }

							default: { break; }
						}

						$ReplaceLength	= ($NextToken["Pos"] - $Token["Pos"]) + $NextToken["Length"];
						$LoopString		= substr($String, $Token["Pos"] + $Token["Length"] + $PosOffset, $ReplaceLength - $NextToken["Length"] - $Token["Length"]);
						$ReplaceValue	= "";

						if($ConditionTrue) {
							$ReplaceValue = self::DoParse($LoopString, $Token["Children"], -$Token["Pos"] - $Token["Length"], $Data);
						}

						break;
					}

					case "Loop": {
						if($NextToken == null || $NextToken["Tag"] !== "EndLoop") break;

						$Start		= (isset($Token["Parms"]["Start"])	? intval($Token["Parms"]["Start"]) : 0);
						$End		= (isset($Token["Parms"]["End"])	? intval($Token["Parms"]["End"]) : 0);

						$Type		= $Token["Parms"]["Type"];
						$DataName	= $Token["Parms"]["DataName"];

						$LoopData	= $Data[$DataName];

						if(is_array($LoopData) == false) break;

						$ReplaceLength	= ($NextToken["Pos"] - $Token["Pos"]) + $NextToken["Length"];
						$LoopString		= substr($String, $Token["Pos"] + $Token["Length"] + $PosOffset, $ReplaceLength - $NextToken["Length"] - $Token["Length"]);
						$ReplaceValue	= "";

						if(!isset($Token["Parms"]["End"])) {
							$End = count($LoopData);
						}else{
							$End = intval($End);
						}

						if($End < $Start) break;	

						if($Type == "ForEach") {
							foreach($LoopData as $Key => $Value) {
								$ReplaceValue .= self::DoParse($LoopString, $Token["Children"], -$Token["Pos"] - $Token["Length"], $Value, $Key, $DataName);
							}
						}else{
							for($j = $Start;$j < $End;$j++) {
								$LoopData = $Data[$j];

								$ReplaceValue .= self::DoParse($LoopString, $Token["Children"], -$Token["Pos"] - $Token["Length"], $Value, $j, $DataName);
							}
						}

						break;
					}

					case "EndIf": 
					case "EndLoop": {
						$ReplaceLength = 0;
						break;
					}

					default: { break; }
				}

				if(isset($Token["Parms"]["Array"])) {
					switch($Token["Parms"]["Array"]) {
						case "Count": { $ReplaceValue = count($ReplaceValue); break; }
					}
				}

				if(isset($Token["Parms"]["Format"])) {

					switch($Token["Parms"]["Format"]) {
						case "Number": {
							if(!isset($Token["Parms"]["Precision"])) {
								$Precision = 2;
							}else{
								$Precision = $Token["Parms"]["Precision"];
							}

							$ReplaceValue = number_format($ReplaceValue, $Precision);

							break;
						}

						case "Date": {
							if(!isset($Token["Parms"]["DateFormat"])) {
								$DateFormat = "m-d-Y";
							}else{
								$DateFormat = $Token["Parms"]["DateFormat"];
							}

							$ReplaceValue = date($DateFormat, $ReplaceValue);

							break;
						}

						case "PicPreview": {
							(isset($Token["Parms"]["Size"])			? $Size			= $Token["Parms"]["Size"]			: $Size = 100);
							(isset($Token["Parms"]["Ratio"])		? $Ratio		= $Token["Parms"]["Ratio"]			: $Ratio = 0);
							(isset($Token["Parms"]["AutoCrop"])		? $AutoCrop		= $Token["Parms"]["AutoCrop"]		: $AutoCrop = 0);
							(isset($Token["Parms"]["SizeType"])		? $SizeType		= $Token["Parms"]["SizeType"]		: $SizeType = "Auto");
							(isset($Token["Parms"]["AutoCropPos"])	? $AutoCropPos	= $Token["Parms"]["AutoCropPos"]	: $AutoCropPos = "Center Center");

							$ReplaceValue = CPicPreview::GetURL($ReplaceValue, $Size, $Ratio, $AutoCrop, $SizeType, $AutoCropPos);

							break;
						}

						case "ShippingTrackingLink": {
							$ReplaceValue = CFormat::GetShippingTrackingLink($ReplaceValue);

							break;
						}

						case "nl2br": {
							$ReplaceValue = nl2br($ReplaceValue);
							break;
						}

						default: {
							break;
						}
					}
				}

				//Debug Stuffs
				if(self::$Debug) {
				/*	echo "<pre>";
					echo "<div style='margin-left: $MarginLeft"."px'>";
					echo "<b>Token:</b> <div style='border: 1px black solid; background-color: #FFF0DD; margin: 10px;'>".print_r($Token, true)."</div>\n";
					echo "<b>String:</b> <div style='border: 1px black solid; background-color: #FFF0DD; margin: 10px;'>".htmlspecialchars($String)."</div>\n";
					echo "<b>Replace Text:</b> <div style='border: 1px black solid; background-color: #E2FFDD; margin: 10px;'>".htmlspecialchars(substr($String, $ReplacePos, $ReplaceLength))."</div>\n";
					echo "<b>Replace:</b> <div style='border: 1px black solid; background-color: #E2FFDD; margin: 10px;'>".htmlspecialchars($ReplaceValue)."</div>\n";
					echo "</div>";
					echo "</pre>";*/
				}

				$String = substr_replace($String, $ReplaceValue, $ReplacePos, $ReplaceLength);

				$PosOffset = $PosOffset - ($ReplaceLength - strlen($ReplaceValue));
			}

			$Level--;

			//Debug Stuffs
			if(self::$Debug) {
			/*	echo "<pre>";
				echo "<div style='margin-left: $MarginLeft"."px'>";
				echo "<b>Final String:</b> <div style='border: 1px black solid; background-color: #FFF0DD; margin: 10px;'>".htmlspecialchars($String)."</div>\n";
				echo "</div>";
				echo "</pre>";*/
			}

			return $String;
		}

		private static function ParseSystem($Parms, $DataIterator) {
			$NewValue = "";

			switch($Parms["Action"]) {
				case "GetDomain": {
					$NewValue = CURL::GetDomain(false);
					break;
				}

				case "GetFullDomain": {
					$NewValue = CURL::GetDomain(true);
					break;
				}

				case "GetShortDomain": {
					$NewValue = CURL::GetShortDomain();
					break;
				}

				case "GetDateTime": {
					$NewValue = time();
					break;
				}

				default: {
					break;
				}
			}

			return $NewValue;
		}

		private static function ParseData($Parms, $Data, $DataIterator, $DataName) {
			$NewValue = "";

			$Action		= (isset($Parms["Action"])	? $Parms["Action"]	: "Get");
			$Name		= (isset($Parms["Name"])	? $Parms["Name"]	: "");
			$Source		= (isset($Parms["Source"])	? $Parms["Source"]	: "");

			switch($Action) {
				case "Get": {
					if($Source == "Loop") {
						$NewValue = $Data[$Name];
					}else{
						$NewValue = self::$PublicData[$Name];
					}

					break;
				}

				case "Set": {
					self::$PublicData[$Name] = $NewValue = $Parms["Value"];
					break;
				}

				default: {
					break;
				}
			}

			return $NewValue;
		}

		private static function ParseBusiness($Parms, $DataIterator) {
			$Business = CSystem::GetBusinessesObject();

			if($Business == false) return $Value;

			$NewValue = $Value;

			switch($Parms["Action"]) {
				case "GetLogo": {
					if(($NewValue = CBusinesses::HasLogo($Business->ID)) === false) $NewValue = "";

					break;
				}

				case "GetCompany":				{ $NewValue = $Business->Company; break; }
				case "GetFirstName":			{ $NewValue = $Business->FirstName; break; }
				case "GetLastName":				{ $NewValue = $Business->LastName; break; }
				case "GetAddress1":				{ $NewValue = $Business->Address1; break; }
				case "GetAddress2":				{ $NewValue = $Business->Address2; break; }
				case "GetCity":					{ $NewValue = $Business->City; break; }
				case "GetState":				{ $NewValue = $Business->State; break; }
				case "GetZip":					{ $NewValue = $Business->Zip; break; }
				case "GetCountry":				{ $NewValue = $Business->Country; break; }
				case "GetPhone":				{ $NewValue = $Business->Phone; break; }
				case "GetFax":					{ $NewValue = $Business->Fax; break; }
				case "GetEmail":				{ $NewValue = $Business->Email; break; }
				case "GetType":					{ $NewValue = $Business->Type; break; }
				case "GetSubType":				{ $NewValue = $Business->SubType; break; }
				case "GetPOPrefix":				{ $NewValue = $Business->POPrefix; break; }
				case "GetPOCount":				{ $NewValue = $Business->POCount; break; }

				default: {
					break;
				}
			}

			return $NewValue;
		}

		private static function ParseOrder($Parms, $DataIterator) {
			$NewValue = "";

			$Order = CSystem::GetCurrentOrder(true);

			switch($Parms["Action"]) {
				case "GetCustomerPONumber":		{ if($Order) { $NewValue = $Order->CustomerPONumber; } break; }
				case "GetPONumber":				{ if($Order) { $NewValue = $Order->PONumber; } break; }

				case "GetBillingCompany":		{ $NewValue = $Order->BillingCompany; break; }
				case "GetBillingFirstName":		{ $NewValue = $Order->BillingFirstName; break; }
				case "GetBillingLastName":		{ $NewValue = $Order->BillingLastName; break; }
				case "GetBillingAddress1":		{ $NewValue = $Order->BillingAddress1; break; }
				case "GetBillingAddress2":		{ $NewValue = $Order->BillingAddress2; break; }
				case "GetBillingCity":			{ $NewValue = $Order->BillingCity; break; }
				case "GetBillingState":			{ $NewValue = $Order->BillingState; break; }
				case "GetBillingZip":			{ $NewValue = $Order->BillingZip; break; }
				case "GetBillingCountry":		{ $NewValue = $Order->BillingCountry; break; }
				case "GetBillingPhone":			{ $NewValue = $Order->BillingPhone; break; }
				case "GetBillingFax":			{ $NewValue = $Order->BillingFax; break; }
				case "GetBillingEmail":			{ $NewValue = $Order->BillingEmail; break; }

				case "GetShippingCompany":		{ $NewValue = $Order->ShippingCompany; break; }
				case "GetShippingFirstName":	{ $NewValue = $Order->ShippingFirstName; break; }
				case "GetShippingLastName":		{ $NewValue = $Order->ShippingLastName; break; }
				case "GetShippingAddress1":		{ $NewValue = $Order->ShippingAddress1; break; }
				case "GetShippingAddress2":		{ $NewValue = $Order->ShippingAddress2; break; }
				case "GetShippingCity":			{ $NewValue = $Order->ShippingCity; break; }
				case "GetShippingState":		{ $NewValue = $Order->ShippingState; break; }
				case "GetShippingZip":			{ $NewValue = $Order->ShippingZip; break; }
				case "GetShippingCountry":		{ $NewValue = $Order->ShippingCountry; break; }

				case "GetComments":				{ if($Order) { $NewValue = $Order->Comments; } break; }

				case "GetCCNumberLast4":		{ if($Order) { $NewValue = substr($Order->GetCCNumber(), -4, 4); } break; }

				case "GetSubTotal":				{ $NewValue = $Order->SubTotal; break; }
				case "GetShipping":				{ $NewValue = $Order->Shipping; break; }
				case "GetTax":					{ $NewValue = $Order->Tax; break; }
				case "GetDiscount":				{ $NewValue = $Order->Discount; break; }
				case "GetTotal":				{ $NewValue = $Order->Total; break; }

				case "LoadStore":				{ 
					$StoreClass = new CStores();
					if($StoreClass->OnLoad($Order->StoresID) !== false) {
					}

					break;
				}

				case "GetStoreDomain": {					
					if($Order) {
						$StoreClass = new CStores();
						if($StoreClass->OnLoadByID($Order->StoresID) !== false) {
							$StoreClass->OnInit();

							$NewValue = $StoreClass->GetPrimaryURL();

							$NewValue = str_replace("http://", "", $NewValue);
							$NewValue = str_replace("https://", "", $NewValue);

							$NewValue = explode(".", $NewValue);
							$NewValue = $NewValue[count($NewValue) - 2].".".$NewValue[count($NewValue) - 1];
						}
					}

					break;
				}

				default: {
					break;
				}
			}

			return $NewValue;
		}

		private static function ParseQuote($Parms, $DataIterator) {
			$NewValue = "";

			$Quote = CSystem::GetCurrentQuote(true);

			switch($Parms["Action"]) {
				case "LoadStore":				{ 
					$StoreClass = new CStores();
					if($StoreClass->OnLoad($Quote->StoresID) !== false) {
					}

					break;
				}

				default: {
					break;
				}
			}

			return $NewValue;
		}

		private static function ParseStore($Parms, $DataIterator) {
			$NewValue = "";

			$Store = CSystem::GetCurrentStore(true);

			switch($Parms["Action"]) {
				case "GetID": {
					$NewValue = $Store->ID;
					break;
				}

				case "GetName": {
					$NewValue = $Store->Name;
					break;
				}

				case "GetDomain": {
					$NewValue = $Store->GetPrimaryURL();
					break;
				}

				default: {
					break;
				}
			}

			return $NewValue;
		}

		private static function ParseTemplate($Parms, $DataIterator) {
			$NewValue = "";

			switch($Parms["Action"]) {
				case "GetName": {
					$NewValue = CURL::GetDomain(false);
					break;
				}

				default: {
					break;
				}
			}

			return $NewValue;
		}

		private static function ParseConditionVariable($Var, $Token, $Data, $DataIterator, $DataName) {
			$Type = "Variable";

			if($Var{0}  == "'")		$Type	= "String";
			if(is_numeric($Var{0}))	$Type	= "Number";

			switch($Type) {
				case "Number": {
					if(is_float($Var))	doubleval($Var);
					else				intval($Var);
					break;
				}

				case "Variable": {
					$Var = self::ParseData(Array("Name" => $Var, "Source" => $Token["Parms"]["Source"]), $Data, $DataIterator, $DataName);
					break;
				}

				//String
				default: { $Var = substr($Var, 1, strlen($Var) - 2); break; }
			}

			return $Var;
		}
	};
?>
