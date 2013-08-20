<?
	//==========================================================================
	/*
		A class for quickly creating Forms

		See CForm.js

		TO DO: StateList, CountryList, ProvinceList shouldn't be here. They
		should be in some tables in the database.

		6/6/2009 9:30 AM
	*/
	//==========================================================================
	class CForm {
		public static $StateList = array(
			""   => "-- Select State --",
			"AL" => "Alabama",
			"AK" => "Alaska",
			"AZ" => "Arizona",
			"AR" => "Arkansas",
			"CA" => "California",
			"CO" => "Colorado",
			"CT" => "Connecticut",
			"DE" => "Delaware",
			"FL" => "Florida",
			"GA" => "Georgia",
			"HI" => "Hawaii",
			"ID" => "Idaho",
			"IL" => "Illinois",
			"IN" => "Indiana",
			"IA" => "Iowa",
			"KS" => "Kansas",
			"KY" => "Kentucky",
			"LA" => "Louisiana",
			"ME" => "Maine",
			"MD" => "Maryland",
			"MA" => "Massachusetts",
			"MI" => "Michigan",
			"MN" => "Minnesota",
			"MS" => "Mississippi",
			"MO" => "Missouri",
			"MT" => "Montana",
			"NE" => "Nebraska",
			"NV" => "Nevada",
			"NH" => "New Hampshire",
			"NJ" => "New Jersey",
			"NM" => "New Mexico",
			"NY" => "New York",
			"NC" => "North Carolina",
			"ND" => "North Dakota",
			"OH" => "Ohio",
			"OK" => "Oklahoma",
			"OR" => "Oregon",
			"PA" => "Pennsylvania",
			"RI" => "Rhode Island",
			"SC" => "South Carolina",
			"SD" => "South Dakota",
			"TN" => "Tennessee",
			"TX" => "Texas",
			"UT" => "Utah",
			"VT" => "Vermont",
			"VA" => "Virginia",
			"WA" => "Washington",
			"DC" => "Washington D.C.",
			"WV" => "West Virginia",
			"WI" => "Wisconsin",
			"WY" => "Wyoming"
		);

		public static $CountryList = array(
			"US" => "United States",
			"CA" => "Canada"
		);
		
		public static $WorldCountryList = array(
			"AF" => "Afghanistan",
			"AX" => "Aland Islands",
			"AL" => "Albania",
			"DZ" => "Algeria",
			"AS" => "American Samoa",
			"AD" => "Andorra",
			"AO" => "Angola",
			"AI" => "Anguilla",
			"AQ" => "Antarctica",
			"AG" => "Antigua and Barbuda",
			"AR" => "Argentina",
			"AM" => "Armenia",
			"AW" => "Aruba",
			"AU" => "Australia",
			"AT" => "Austria",
			"AZ" => "Azerbaijan",
			"BS" => "Bahamas",
			"BH" => "Bahrain",
			"BD" => "Bangladesh",
			"BB" => "Barbados",
			"BY" => "Belarus",
			"BE" => "Belguim",
			"BZ" => "Belize",
			"BJ" => "Benin",
			"BM" => "Bermuda",
			"BT" => "Bhutan",
			"BO" => "Bolivia",
			"BQ" => "Bonaire, Saint Eustatius and Saba",
			"BA" => "Bosnia and Herzegovina",
			"BW" => "Botswana",
			"BV" => "Bouvet Island",
			"BR" => "Brazil",
			"IO" => "British Indian Ocean Territory",
			"BN" => "Brunei Darussalam",
			"BG" => "Bulgaria",
			"BF" => "Burkina Faso",
			"BI" => "Burundi",
			"KH" => "Cambodia",
			"CM" => "Cameroon",
			"CA" => "Canada",
			"CV" => "Cape Verde",
			"KY" => "Cayman Islands",
			"CF" => "Central African Republic",
			"TD" => "Chad",
			"CL" => "Chile",
			"CN" => "China",
			"CX" => "Christmas Island",
			"CC" => "Cocos (Keeling) Islands",
			"CO" => "Colombia",
			"KM" => "Comoros",
			"CG" => "Congo",
			"CD" => "Congo, The Democratic Republic of the",
			"CK" => "Cook Islands",
			"CR" => "Costa Rica",
			"CI" => "Cote D'Ivoire",
			"HR" => "Croatia",
			"CU" => "Cuba",
			"CW" => "Curacao",
			"CY" => "Cyprus",
			"CZ" => "Czech Republic",
			"DK" => "Denmark",
			"DJ" => "Djibouti",
			"DM" => "Dominica",
			"DO" => "Dominican Republic",
			"EC" => "Ecuador",
			"EG" => "Egypt",
			"SV" => "El Salvador",
			"GQ" => "Equatorial Guinea",
			"ER" => "Eritrea",
			"EE" => "Estonia",
			"ET" => "Ethiopia",
			"FK" => "Falkland Islands (Malvinas)",
			"FO" => "Faroe Islands",
			"FJ" => "Fiji",
			"FI" => "Finland",
			"FR" => "France",
			"GF" => "French Guiana",
			"PF" => "French Polynesia",
			"TF" => "French Southern Territories",
			"GA" => "Gabon",
			"GM" => "Gambia",
			"GE" => "Georgia",
			"DE" => "Germany",
			"GH" => "Ghana",
			"GI" => "Gibraltar",
			"GR" => "Greece",
			"GL" => "Greenland",
			"GD" => "Grenada",
			"GP" => "Guadeloupe",
			"GU" => "Guam",
			"GT" => "Guatemala",
			"GG" => "Guernsey",
			"GN" => "Guinea",
			"GW" => "Guinea-Bissau",
			"GY" => "Guyana",
			"HT" => "Haiti",
			"HM" => "Heard Island and McDonald Islands",
			"VA" => "Holy See (Vatican City State)",
			"HN" => "Honduras",
			"HK" => "Hong Kong",
			"HU" => "Hungary",
			"IS" => "Iceland",
			"IN" => "India",
			"ID" => "Indonesia",
			"IR" => "Iran, Islamic Republic of",
			"IQ" => "Iraq",
			"IE" => "Ireland",
			"IM" => "Isle of Man",
			"IL" => "Israel",
			"IT" => "Italy",
			"JM" => "Jamaica",
			"JP" => "Japan",
			"JE" => "Jersey",
			"JO" => "Jordan",
			"KZ" => "Kazakhstan",
			"KE" => "Kenya",
			"KI" => "Kiribati",
			"KP" => "Korea, Democratic People's Republic of",
			"KR" => "Korea, Republic of",
			"KW" => "Kuwait",
			"KG" => "Kyrgyzstan",
			"LA" => "Lao People's Democratic Republic",
			"LV" => "Latvia",
			"LB" => "Lebanon",
			"LS" => "Lesotho",
			"LR" => "Liberia",
			"LY" => "Libyan Arab Jamahiriya",
			"LI" => "Liechtenstein",
			"LT" => "Lithuania",
			"LU" => "Luxembourg",
			"MO" => "Macao",
			"MK" => "Macedonia, The Former Yugoslav Republic of",
			"MG" => "Madagascar",
			"MW" => "Malawi",
			"MY" => "Malaysia",
			"MV" => "Maldives",
			"ML" => "Mali",
			"MT" => "Malta",
			"MH" => "Marshall Islands",
			"MQ" => "Martinique",
			"MR" => "Mauritania",
			"MU" => "Mauritius",
			"YT" => "Mayotte",
			"MX" => "Mexico",
			"FM" => "Micronesia, Federated States of",
			"MD" => "Moldova, Republic of",
			"MC" => "Monaco",
			"MN" => "Mongolia",
			"ME" => "Montenegro",
			"MS" => "Montserrat",
			"MA" => "Morocco",
			"MZ" => "Mozambique",
			"MM" => "Myanmar",
			"NA" => "Namibia",
			"NR" => "Nauru",
			"NP" => "Nepal",
			"NL" => "Netherlands",
			"NC" => "New Caledonia",
			"NZ" => "New Zealand",
			"NI" => "Nicaragua",
			"NE" => "Niger",
			"NG" => "Nigeria",
			"NU" => "Niue",
			"NF" => "Norfolk Island",
			"MP" => "Northern Mariana Islands",
			"NO" => "Norway",
			"OM" => "Oman",
			"PK" => "Pakistan",
			"PW" => "Palau",
			"PS" => "Palestinian Territory, Occupied",
			"PA" => "Panama",
			"PG" => "Papua New Guinea",
			"PY" => "Paraguay",
			"PE" => "Peru",
			"PH" => "Philippines",
			"PN" => "Pitcairn",
			"PL" => "Poland",
			"PT" => "Portugal",
			"PR" => "Puerto Rico",
			"QA" => "Qatar",
			"RE" => "Reunion",
			"RO" => "Romania",
			"RU" => "Russian Federation",
			"RW" => "Rwanda",
			"BL" => "Saint Barthelemy",
			"SH" => "Saint Helena, Ascension and Tristan Da Cunha",
			"KN" => "Saint Kitts and Nevis",
			"LC" => "Saint Lucia",
			"MF" => "Saint Martin (French Part)",
			"PM" => "Saint Pierre and Miquelon",
			"VC" => "Saint Vincent and the Grenadines",
			"WS" => "Samoa",
			"SM" => "San Marino",
			"ST" => "Sao Tome and Principe",
			"SA" => "Saudi Arabia",
			"SN" => "Senegal",
			"RS" => "Serbia",
			"SC" => "Seychelles",
			"SL" => "Sierra Leone",
			"SG" => "Singapore",
			"SX" => "Sint Maarten (Dutch Part)",
			"SK" => "Slovakia",
			"SI" => "Slovenia",
			"SB" => "Solomon Islands",
			"SO" => "Somalia",
			"ZA" => "South Africa",
			"GS" => "South Georgia and the South Sandwich Islands",
			"ES" => "Spain",
			"LK" => "Sri Lanka",
			"SD" => "Sudan",
			"SR" => "Suriname",
			"SJ" => "Svalbard and Jan Mayen",
			"SZ" => "Swaziland",
			"SE" => "Sweden",
			"CH" => "Switzerland",
			"SY" => "Syrian Arab Republic",
			"TW" => "Taiwan, Province of China",
			"TJ" => "Tajikistan",
			"TZ" => "Tanzania, United Republic of",
			"TH" => "Thailand",
			"TL" => "Timor-Leste",
			"TG" => "Togo",
			"TK" => "Tokelau",
			"TO" => "Tonga",
			"TT" => "Trinidad and Tobago",
			"TN" => "Tunisia",
			"TR" => "Turkey",
			"TM" => "Turkmenistan",
			"TC" => "Turks and Caicos Islands",
			"TV" => "Tuvalu",
			"UG" => "Uganda",
			"UA" => "Ukraine",
			"AE" => "United Arab Emirates",
			"GB" => "United Kingdom",
			"US" => "United States",
			"UM" => "United States Minor Outlying Islands",
			"UY" => "Uruguay",
			"UZ" => "Uzbekistan",
			"VU" => "Vanuatu",
			"VE" => "Venezuela, Bolivarian Republic of",
			"VN" => "Vietnam",
			"VG" => "Virgin Islands, British",
			"VI" => "Virgin Islands, U.S.",
			"WF" => "Wallis and Futuna",
			"EH" => "Western Sahara",
			"YE" => "Yemen",
			"ZM" => "Zambia",
			"ZW" => "Zimbabwe",
		);
		
		public static $ProvinceList = array(
			''   => '-- Select Province --',
			'AB' => 'Alberta',
			'BC' => 'British Columbia',
			'MB' => 'Manitoba',
			'NB' => 'New Brunswick',
			'NL' => 'Newfoundland and Labrador',
			'NT' => 'Northwest Territories',
			'NS' => 'Nova Scotia',
			'NU' => 'Nunavut',
			'ON' => 'Ontario',
			'PE' => 'Prince Edward Island',
			'QC' => 'Quebec',
			'SK' => 'Saskatchewan',
			'YT' => 'Yukon',
		);

		private static $Prefix	= "";
		private static $Tooltip = "";

		private static $Format	= "Table";

		public static $StyleName	= "";
		public static $StyleValue	= "";
		
		private static $WindowID                    = "";
		private static $ValidatorInitalized;
		public static $DefaultClassValidationRules = array(
            "required",
            "number",
            "digits",
            "creditcard",
            "date",
            "url",
            "email",
            "dateISO",
            "phoneUS",
            "ipv4",
            "vinUS",
            "lettersonly",
            "alphanumeric",
            "letterswithbasicpunc",
        );

		public static function Redirect($URL) {
			echo "<script language='JavaScript' type='text/javascript'>document.location.href = '".$URL."';</script>";
		}
		
		public static function WindowInit($WindowID = "", $Validate = true) {
            self::Init("CWindow_".$WindowID."Form", $Validate);
        }

        public static function Init($FormID = "", $Validate = true) {
            self::RandomPrefix();
            self::$ValidatorInitalized = false;
            if($Validate == true && $FormID != "") {
                echo self::ValidatorInit($FormID);
            }

            echo "<script>$('#".$FormID."').attr('prefix', '".self::$Prefix."');</script>";

            self::$WindowID = $FormID;
        }

        private static function ValidatorInit($FormID) {
            self::$ValidatorInitalized = true;
            $Content = "
            <script>

                     $('#".$FormID."').validate({
                     	'errorClass' : 'CForm_Error',
                         ignore: ''
                     });
            </script>
            ";
            return $Content;
        }

        private static function AddValidationRule($FormName, $Rules) {
            $Content = "";
            if(self::$ValidatorInitalized == true) {
                if(gettype($Rules) == "string") {


                    $Rules      = explode(" ", $Rules);
                    $ValidRules = 0;
                    foreach($Rules as $Rule) {
                        if(in_array($Rule, self::$DefaultClassValidationRules)) {
                            $ValidRules++;
                        }
                    }
                    if(count($ValidRules)) {

                        $Content = "
                    <script>

                    ";
                        $Content .= "$('#".self::GetPrefix()."$FormName').rules('add',
                    {
                    ";
                        foreach($Rules as $Rule) {
                            if(in_array($Rule, self::$DefaultClassValidationRules)) {
                                $Content .= "$Rule: true,".PHP_EOL;
                            }
                        }
                        $Content = substr($Content, 0, -2);
                        $Content .="
                    });

                    </script>
                    ";
                    }
                }
                else {

                    $Content = "
                <script>

                ";

                    $Content .= "$('#".self::GetPrefix()."$FormName').rules('add',";
                    $Content .= json_encode($Rules);
                    $Content .="
                    );

                </script>
                 ";
                }
            }
            return $Content;
        }
		
		private static function FormatOutput($Name, $Element = "", array $ClassNames = array("Name"    => "CForm_Name", "Content" => "CForm_Value"), $DisplayType = "Basic", $Style) {
            $Content = "";
            if(self::$Format == "Table") {
                if(isset($ClassNames["Name"]) && isset($ClassNames["Content"])) {
                    $Content .= "<tr class='{$DisplayType}' ><td align='right' class='{$ClassNames["Name"]}' valign='top' style='{$Style}'><b onMouseOver=\"CTooltip.Show('".self::$Tooltip."');\" onMouseOut=\"CTooltip.Hide();\">$Name</b>&nbsp;</td>
					<td class='{$ClassNames["Content"]}' valign='top' style='{$Style}'>";
                }
                elseif(isset($ClassNames["Content"])) {
                    $Content .= "<tr class='{$DisplayType}'><td class='{$ClassNames["Content"]}' valign='top' colspan='2' style='{$Style}'>";
                }
                elseif(isset($ClassNames["Name"])) {
                    $Content .= "<tr class='{$DisplayType}'><td class='{$ClassNames["Name"]}' valign='top' colspan='2' style='{$Style}'>";
                }

                $Content .= $Element;
                $Content .= "</td></tr>";
            }

            if(self::$Format == "Semantic") {
                if(isset($ClassNames["Name"]) && isset($ClassNames["Content"])) {
                    $Content .= "<label>$Name</label>";
                }

                $Content .= $Element;
            }
            if(self::$Format == "" || self::$Format == "None") {
                $Content .= $Element;
            }
            CForm::ResetTooltip();
            return $Content;
        }

		public static function MakeSafe($String) {
			$String = str_replace("\"", "&#34;", $String);
			$String = str_replace("'", "&#39;", $String);

			$String = str_replace("<", "&lt;", $String);
			$String = str_replace(">", "&gt;", $String);

			return $String;
		}

		/*
		*/
		public static function MakeSafeValues(&$ThisArray) {
			function MakeSafeLoop(&$Array) {
				if(is_array($Array)) {
					foreach($Array as $Key => $Value) {
						if(is_array($Array[$Key])) {
							$Array[$Key] = MakeSafeLoop($Array[$Key]);
							continue;
						}

						$Array[$Key] = CForm::MakeSafe($Value);
					}
				}

				return $Array;
			}

			return MakeSafeLoop($ThisArray);
		}

		public static function AddRow($Content2, $DisplayType = "Basic") {
			$Content = "
			<tr class='{$DisplayType}'>
				<td align='center' class='CForm_Name' valign='top' colspan='2'>".$Content2."</td>
			</tr>
			";

			return $Content;
		}

		public static function AddHeader($Name) {
			$Content = "
			<tr>
				<td align='center' class='CForm_Header' valign='top' colspan='2'><br/><b onMouseOver=\"CTooltip.Show('".self::$Tooltip."');\" onMouseOut=\"CTooltip.Hide();\">$Name</b></td>
			</tr>
			";

			CForm::ResetTooltip();

			return $Content;
		}

		public static function AddStatic($Name, $Value, $DisplayType = "Basic") {
			$Content = "
			<tr class='{$DisplayType}'>
				<td align='right' class='CForm_Name' valign='top'><b>$Name:</b>&nbsp;</td>
				<td class='CForm_Value' valign='top'>$Value</td>
			</tr>
			";

			CForm::ResetTooltip();

			return $Content;
		}

		public static function AddHidden($FormName, $Value, $Error = "") {
			$Value = CForm::MakeSafe($Value);

			$Content = "<input type='hidden' name='".self::$Prefix.$FormName."' id='".self::$Prefix.$FormName."' value='$Value' title='".$Error."'/>";

			return $Content;
		}

		public static function AddTextbox($Name, $FormName, $Value, $Error = "", $TabIndex = "", $DisplayType = "Basic", $Style = "", $Readonly = false) {
			$Value = CForm::MakeSafe($Value);

			$Content = "";

			if(self::$Format == "Table") {
				$Content .= "
				<tr class='{$DisplayType}'>
					<td align='right' class='CForm_Name' style='{$Style}' valign='top'><b onMouseOver=\"CTooltip.Show('".self::$Tooltip."');\" onMouseOut=\"CTooltip.Hide();\">$Name:</b>&nbsp;</td>
					<td class='CForm_Value' valign='top' style='{$Style}'>";
			}

			if(self::$Format == "Stacked") {
				$Content .= "
				<tr class='{$DisplayType}'>
					<td class='CForm_Value' valign='top' style='{$Style}'><b>$Name</b><br/>";
			}

			$Content .= "<input type='text' name='".self::$Prefix.$FormName."' ";
            $Content .= "id='".self::$Prefix.$FormName."' value='$Value' title='".$Error."' ";
            $Content .= "class='CForm_Textbox' ".($TabIndex != "" ? "tabindex='$TabIndex'" : "");
            $Content .= ($Readonly ? " readonly " : "");
            $Content .= "/>";
				
			if(self::$Format == "Table") {
				$Content .= "
					</td>
				</tr>
				";
			}

			if(self::$Format == "Stacked") {
				$Content .= "
					</td>
				</tr>
				";
			}

			CForm::ResetTooltip();

			return $Content;
		}

		public static function AddPassword($Name, $FormName, $Error = "") {
			$Content = "
			<tr>
				<td align='right' class='CForm_Name' valign='top'><b onMouseOver=\"CTooltip.Show('".self::$Tooltip."');\" onMouseOut=\"CTooltip.Hide();\">$Name:</b>&nbsp;</td>
				<td class='CForm_Value' valign='top'><input type='password' name='".self::$Prefix.$FormName."' id='".self::$Prefix.$FormName."' title='".$Error."' class='CForm_Textbox'/></td>
			</tr>
			";

			CForm::ResetTooltip();

			return $Content;
		}

		public static function AddTextarea($Name, $FormName, $Value, $Error = "", $DisplayType = "Basic") {
			$Value = CForm::MakeSafe($Value);

			$Content = "
			<tr class='{$DisplayType}'>
				<td align='right' class='CForm_Name' valign='top'><b onMouseOver=\"CTooltip.Show('".self::$Tooltip."');\" onMouseOut=\"CTooltip.Hide();\">$Name:</b>&nbsp;</td>
				<td class='CForm_Value' valign='top'><textarea title='".$Error."' name='".self::$Prefix.$FormName."' id='".self::$Prefix.$FormName."' class='CForm_Textarea'>$Value</textarea></td>
			</tr>
			";

			CForm::ResetTooltip();

			return $Content;
		}

		public static function AddFCKEditor($FormName, $Value) {
			return self::AddRTE($FormName, $Value);
		}

		public static function AddRTE($FormName, $Value, $Type = "Advanced", $Height = 200) {
			$Value = CForm::MakeSafe($Value);

			if($Type != "Advanced" && $Type != "Basic") {
				$Type = "Advanced";
			}

			$Config = Array(
				"toolbar" => $Type,
				"height"  => $Height."px"
			);

			$Content = "
			<tr>
				<td class='CForm_Value' valign='top' colspan='2'>
				<textarea rel='CForm_RTE' name='".self::$Prefix.$FormName."' id='".self::$Prefix.$FormName."' style='width: 100%; height: 100px;'>$Value</textarea>
				<script language='Javascript'>CForm.AddRTEControl('".self::$Prefix.$FormName."', ".json_encode($Config).");</script>
				</td>
			</tr>
			";

			CForm::ResetTooltip();

			return $Content;
		}

		public static function AddColorPicker($Name, $FormName, $Value) {
			if($Value == "") $Value = "FFFFFF";

			$Content = "
			<tr>
				<td align='right' class='CForm_Name' valign='top'><b onMouseOver=\"CTooltip.Show('".self::$Tooltip."');\" onMouseOut=\"CTooltip.Hide();\">$Name:</b>&nbsp;</td>
				<td class='CForm_Value' valign='top'>
				<div id='".self::$Prefix.$FormName."_ColorPicker' class='ColorPicker_Select'><div class='ColorPicker_Select_Inner' style='background-color: #$Value'></div></div>
				<input type='hidden' name='".self::$Prefix.$FormName."' value='$Value' id='".self::$Prefix.$FormName."' value='$Value'/>
				<script language='Javascript'>$('#".self::$Prefix."$FormName"."_ColorPicker').ColorPicker({
					color: '$Value',
					onShow: function (colpkr) { $(colpkr).fadeIn(500); return false; },
					onHide: function (colpkr) {	$(colpkr).fadeOut(500);	return false; },
					onChange: function (hsb, hex, rgb) { $('#".self::$Prefix."$FormName"."_ColorPicker div').css('backgroundColor', '#' + hex); $('#".self::$Prefix."$FormName').attr('value', hex); }
				});
				</script>
				</td>
			</tr>
			";

			CForm::ResetTooltip();

			return $Content;
		}

		public static function AddCheckbox($Name, $FormName, $Checked, $Error = "") {
			$Content = "
			<tr>
				<td align='right' class='CForm_Name' valign='top'><b onMouseOver=\"CTooltip.Show('".self::$Tooltip."');\" onMouseOut=\"CTooltip.Hide();\">$Name:</b>&nbsp;</td>
				<td class='CForm_Value' valign='top'><input type='checkbox' title='".$Error."' name='".self::$Prefix.$FormName."' id='".self::$Prefix.$FormName."'";

			if($Checked) {
				$Content .= "checked='checked'";
			}
				
			$Content .= "/></td>
			</tr>
			";

			CForm::ResetTooltip();

			return $Content;
		}

		public static function AddDropdown($Name, $FormName, $Values, $SelectedValue, $Error = "") {
			$Content = "";

			if(self::$Format == "Table") {
				$Content .= "
				<tr>
					<td align='right' class='CForm_Name' valign='top'><b onMouseOver=\"CTooltip.Show('".self::$Tooltip."');\" onMouseOut=\"CTooltip.Hide();\">$Name:</b>&nbsp;</td>
					<td class='CForm_Value' valign='top'>
					";
			}

			if(self::$Format == "Stacked") {
				$Content .= "
				<tr>
					<td class='CForm_Value' valign='top'><b>$Name</b><br/>";
			}

			$Content .= "<select name='".self::$Prefix.$FormName."' id='".self::$Prefix.$FormName."' title='".$Error."' style='width:300px;'>";

			foreach($Values as $Key => $Value) {
				$Content .= "<option value='$Key'";

				if($Key == $SelectedValue) {
					$Content .= "selected='selected'";
				}

				$Content .= ">$Value</option>";
			}

			$Content .= "</select>";

			if(self::$Format == "Table") {
				$Content .= "					
					</td>
				</tr>
				";
			}

			if(self::$Format == "Stacked") {
				$Content .= "					
					</td>
				</tr>
				";
			}
			
			$Content .= "<script>
            $(function(){
				setTimeout(function() { $('#".self::$Prefix.$FormName."').select2({ minimumResultsForSearch : 20 });},5);
            });
            </script>";

			CForm::ResetTooltip();

			return $Content;
		}

		public static function AddGroupDropdown($Name, $FormName, $Values, $SelectedValue, $Error = "") {
			$Content = "
			<tr>
				<td align='right' class='CForm_Name' valign='top'><b onMouseOver=\"CTooltip.Show('".self::$Tooltip."');\" onMouseOut=\"CTooltip.Hide();\">$Name:</b>&nbsp;</td>
				<td class='CForm_Value' valign='top'>
				<select name='".self::$Prefix.$FormName."' id='".self::$Prefix.$FormName."' title='".$Error."' class='CForm_Dropdown'>";

			foreach($Values as $Name => $ParentArray) {
				if(is_array($ParentArray)) {
					$Content .= "<optgroup label='$Name'>";
	
					foreach($ParentArray as $Key => $Value) {
						$Content .= "<option value='$Key'";
	
						if($Key == $SelectedValue) {
							$Content .= " selected='selected'";
						}
	
						$Content .= ">$Value</option>";
					}
	
					$Content .= "</optgroup>";
				} else {
					$Content .= "<option value='$Name'";

					if($Key == $SelectedValue) {
						$Content .= " selected='selected'";
					}

					$Content .= ">$ParentArray</option>";
				}
			}

			$Content .="
				</select>
				</td>
			</tr>
			";

			CForm::ResetTooltip();

			return $Content;
		}

		public static function AddListbox3($Name, $FormName, $Values, $SelectedValues = Array(), $Error = "") {
			$Content = "
			<tr>
				<td align='right' class='CForm_Name' valign='top'><b onMouseOver=\"CTooltip.Show('".self::$Tooltip."');\" onMouseOut=\"CTooltip.Hide();\">$Name:</b>&nbsp;</td>
				<td class='CForm_Value' valign='top'>
				<select name='".self::$Prefix.$FormName."' id='".self::$Prefix.$FormName."' title='".$Error."' multiple='multiple' class='CForm_Listbox' class='CForm_Dropdown'>";

			foreach($Values as $Key => $Value) {
				$Content .= "<option value='$Key'";

				if(is_array($SelectedValues) && in_array($Key, $SelectedValues)) {
					$Content .= "selected='selected'";
				}

				$Content .= ">$Value</option>";
			}

			$Content .= "
				</select>
				</td>
			</tr>
			";

			CForm::ResetTooltip();

			return $Content;
		}
		
		public static function AddListbox($Name, $FormName, $Values, $SelectedValues = Array(), $ValidateRule = "", $Extra = '', $Placeholder = '', $DisplayType = "Basic", $Style = "") {
            $Content = "<select name='".self::$Prefix.$FormName."' id='".self::$Prefix.$FormName."' title='".$Error."' multiple='multiple' class='' style='width: 200px;' $Extra>";

            foreach($Values as $Key => $Value) {
                $Content .= "<option value='$Key'";

                if(is_array($SelectedValues) && in_array($Key, $SelectedValues)) {
                    $Content .= "selected='selected'";
                }

                $Content .= ">$Value</option>";
            }

            $Content .= "</select>";
            $Content .= "<script>
            $(function(){
            setTimeout(function() { $('#".self::$Prefix.$FormName."').select2({minimumResultsForSearch: 20, placeholder: '".$Placeholder."'});},5);
           

            });
            
            </script>";
            if($ValidateRule != "") $Content .= self::AddValidationRule($FormName, $ValidateRule);
            return self::FormatOutput($Name, $Content, array("Name"    => "CForm_Name", "Content" => "CForm_Value"), $DisplayType, $Style);
        }
		
		public static function AddListbox2($Name, $FormName, $Values, $SelectedValues = Array(), $ValidateRule = "", $Extra = '', $Placeholder = '', $DisplayType = "Basic") {
            $Content = "<input name='".self::$Prefix.$FormName."' id='".self::$Prefix.$FormName."' type='text' title='".$Error."' style='width: 300px;' $Extra>";

			$ValuesArray = Array();
            foreach($Values as $Key => $Value) {
                $ValuesArray[] = json_encode(Array("id" => $Key, "text" => $Value));
			}
			/*
            $Content .= "<script>
            $(function(){
				setTimeout(function() {
					$('#".self::$Prefix.$FormName."').select2({
						minimumResultsForSearch		: 20,
						placeholder					: '".$Placeholder."',
						data						: [".str_replace('"id"', 'id', str_replace('"text"', 'text', implode(",", $ValuesArray)))."]
					});
				}, 5);
            });
            
            </script>";
            */
            if($ValidateRule != "") $Content .= self::AddValidationRule($FormName, $ValidateRule);
            return self::FormatOutput($Name, $Content, array("Name"    => "CForm_Name", "Content" => "CForm_Value"), $DisplayType);
        }

		public static function AddDatepicker($Name, $FormName, $Value, $StartYear = "", $EndYear = "", $Error = "", $DisplayType = "Basic") {
			$Value = CForm::MakeSafe($Value);

			if($Value > 0) {
				$Month	= date("m", $Value);
				$Day	= date("d", $Value);
				$Year	= date("Y", $Value);
				$FormattedValue = date('F j, Y', $Value);
			}else{
				$Month	= date("m");
				$Day	= date("d");
				$Year	= date("Y");
				$FormattedValue = '';
			}

			if(!$StartYear) {
				$StartYear = date("Y");
			}

			if(!$EndYear) {
				$EndYear = date("Y") + 10;
			}
			
			$Content = "
			<tr class='{$DisplayType}'>
				<td align='right' class='CForm_Name' valign='top'><b>$Name:</b>&nbsp;</td>
				<td class='CForm_Value' valign='top'><input type='text' name='".self::$Prefix.$FormName."' id='".self::$Prefix.$FormName."' title='".$Error."' class='CForm_Datepicker' readonly='readonly' value='".$FormattedValue."'/></td>
			</tr>
			";
			
			$Content .= "
				<script type='text/javascript'>
					$(document).ready(function(){
						$(\"#".self::$Prefix.$FormName."\").datepicker({
							showOn:				'both' ,
							buttonImageOnly:	true ,
							buttonText:			'' ,
							dateFormat:			'MM d, yy' ,
							minDate:			new Date({$StartYear}, 0, 1) ,
							maxDate:			new Date({$EndYear}, 0, 1)
						});
					});
				</script>
			";

			CForm::ResetTooltip();

			return $Content;
		}

		public static function AddTimestamp($Name, $FormName, $Value, $StartYear = "", $EndYear = "", $Error = "") {
			$Value = CForm::MakeSafe($Value);

			if($Value) {
				$Month	= date("m", $Value);
				$Day	= date("d", $Value);
				$Year	= date("Y", $Value);
			}else{
				$Month	= 0; //date("m");
				$Day	= date("d");
				$Year	= date("Y");
			}

			$Value = mktime(0, 0, 0, $Month, $Day, $Year);

			$Content = "
			<tr>
				<td align='right' class='CForm_Name' valign='top'><b>$Name:</b>&nbsp;</td>
				<td class='CForm_Value' valign='top'>
				<input type='hidden' name='".self::$Prefix.$FormName."' id='".self::$Prefix.$FormName."' value='".$Value."'/>
				";
				
				$Content .= '<select name="'.self::$Prefix.$FormName.'_Month" id="'.self::$Prefix.$FormName.'_Month" onChange="CForm.UpdateTimestamp(\''.self::$Prefix.$FormName.'\');" class="CForm_Dropdown_Timestamp">';

				$Content .= "<option value='0'>-- Select One --</option>";

				for($i = 1;$i <= 12;$i++) {
					$Content .= "<option value='".$i."'";

					if($Month == $i) {
						$Content .= "selected='selected'";
					}
					
					$Content .= ">$i - ".date("F", mktime(0, 0, 0, $i, 1))."</option>";
				}

				$Content .= '</select>';

				$Content .= '<select name="'.self::$Prefix.$FormName.'_Day" id="'.self::$Prefix.$FormName.'_Day" onChange="CForm.UpdateTimestamp(\''.self::$Prefix.$FormName.'\');" class="CForm_Dropdown_Timestamp">';

				for($i = 1;$i <= 31;$i++) {
					$Content .= "<option value='".$i."'";

					if($Day == $i) {
						$Content .= "selected='selected'";
					}
					
					$Content .= ">$i</option>";
				}

				$Content .= '</select>';

				$Content .= '<select name="'.self::$Prefix.$FormName.'_Year" id="'.self::$Prefix.$FormName.'_Year" onChange="CForm.UpdateTimestamp(\''.self::$Prefix.$FormName.'\');" class="CForm_Dropdown_Timestamp">';

				if(!$StartYear) {
					$StartYear = date("Y");
				}

				if(!$EndYear) {
					$EndYear = date("Y") + 10;
				}

				for($i = $StartYear;$i <= $EndYear;$i++) {
					$Content .= "<option value='".$i."'";

					if($Year == $i) {
						$Content .= "selected='selected'";
					}
					
					$Content .= ">$i</option>";
				}

				$Content .= '</select>';		
			
			$Content .= "
				<script type='text/javascript'>CForm.UpdateTimestamp('".self::$Prefix.$FormName."');</script>
				</td>
			</tr>
			";

			CForm::ResetTooltip();

			return $Content;
		}

		public static function AddTimestampHour($Name, $FormName, $Value, $Error = "") {
			$Value = CForm::MakeSafe($Value);

			$Hour	= date("g", $Value);
			$Minute	= date("i", $Value);
			$AMPM	= date("A", $Value);

			$Content = "
			<tr>
				<td align='right' class='CForm_Name' valign='top'><b>$Name:</b>&nbsp;</td>
				<td class='CForm_Value' valign='top'>";
				
				$Content .= '<select name="'.self::$Prefix.$FormName.'_Hour" id="'.self::$Prefix.$FormName.'_Hour" class="CForm_Dropdown_Timestamp">';

				for($i = 1;$i <= 12;$i++) {
					if($i < 10) {
						$t = "0".$i;
					}else{
						$t = $i;
					}

					$Content .= "<option value='".$t."'";

					if($Hour == $i) {
						$Content .= "selected='selected'";
					}
					
					$Content .= ">$t</option>";
				}

				$Content .= '</select>';

				$Content .= '<select name="'.self::$Prefix.$FormName.'_Minute" id="'.$FormName.'_Minute" class="CForm_Dropdown_Timestamp">';

				for($i = 0;$i < 60;$i++) {
					if($i < 10) {
						$t = "0".$i;
					}else{
						$t = $i;
					}

					$Content .= "<option value='".$t."'";

					if($Minute == $i) {
						$Content .= "selected='selected'";
					}
					
					$Content .= ">$t</option>";
				}

				$Content .= '</select>';

				$Content .= '<select name="'.self::$Prefix.$FormName.'_AMPM" id="'.self::$Prefix.$FormName.'_AMPM" class="CForm_Dropdown_Timestamp">';

				$Content .= '<option value="AM">AM</option>';
				$Content .= '<option value="PM"';

				if($AMPM == "PM") {
					$Content .= " selected='selected'";
				}
				
				$Content .= '>PM</option>';

				$Content .= '</select>';		
			
			$Content .= "</td>
			</tr>
			";

			CForm::ResetTooltip();

			return $Content;
		}

		public static function AddYesNo($Name, $FormName, $SelectedValue, $Type = "OnOff", $DisplayType = "Basic", $Style = "") {
			$SelectedValue = intval($SelectedValue);

			$Content = "";

			if(self::$Format == "Table") {
				$Content .= "
				<tr class='{$DisplayType}'>
					<td align='right' class='CForm_Name' style='{$Style}".CForm::$StyleName."' valign='top'><b onMouseOver=\"CTooltip.Show('".self::$Tooltip."');\" onMouseOut=\"CTooltip.Hide();\">$Name:</b>&nbsp;</td>
					<td class='CForm_Value' valign='top' style='{$Style}".CForm::$StyleValue."'>";
			}
			
			$Content .= "<div class='CForm_".$Type."' id='".self::$Prefix.$FormName."_YesNo'></div><input type='hidden' name='".self::$Prefix.$FormName."' id='".self::$Prefix.$FormName."' value='".$SelectedValue."'/>
			<script language='Javascript'>CForm.AddYesNoControl('".self::$Prefix.$FormName."_YesNo', '".self::$Prefix.$FormName."');</script>";

			if(self::$Format == "Table") {
				$Content .= "
					</td>
				</tr>
				";
			}

			CForm::ResetTooltip();

			return $Content;

			//return CForm::AddDropdown($Name, $FormName, $Values, $SelectedValue, $Error);
		}

		public static function AddFileBrowse($Name, $FormName) {
			$Content = "
			<tr>
				<td align='right' class='CForm_Name' valign='top'><b onMouseOver=\"CTooltip.Show('".self::$Tooltip."');\" onMouseOut=\"CTooltip.Hide();\">$Name:</b>&nbsp;</td>
				<td class='CForm_Value' valign='top'><input type='file' name='".self::$Prefix.$FormName."' id='".self::$Prefix.$FormName."' title='".$Error."'/></td>
			</tr>
			";

			CForm::ResetTooltip();

			return $Content;
		}

		public static function AddUpload($Name, $FormName, $Location = "Temp", $Class = '') {
			$Content = "";

			if(self::$Format == "Table") {
				$Content = "
				<tr>
					<td align='right' class='CForm_Name' valign='top'><b onMouseOver=\"CTooltip.Show('".self::$Tooltip."');\" onMouseOut=\"CTooltip.Hide();\">$Name:</b>&nbsp;</td>
					<td class='CForm_Value' valign='top'>";
			}

			$Content .= "
				<input type='text' readonly='true' class='".$Class." CForm_Textbox CForm_Upload_Textbox' name='".self::$Prefix.$FormName."Original' id='".self::$Prefix.$FormName."Original'/>
				<span class='CForm_Upload_Button' id='".self::$Prefix.$FormName."_SWFUpload' rel='SWFUpload'></span>
				<div id='".self::$Prefix.$FormName."_SWFUpload_Icon' class='CForm_Upload_Icon'></div>
				<input type='hidden' name='".self::$Prefix.$FormName."' id='".self::$Prefix.$FormName."' title='".$Error."'/>

				<script language='Javascript'>CForm.AddUploadControl('".self::$Prefix.$FormName."_SWFUpload', '".self::$Prefix.$FormName."', '$Location');</script>
			";

			if(self::$Format == "Table") {
				$Content .= "
					</td>
				</tr>
				";
			}

			CForm::ResetTooltip();

			return $Content;
		}

		public static function AddUploadMultiple($Name, $FormName, $Callback = "null") {
			$Content = "";

			if(self::$Format == "Table") {
				$Content = "
				<tr>
					<td align='right' class='CForm_Name' valign='top'><b onMouseOver=\"CTooltip.Show('".self::$Tooltip."');\" onMouseOut=\"CTooltip.Hide();\">$Name:</b>&nbsp;</td>
					<td class='CForm_Value' valign='top'>";
			}

			$Content .= "
				<div id='".self::$Prefix.$FormName."_SWFUpload_Icon' class='CForm_Upload_Icon'></div>
				<span class='CForm_Upload_Button' id='".self::$Prefix.$FormName."_SWFUpload' rel='SWFUpload'></span>

				<script language='Javascript'>CForm.AddUploadMultipleControl('".self::$Prefix.$FormName."_SWFUpload', '".self::$Prefix.$FormName."', $Callback);</script>
			";

			if(self::$Format == "Table") {
				$Content .= "
					</td>
				</tr>
				";
			}

			CForm::ResetTooltip();

			return $Content;
		}

		public static function AddCC($Name, $FormName, Array $Value, $Error = "") {
			$Value = CForm::MakeSafe($Value);

			$Content = "";

			if(self::$Format == "Table") {
				$Content .= "
				<tr>
					<td align='right' class='CForm_Name' valign='top'><b onMouseOver=\"CTooltip.Show('".self::$Tooltip."');\" onMouseOut=\"CTooltip.Hide();\">$Name:</b>&nbsp;</td>
					<td class='CForm_Value' valign='top'>";
			}

			$Content .= "<input type='text' name='".self::$Prefix.$FormName."1' id='".self::$Prefix.$FormName."1' value='".$Value[0]."' title='".$Error."' maxlength='4' class='CForm_Textbox CForm_CC'/>";
			$Content .= "<input type='text' name='".self::$Prefix.$FormName."2' id='".self::$Prefix.$FormName."2' value='".$Value[1]."' title='".$Error."' maxlength='4' class='CForm_Textbox CForm_CC'/>";
			$Content .= "<input type='text' name='".self::$Prefix.$FormName."3' id='".self::$Prefix.$FormName."3' value='".$Value[2]."' title='".$Error."' maxlength='4' class='CForm_Textbox CForm_CC'/>";
			$Content .= "<input type='text' name='".self::$Prefix.$FormName."4' id='".self::$Prefix.$FormName."4' value='".$Value[3]."' title='".$Error."' maxlength='4' class='CForm_Textbox CForm_CC'/>";
			//$Content .= "<script language='Javascript'>CForm.AddCCControl('".self::$Prefix.$FormName."');</script>";
				
			if(self::$Format == "Table") {
				$Content .= "
					</td>
				</tr>
				";
			}

			CForm::ResetTooltip();

			return $Content;
		}

		public static function AddBlank() {
			$Content = "
			<tr>
				<td colspan='2'><br/></td>
			</tr>
			";

			return $Content;
		}

		public static function AddButton($Text, $Style = "", $OnClick = "") {
			$Content = "<input type='button' onClick=\"$OnClick\" style=\"$Style\" value=\"$Text\"/>";

//			$Content  = "<div class='CForm_Button' style=\"$Style\" onMouseDown=\"CForm.Button.OnMouseDown(this);\" onMouseUp=\"CForm.Button.OnMouseUp(this);\" onClick=\"$OnClick\">";
//				$Content .= "<div class='CForm_Button_Left'></div>";
//				$Content .= "<div class='CForm_Button_Content'>$Text</div>";
//				$Content .= "<div class='CForm_Button_Right'></div>";
//			$Content .= "</div>";

			return $Content;
		}

		public static function AddSubmit($Name, $FormName = "Submit", $FormValue = "") {
			$Content = "
			<tr>
				<td align='center' class='CForm_Submit' valign='top' colspan='2'>
				<br/>
				<input style='padding:0;border:0;' type='image' src='./gfx/buttons/submit.jpg' onMouseOver='this.src=\"./gfx/buttons/submit_over.jpg\"' onMouseOut='this.src=\"./gfx/buttons/submit.jpg\"' name='".self::$Prefix.$FormName."' value='".self::$Prefix.$FormName."'/>
				<input type='hidden' name='".self::$Prefix.$FormName."' value='$FormValue' />
				</td>
			</tr>
			";

			return $Content;
		}

		public static function AddAutocomplete($Name, $FormName, $FormText, $FormValue, $Request, $Type, $Action, $Error = "") {
			$FormText  = CForm::MakeSafe($FormText);
			$FormValue = CForm::MakeSafe($FormValue);
			$URL	   = CForm::MakeSafe($URL);

			$Content = "
			<tr>
				<td align='right' class='CForm_Name' valign='top'><b onMouseOver=\"CTooltip.Show('".self::$Tooltip."');\" onMouseOut=\"CTooltip.Hide();\">$Name:</b>&nbsp;</td>
				<td class='CForm_Value' valign='top'>
				<input type='text' name='".self::$Prefix.$FormName."_Search' id='".self::$Prefix.$FormName."_Search' value='' class='CForm_Autocomplete_Search'/><br/>
				<input type='text' name='".self::$Prefix.$FormName."_Text' id='".self::$Prefix.$FormName."_Text' value='$FormText' class='CForm_Autocomplete_Text' readonly='readonly' alt='".$FormText."' title='".$FormText."'/>
				<input type='hidden' name='".self::$Prefix.$FormName."' id='".self::$Prefix.$FormName."' value='$FormValue' title='".$Error."' rel='Autocomplete'/>
				<script language='Javascript'>CForm.AddAutocomplete('".self::$Prefix.$FormName."', '".$Request."', '".$Type."', '".$Action."');</script>
				</td>
			</tr>
			";

			CForm::ResetTooltip();

			return $Content;
		}

		public static function AddDualListbox($Name, $FormName, $ValuesLeft, $ValuesRight, $Error = "") {
			$Content = "
			<tr>
				<td align='right' class='CForm_Name' valign='top'><b onMouseOver=\"CTooltip.Show('".self::$Tooltip."');\" onMouseOut=\"CTooltip.Hide();\">$Name:</b>&nbsp;</td>
				<td class='CForm_Value' valign='top'>

				<table class='CForm_DualListbox_Table'>
				<tr>
					<td width='50%'>

					<b class='CForm_DualListbox_Title'>Available</b>
					<select id='".self::$Prefix.$FormName."_Left' title='".$Error."' class='CForm_DualListbox' multiple='multiple'>";

			foreach($ValuesLeft as $Key => $Value) {
				$Content .= "<option value='$Key'>$Value</option>";
			}

			$Content .= "
					</select>
					</td>
					<td width='50%'>

					<b class='CForm_DualListbox_Title'>Selected</b>
					<select id='".self::$Prefix.$FormName."_Right' title='".$Error."' class='CForm_DualListbox' multiple='multiple'>";

			foreach($ValuesRight as $Key => $Value) {
				$Content .= "<option value='$Key'>$Value</option>";
			}

			$Content .= "
					</select>
					</td>
				</tr>
				</table>
				<input type='hidden' name='".self::$Prefix.$FormName."' id='".self::$Prefix.$FormName."' title='".$Error."'/>

				<script language='Javascript'>CForm.AddDualListboxControl('".self::$Prefix.$FormName."');</script>
				</td>
			</tr>
			";

			CForm::ResetTooltip();

			return $Content;
		}

		public static function RowsToArray(CTableIterator $Rows, $Column, $IDColumn = "ID") {
			$Return = Array();

			foreach($Rows as $Row) {
				$Return[$Row->{$IDColumn}] = $Row->{$Column};
			}

			return $Return;
		}

		public static function SetTooltip($Content) {
			self::$Tooltip = $Content;
		}

		public static function ResetTooltip() {
			self::$Tooltip = "";
		}

		public static function RandomPrefix() {
			self::$Prefix = md5(microtime());
		}

		public static function SetPrefix($Prefix) {
			self::$Prefix = $Prefix;
		}

		public static function GetPrefix() {
			return self::$Prefix;
		}

		public static function SetFormat($Format) {
			self::$Format = $Format;
		}
		
		public static function AddRecatpcha() {
			require_once(Config::$Options["ReCaptcha"]["LibraryPath"]);			
			
			return recaptcha_get_html(Config::$Options["ReCaptcha"]["Keys"]["Public"]);
		}
	};
?>
