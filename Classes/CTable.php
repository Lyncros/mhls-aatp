<?
	//==========================================================================
	/*
		Class for manipulating tables within mySQL, can also be used to be 
		extended onto a class to load columns.

		4/10/2009
	*/
	//==========================================================================
	/**
	 *
	 *
	 *
	 *
	 */
	class CTable {
		protected	$Table = "";
		
		/**
		 *
		 *
		 *
		 *
		 */
		public		$Rows = 0;

		/**
		 *
		 *
		 *
		 *
		 */
		function __construct($Table) {
			$this->Table				= $Table;
		}

		/**
		 *
		 *
		 *
		 *
		 */
		function __destruct() {
			unset($this->Rows);
		}

		/**
		 *
		 *
		 *
		 *
		 */
		function OnLoadAll($Extra = "") {
			$Query = "SELECT * FROM `".$this->Table."`";

			return $this->OnLoadByQuery($Query." ".$Extra);
		}

		/**
		 *
		 *
		 *
		 *
		 */
		function OnLoadByID($ID, $Extra = "") {
			$ID = intval($ID);

			$Query = "SELECT * FROM `".$this->Table."` WHERE `ID` = $ID";

			return $this->OnLoadByQuery($Query." ".$Extra);
		}

		/**
		 *
		 *
		 *
		 *
		 */
		function OnLoadByIDs($IDs, $Extra = "") {
			$IDsAsString = implode(', ',$IDs);

			$Query = "SELECT * FROM `".$this->Table."` WHERE `ID` IN ($IDsAsString)";

			return $this->OnLoadByQuery($Query." ".$Extra);
		}
		
		/**
		 *
		 *
		 *
		 *
		 */
		function OnLoadByQuery($Query) {
			if(($Res = mysql_query($Query)) === false) {
				trigger_error("mySQL Error: ".mysql_error()." - ".$Query, E_USER_WARNING);
				return false;
			}

			if(mysql_num_rows($Res) <= 0) {
				return false;
			}

			if(!isset($this)) {				
				return new CTableIterator($Res);
			}else{
				return ($this->Rows = new CTableIterator($Res));
			}
		}

		//=====================================================================
		/**
		 *
		 *
		 *
		 *
		 */
		function OnSave() {
		}
        
        function Save($ID, $Data, $Extra = null) {
            if ($ID > 0) {                
                if ($Extra) {                    
                    $Data["Modified"] = time();
                    $Data["ModifiedUsersID"] = $Extra["UsersID"];
                    $Data["ModifiedIPAddress"] = $Extra["IPAddress"];
                }
                return $this->UpdateExistent($ID, $Data);
            } else {
                if ($Extra) 
                {
                    $Data["Created"] = time();
                    $Data["CreatedUsersID"] = $Extra["UsersID"];
                    $Data["CreatedIPAddress"] = $Extra["IPAddress"];
                }
                return $this->AddNew($Data);
            }
            
            return false;
        }
        
        function AddNew($Data) {
            return self::Add($this->Table, $Data);
        }
        
        function UpdateExistent($ID, $Data) {
            return self::Update($this->Table, $ID, $Data);
        }

		//---------------------------------------------------------------------
		/**
		 *
		 *
		 *
		 *
		 */
		function OnDelete() {
			return CTable::Delete($this->Table, $this->ID);
		}
        
        function OnDeleteLogic($ID, $UsersID, $RemoteIP) {
			$Data = Array(
				"Deleted"			=> time(),
				"DeletedUsersID"	=> $UsersID,
				"DeletedIPAddress"	=> $RemoteIP,
			);

            return CTable::Update($this->Table, $ID, $Data);
        }

		//=====================================================================
		// Parses all Data with CDataParser, replaces Iterator with a 
		// CDataParserIterator object
		/**
		 *
		 *
		 *
		 *
		 */
		function Parse() {
			$this->Rows = CDataParser::Parse($this->Rows); //Note, becomes CDataParserIterator, not CTableIterator
		}

		//=====================================================================
		/**
		 *
		 *
		 *
		 *
		 */
		function __get($Variable) {
			return $this->Rows->$Variable;
		}

		//---------------------------------------------------------------------
		/**
		 *
		 *
		 *
		 *
		 */
		function __set($Variable, $Value) {
			return ($this->Rows->$Variable = $Value);
		}

		//=====================================================================
		/**
		 *
		 *
		 *
		 *
		 */
		 function ToString($Column)
		 {
			if($this->Rows != 0)
				return $this->Rows->ToString($Column);
			else
				return '';
		 }
		 
		//=====================================================================
		// Static members
		//=====================================================================
        
		/**
		 *
		 *
		 *
		 *
		 */
        public static function SelectFields($Fields, $Table, $Extra) {
            $Query = "SELECT $Fields FROM `$Table` $Extra";
			//die(var_export($Query));
            if(($Res = mysql_query($Query)) === false) {
                trigger_error("mySQL Error: ".mysql_error()." - ".$Query, E_USER_WARNING);
                return false;
            }
            return new CTableIterator($Res);              
        }
		
		/**
		 *
		 *
		 *
		 *
		 */
        public static function Select($Table, $Extra) {
            $Query = "SELECT * FROM `$Table` $Extra";
			
            if(($Res = mysql_query($Query)) === false) {
                trigger_error("mySQL Error: ".mysql_error()." - ".$Query, E_USER_WARNING);
                return false;
            }
            return new CTableIterator($Res);              
        }
        
		/**
		 *
		 *
		 *
		 *
		 */
        public static function SelectById($Table, $Id) {
            $Query = "SELECT * FROM `$Table` WHERE ID = $Id";
			
            if(($Res = mysql_query($Query)) === false) {
                trigger_error("mySQL Error: ".mysql_error()." - ".$Query, E_USER_WARNING);
                return false;
            }
			$Result = new CTableIterator($Res);
            return $Result->Current;
        }
		
		/**
		 *
		 *
		 *
		 *
		 */
		public static function Add($Table, $Data) {
			if(is_array($Data) == false) {
				trigger_error($Table." :: Passed Data not Array: ".$Data, E_USER_WARNING);
				return false;
			}

			$Query = "INSERT INTO `".$Table."` (";

			reset($Data);
			for($i = 0;$i < count($Data);$i++) {
				$Query .= "`".key($Data)."`";

				if($i < count($Data) - 1) {
					$Query .= ", ";
				}

				next($Data);
			}

			$Query .= ") VALUES (";

			reset($Data);
			for($i = 0;$i < count($Data);$i++) {
				if(is_array(current($Data))) {
					$Current = current($Data);

					//Type
					switch($Current[2]) {
						default: {
							$Query .= $Current[0]."('".mysql_real_escape_string($Current[1])."')";
							break;
						}
					}
				}else
				//if(is_null(current($Data))) {
				//	$Query .= "NULL";
				//}else{
					$Query .= "'".mysql_real_escape_string(current($Data))."'";
				//}

				if($i < count($Data) - 1) {
					$Query .= ", ";
				}

				next($Data);
			}

			$Query .= ");";     
            
			if(mysql_query($Query) !== false) {
				return mysql_insert_id();
			}

			//echo $Query."<br>";
			//echo mysql_error()."<br>";

			trigger_error("mySQL Error: ".mysql_error()." - ".$Query, E_USER_WARNING);

			return false;
		}

		/**
		 *
		 *
		 *
		 *
		 */
		public static function Update($Table, $ID, $Data) {
			if(is_array($Data) == false) {
				trigger_error($Table." :: Passed Data not Array: ".$Data, E_USER_WARNING);
				return false;
			}

			$ID = intval($ID);

			$Query = "UPDATE `".$Table."` SET ";

			reset($Data);
			for($i = 0;$i < count($Data);$i++) {
				if(is_array(current($Data))) {
					$Current = current($Data);

					$Query .= "`".key($Data)."` = ";

					//Type
					switch($Current[2]) {
						default: {
							$Query .= $Current[0]."('".mysql_real_escape_string($Current[1])."')";
							break;
						}
					}
				}else
				if(is_null(current($Data))) {
					$Query .= "`".key($Data)."` = NULL";
				}else{
					$Query .= "`".key($Data)."` = '".mysql_real_escape_string(current($Data))."'";
				}

				if($i < count($Data) - 1) {
					$Query .= ", ";
				}

				next($Data);
			}

			$Query .= " WHERE ID = $ID;";

			if(mysql_query($Query) !== false) {
				return true;
			}

			trigger_error("mySQL Error: ".mysql_error()." - ".$Query, E_USER_WARNING);

			return false;
		}

		/**
		 *
		 *
		 *
		 *
		 */
		public static function Delete($Table, $ID) {
			$ID = intval($ID);

			$Query = "DELETE FROM `".$Table."` WHERE ID = $ID;";

			if(mysql_query($Query) !== false) {
				return true;
			}			

			trigger_error("mySQL Error: ".mysql_error()." - ".$Query, E_USER_WARNING);

			return false;
		}

		/**
		 *
		 *
		 *
		 *
		 */
		public static function RunQuery($Query) {
			if(mysql_query($Query) !== false) {
				return true;
			}

			trigger_error("mySQL Error: ".mysql_error()." - ".$Query, E_USER_WARNING);

			return false;
		}

		/**
		 *
		 *
		 *
		 *
		 */
		public static function NumRows($Table, $Extra) {
			$Query = "SELECT COUNT(*) as 'NumRows' FROM `$Table` $Extra";

			if(($Res = mysql_query($Query)) === false) {
				trigger_error("mySQL Error: ".mysql_error()." - ".$Query, E_USER_WARNING);
				return false;
			}

			if(mysql_num_rows($Res) <= 0) {
				return false;
			}

			$Rows = mysql_fetch_assoc($Res);

			return $Rows["NumRows"];
		}

		/**
		 *
		 *
		 *
		 *
		 */
		public static function GetSum($Table, $Column, $Extra) {
			$Query = "SELECT SUM(`$Column`) as 'Sum' FROM `$Table` $Extra";

			if(($Res = mysql_query($Query)) === false) {
				trigger_error("mySQL Error: ".mysql_error()." - ".$Query, E_USER_WARNING);
				return false;
			}

			if(mysql_num_rows($Res) <= 0) {
				return false;
			}

			$Rows = mysql_fetch_assoc($Res);

			return $Rows["Sum"];
		}

		/**
		 *
		 *
		 *
		 *
		 */
		public static function RemoveMagicQuotes($Value) {
			return is_array($Value) ? 
				array_map(array("CTable", "RemoveMagicQuotes"), $Value) :
				(
					get_magic_quotes_gpc() ?
		            stripslashes($Value) :
					$Value
				);
		}

		/**
		 *
		 *
		 *
		 *
		 */
		public static function Sanitize($Value) {
			return is_array($Value) ? 
				array_map(array("CTable", "Sanitize"), $Value) :
				(
					get_magic_quotes_gpc() ?
		            mysql_real_escape_string(stripslashes($Value)) :
					mysql_real_escape_string($Value)
				);
		}

		/**
		 *
		 *
		 *
		 *
		 */
		public static function Unsanitize($Value) {
			return is_array($Value) ? 
				array_map(array("CTable", "Unsanitize"), $Value) : stripslashes($Value);
		}
	};

	//=========================================================================
?>
