<?
	//==========================================================================
	/*
		Class for Searching through Tables

		This is more of a utility class, as it will print out HTML

		Features:
		- Column Sorting
		- Paging
		- Keyword Searching
		- View Restrictions
		- Export
		- History

		9/16/2009 2:21 PM
		 - Initial version

		2/5/2010 4:03 PM
		 - Updated Logic to handle joining multiple tables, even if the table 
		   being joined doesn't match
	*/
	//==========================================================================
	class CSearch extends CTable {
		public	$ItemList	= Array();

		public $Results		= Array();
		public $MaxView		= 20;

		public $NumRows		= 0;
		public $NumPages	= 0;

		private	$DefaultColumn		= 0;
		private	$DefaultColumnDir	= 0; //0 = ASC, 1 = DESC

		private $OnClick		= "";

		private $Restrictions	= Array();
		
		private $WhereOperator = "AND";
		
		function __construct($Table, $TableBusinessesID = 0) {
			parent::__construct($Table, $TableBusinessesID);
		}

		function OnInit() {
			$Query			= "";
			$Selects		= Array();
			$WhereList		= Array();

			$Where			= "";
			$OrderByWhere	= "";
			$OrderBy		= "";

			if(!isset($_GET["CSearch_OrderBy"])) {
				$_GET["CSearch_OrderBy"] = $this->DefaultColumn;
			}

			if(isset($_GET["CSearch_MaxView"])) {
				$this->MaxView = $_GET["CSearch_MaxView"];
			}else{
				$_GET["CSearch_MaxView"] = $this->MaxView;
			}

			if($this->MaxView < 0) {
				$this->MaxView = 0;
			}

			if(strlen($this->Table) > 0) {
				if($_GET["CSearch_OrderBy"] < 0 || $_GET["CSearch_OrderBy"] >= count($this->ItemList)) {
					$_GET["CSearch_OrderBy"] = 0;
				}

				$Query = "SELECT ";

				$DB = ($this->TableBusinessesID > 0 ? "`ypp_".intval($this->TableBusinessesID)."`." : "");

				$Selects[] = "`".$this->Table."`.*";

				foreach($this->ItemList as $Item) {
					if(strlen($Item->Name) <= 0) continue;
					if($Item->SearchType == CSEARCHCOLUMN_SEARCHTYPE_NOSEARCH) continue;

					if(strlen($Item->FTable) > 0) {
						$FDB = ($Item->FTableBusinessesID > 0 ? "`ypp_".intval($Item->FTableBusinessesID)."`." : "");

						$Selects[] = "(SELECT `".$Item->FColumn."` FROM $FDB`".$Item->FTable."` WHERE `".$Item->FLink."` = `".$Item->Column."`) AS \"".$Item->FTable.".".$Item->FColumn."\"";

						$Item->Column = $Item->FTable.".".$Item->FColumn;
					}
				}

				$Query .= implode(", ", $Selects)." FROM $DB`".$this->Table."` ";

				if(@$_GET["CSearch_Keywords"]) {
					foreach($this->ItemList as $Item) {
						if($Item->SearchType == CSEARCHCOLUMN_SEARCHTYPE_NOSEARCH) continue;

						if($Item->SearchType == CSEARCHCOLUMN_SEARCHTYPE_LOOSE) {
							//if(strlen($Item->FTable) > 0)	$WhereList[] = "\"".$Item->Column."\" LIKE '%".mysql_real_escape_string($_GET["CSearch_Keywords"])."%'";
							$WhereList[] = "`".$Item->Column."` LIKE '%".mysql_real_escape_string($_GET["CSearch_Keywords"])."%'";
						}else
						if($Item->SearchType == CSEARCHCOLUMN_SEARCHTYPE_EXACT) {
							//if(strlen($Item->FTable) > 0)	$WhereList[] = "\"".$Item->Column."\" = '".mysql_real_escape_string($_GET["CSearch_Keywords"])."'";
							$WhereList[] = "`".$Item->Column."` = '".mysql_real_escape_string($_GET["CSearch_Keywords"])."'";
						}
					}
				}

				$KeywordsUsed = false;

				if(count($WhereList) > 0) {
					$Query .= " HAVING (".implode(" || ", $WhereList).")";

					$KeywordsUsed = true;
				}

				$WhereList = Array();

				foreach($this->Restrictions as $Restriction) {
					$Column = $Restriction[0];
					$Value	= mysql_real_escape_string($Restriction[1]);
					$FTable = $Restriction[2];
					$Type	= $Restriction[3];

					if($Type == "Normal") {
						if(strlen($FTable) > 0) {
							$WhereList[] = "`$FTable".".$Column` = '$Value'";
						}else{
							$WhereList[] = "`".$this->Table."`.`$Column` = '$Value'";
						}
					}else{
						$WhereList[] = $Column;
					}
				}

				if(count($WhereList) > 0) {
					if($KeywordsUsed == false) {
						$Query .= " HAVING ";
					}else{
						$Query .= " " . $this->WhereOperator . " ";
					}

					$Query .= implode(" " . $this->WhereOperator . " ", $WhereList);
				}

				$Query .= " ORDER BY ";

				$Item = $this->ItemList[$_GET["CSearch_OrderBy"]];

				$Query .= "`".$Item->Column."`";

				if(!isset($_GET["CSearch_OrderByDir"])) {
					$_GET["CSearch_OrderByDir"] = $this->DefaultColumnDir;
				}

				if($_GET["CSearch_OrderByDir"] == 1)	$Query .= " DESC ";
				else									$Query .= " ASC ";

				$this->NumRows	= count(CTable::OnLoadByQuery($Query));

				if($this->MaxView <= 0) {
					$this->NumPages = 1;
				}else{
					$this->NumPages = ceil($this->NumRows / $this->MaxView);
				}

				if(!isset($_GET["CSearch_Page"]) || $_GET["CSearch_Page"] < 0) {
					$_GET["CSearch_Page"] = 0;
				}else
				if($_GET["CSearch_Page"] >= $this->NumPages) {
					$_GET["CSearch_Page"] = $this->NumPages - 1;
				}

				if($this->MaxView <= 0 || isset($_GET["CSearch_Export"])) {
				}else{
					$Start	= intval($_GET["CSearch_Page"]) * $this->MaxView;
					$End	= $this->MaxView;

					$Query .= " LIMIT $Start, $End";
				}

				if($_SERVER["REMOTE_ADDR"] == "75.145.183.249") {
					echo "<script type='text/javascript'>
						$(function(){
							$('#Debugging').html(\"$Query\");
						});
					</script>";
				}
				
				$Rows = CTable::OnLoadByQuery($Query);
				
				if(isset($_GET["Test"])) {
					echo mysql_error()."<br/>";
					echo $Query;
				}

				if(isset($_GET["CSearch_Export"])) {
					$this->Export();
				}

				$this->SaveHistory();
			}
		}

		function OnRenderTitle($Title, $Options) {
			echo '<div>';

			if(isset($Options["Export"])) {
				echo CForm::AddButton("Export", "float: right;", "document.location.href = '".CURL::FormatURL("", Array("CSearch_Export" => 1))."'");
			}

			if(isset($Options["Buttons"])) {
				foreach($Options["Buttons"] as $Button) {
					echo CForm::AddButton($Button["Name"], "float: right;", $Button["OnClick"]);
				}
			}
			
			if(isset($Options["Dropdowns"])) {
				foreach($Options["Dropdowns"] as $Name => $Option) {
					echo "<div style='float: right; margin: 4px;'>";
					echo "<select name=\"$Name\" onChange=\"document.location.href = '".CURL::FormatURL("", Array())."&".$Name."=' + this.value;\">";

					foreach($Option as $DropValue => $DropName) {
						if(is_array($DropName)) {
							echo "<optgroup label='$DropValue'/>";

							foreach($DropName as $DropValue2 => $DropName2) {
								echo "<option value=\"$DropValue2\"";

								if($DropValue2 == $_GET[$Name]) {
									echo " selected='selected'";
								}

								echo ">".$DropName2."</option>";
							}

							echo "</optgroup>";
						}else{
							echo "<option value=\"$DropValue\"";

							if($DropValue == $_GET[$Name]) {
								echo " selected='selected'";
							}

							echo ">".$DropName."</option>";
						}
					}

					echo "</select>";
					echo "</div>";
				}
			}

			if(isset($Options["MaxView"])) {
				echo "
				<div style='float: right; margin: 4px;'>
				<select name='CSearch_MaxView' onChange=\"document.location.href = '".CURL::FormatURL("", Array())."&CSearch_MaxView=' + this.value;\">
					<option value='0'>-- All --</option>
				";

				for($i = 5;$i <= 100;$i+=5) {
					echo "<option value='$i'";

					if($i == @$_GET["CSearch_MaxView"]) {
						echo " selected='selected'";
					}
					
					echo ">$i</option>";
				}

				echo "
				</select>
				</div>
				";
			}

			if(isset($Options["Search"])) {
				echo "<div style='float: right; margin: 4px;'>";
				echo "<input type='input' name='CSearch_Keywords' style='margin-right: 1px;' onKeyUp=\"if(CKeyboard.GetKey(event) == 'KS_Enter') { document.location.href = '".CURL::FormatURL("", Array())."&CSearch_Keywords=' + this.value;}\" value=\"".htmlspecialchars(@$_GET["CSearch_Keywords"])."\"/>";
				echo "</div>";
			}

			if(isset($Options["Custom"])) {
				foreach($Options["Custom"] as $Content) {
					echo "<div style='margin: 4px; float: right;'>".$Content."</div>";
				}
			}

			echo '
				<div class="PageTitle">'.($Title).'</div>
			</div>
			<div class="HR_PageTitle"></div>
			';
		}

		function OnRender($TableStyle = "", $CellStyle = "") {
			$Return = "";
			echo "<table class='CSearch_Results' cellspacing='0' cellpadding='2' style='$TableStyle'>\n";

			echo "<tr class='CSearch_Header'>\n";
			//echo "\t<td class='CSearch_Header_Cell' valign='top'><div class='CSearch_Spacer_Left'></div><div class='CSearch_Spacer_Right'></div><input type='checkbox' class='CSearch_Header_Checkbox'/></td>\n";
			
			$i = 0;
			foreach($this->ItemList as $Item) {
				if($Item->Hidden) continue;

				$Header = $Item->Name;

				$Sort = "";

				if($i == $_GET["CSearch_OrderBy"]) {
					$Dir = (($_GET["CSearch_OrderByDir"] + 1) % 2);

					if($Dir == 0) {
						$Sort = "CSearch_Sort_Down";
					}else{
						$Sort = "CSearch_Sort_Up";
					}
				}else{
					$Dir = 0;
				}

				$HeaderValue = "<a href='".CURL::FormatURL("", Array("CSearch_OrderBy" => $i, "CSearch_OrderByDir" => $Dir))."'>$Header</a>";

				if($this->Rows && $this->Rows->ColumnExists($Item->Column) == false) {
					$HeaderValue = "$Header";
				}

				echo "\t<th class='CSearch_Header_Cell ".($i == 0 ? "CSearch_Header_Cell_First" : "")."' style='width: ".$Item->Width.";'>";

				if(strlen($Sort) > 0) {
					echo "<div class='$Sort'></div>";
				}
				
				//
				
				echo $HeaderValue.($i == 0 && $this->Rows ? "" : "")."</th>\n";

				++$i;
			}

			echo "</tr>\n";
			//echo "<tr>\n";
			//	echo "<td colspan='".count($this->ItemList)."' align='center'><div class='HR_Dashes'></div></td>";
			//echo "</tr>\n";

			if(!$this->Rows || count(end($this->Rows)) <= 0) {
				echo "<tr>\n";
					echo "<td colspan='".count($this->ItemList)."' align='center' class='CSearch_NoResults' style='$CellStyle'>No results found.</td>";
				echo "</tr>\n";
			}

			if($this->Rows) {
				$i = 0;
				foreach($this->Rows as $Row) {
					$TempOnClick = $this->OnClick;

					preg_match_all("/\[\[([a-zA-Z0-9 _\-]+)\]\]/", $TempOnClick, $Matches);

					foreach($Matches[0] as $Value) {
						$ColumnName = str_replace("[[", "", $Value);
						$ColumnName = str_replace("]]", "", $ColumnName);

						$TempOnClick = str_replace($Value, $Row->{$ColumnName}, $TempOnClick);
					}

					if($i % 2 == 0) {
						echo "<tr class='CSearch_Row' onMouseOver=\"this.className = 'CSearch_Row_Over';\" onMouseOut=\"this.className = 'CSearch_Row';\" onClick=\"$TempOnClick\" style='".($TempOnClick != "" ? "cursor: pointer;" : "")."'>\n";
					}else{
						echo "<tr class='CSearch_Row2' onMouseOver=\"this.className = 'CSearch_Row_Over';\" onMouseOut=\"this.className = 'CSearch_Row2';\" onClick=\"$TempOnClick\" style='".($TempOnClick != "" ? "cursor: pointer;" : "")."'>\n";
					}

					//echo "\t<td class='CSearch_Cell'><input type='checkbox' class='CSearch_Cell_Checkbox'/></td>\n";

					$j = 0;
					foreach($this->ItemList as $Item) {
						if($Item->Hidden) continue;

						$ColumnValue = self::GetColumnValue($Item, $Row);

						if(strlen($ColumnValue) <= 0) {
							$ColumnValue = "&nbsp;";
						}

						echo "\t\t<td class='".($j == 0 ? "CSearch_Cell_First" : "CSearch_Cell")."' align='".($j == 0 ? "left" : "left")."' style='$CellStyle'>".$ColumnValue."</td>\n";
						$j++;
					}

					echo "</tr>\n";

					++$i;
				}
			}

			echo "</table>\n";
			
			//return $Return;
		}

		//---------------------------------------------------------------------
		function OnRenderPages() {
			if($this->NumResults() <= 0) return;

			/*echo "<div class='CSearch_Pages'>";

			if($_GET["CSearch_Page"] > 2) {
				echo "<div class='CSearch_Pages_Number' onClick=\"document.location.href = '".CURL::FormatURL("", Array("CSearch_Page" => 0))."';\">1 ... </div>";
			}

			for($i = $_GET["CSearch_Page"] - 2;$i < $_GET["CSearch_Page"] + 2;$i++) {
				if($i < 0 || $i >= $this->NumPages) continue;

				if($i == $_GET["CSearch_Page"]) {
					$Class = "CSearch_Pages_Number_Active";
				}else{
					$Class = "CSearch_Pages_Number";
				}

				echo "<div class='$Class' onClick=\"document.location.href = '".CURL::FormatURL("", Array("CSearch_Page" => $i))."';\">".($i + 1)."</div>";
			}

			if($_GET["CSearch_Page"] < $this->NumPages - 2) {
				echo "<div class='CSearch_Pages_Number' onClick=\"document.location.href = '".CURL::FormatURL("", Array("CSearch_Page" => $this->NumPages - 1))."';\"> ... ".$this->NumPages."</div>";
			}

			echo "</div>";*/

			$NumPages = $this->NumPages;

			if($NumPages <= 0) $NumPages = 1;

			//echo "<div class='HR_Dashes'></div>";
			echo "<div class='CSearch_Pages'>";
				echo "<div class='CSearch_Pages_Previous' onClick=\"document.location.href = '".CURL::FormatURL("", Array("CSearch_Page" => $_GET["CSearch_Page"] - 1))."';\"></div>";
				echo "<div class='CSearch_Pages_NumPages'><input type='text' class='CForm_Textbox CSearch_Pages_Textbox' value='".($_GET["CSearch_Page"] + 1)."' onKeyDown=\"if(CKeyboard.GetKey(event) == 'KS_Enter') { document.location.href = '".CURL::FormatURL("", Array())."&CSearch_Page=' + (parseInt(this.value) - 1);}\"/>&nbsp;of ".$NumPages."</div>";
				echo "<div class='CSearch_Pages_Next' onClick=\"document.location.href = '".CURL::FormatURL("", Array("CSearch_Page" => $_GET["CSearch_Page"] + 1))."';\"></div>";
				echo "<div class='CSearch_Pages_NumResults'>".$this->NumRows." Rows</div>";
			echo "</div>";
			
			//return $Return;
		}

		//======================================================================
		function GetColumnValue($Item, $Row) {
			$ColumnValue = "";

			if(isset($Row->{$Item->Column})) {
				$ColumnValue = $Row->{$Item->Column};
			}else{
				$Content = $Item->Column;

				$Res = preg_match_all("/\[\[([a-zA-Z0-9 _\-]+)\]\]/", $Content, $Matches);

				if($Res <= 0 || $Res === false) {
				}else{
					foreach($Matches[0] as $Value) {
						$ColumnName = str_replace("[[", "", $Value);
						$ColumnName = str_replace("]]", "", $ColumnName);

						$Content = str_replace($Value, $Row->{$ColumnName}, $Content);
					}

					$ColumnValue = $Content;
				}
			}

			if($Item->Callback != "" && is_callable($Item->Callback)) {
				$ColumnValue = call_user_func($Item->Callback, $ColumnValue, $Row);
			}

			return $ColumnValue;
		}

		//======================================================================
		function Export() {
			$Filename = "./Temp/".$this->Table."-".time().".php";

			$FileHandle = fopen($Filename, "w");

			fwrite($FileHandle, "<?
				Header(\"Content-type: application/vnd.ms-excel\");
				Header(\"Content-disposition: attachment;filename=".$this->Table."-".time().".csv\");
			?>");

			$i = 0;
			foreach($this->ItemList as $Item) {
				$Header = $Item->Name;

				$Header = strip_tags($Header);
				$Header = str_replace('"', "'", $Header);

				fwrite($FileHandle, '"'.$Header.'"');

				if($i < count($this->ItemList) - 1) {
					fwrite($FileHandle, ",");
				}else{
					fwrite($FileHandle, "\n");
				}

				++$i;
			}

			foreach($this->Rows as $Row) {
	
				$j = 0;
				foreach($this->ItemList as $Item) {
					$ColumnValue = self::GetColumnValue($Item, $Row);

					$ColumnValue = strip_tags($ColumnValue);
					$ColumnValue = str_replace('"', "'", $ColumnValue);

					fwrite($FileHandle, '"'.$ColumnValue.'"');

					if($j < count($this->ItemList) - 1) {
						fwrite($FileHandle, ",");
					}else{
						fwrite($FileHandle, "\n");
					}

					++$j;
				}
			}

			fclose($FileHandle);

			echo "<script>window.open('$Filename', 'Export');</script>";

			$Redirect = CURL::FormatURL("", Array());

			CURL::GoBack();
		}

		//=====================================================================
		function SetDefaultColumn($ID, $Dir = 0) {
			if($ID < 0 || $ID >= count($this->ItemList)) return false;

			$this->DefaultColumn	= $ID;
			$this->DefaultColumnDir = $Dir;

			return true;
		}

		//---------------------------------------------------------------------
		function SetOnClick($Content) {
			$this->OnClick = $Content;
		}

		//---------------------------------------------------------------------
		function SaveHistory() {
			if(strlen($this->Table) <= 0) return;

			if(!isset($_SESSION["CSearch"])) {
				$_SESSION["CSearch"] = Array();
			}

			if(!isset($_SESSION["CSearch_Order"])) {
				$_SESSION["CSearch_Order"] = Array();
			}

			if(!isset($_SESSION["CSearch"][$this->Table])) {
				$_SESSION["CSearch"][$this->Table] = Array();
			}

			$Item = end($_SESSION["CSearch"][$this->Table]);

			$URL = CURL::FormatURL("", Array());

			if($Item["URL"] == $URL) return;

			$_SESSION["CSearch"][$this->Table][] = Array(
				"Timestamp" => mktime(),
				"Name"		=> $this->Table,
				"Keywords"	=> @$_GET["CSearch_Keywords"],
				"URL"		=> $URL
			);

			end($_SESSION["CSearch"][$this->Table]);

			$_SESSION["CSearch_Order"][] = Array(
				"Table" => $this->Table,
				"ID"	=> key($_SESSION["CSearch"][$this->Table])
			);
		}

		function NumResults() {
			return count(end($this->Results));
		}

		//=====================================================================
		// Static Methods
		//=====================================================================
		function GetHistoryOrder() {
			return @$_SESSION["CSearch_Order"];
		}

		function GetHistory($Table, $ID = 0) {
			if(!isset($_SESSION["CSearch"])) return false;
			if(!isset($_SESSION["CSearch"][$Table])) return false;

			$HistoryArray = $_SESSION["CSearch"][$Table];

			reset($HistoryArray);

			if($ID <= 0 || array_key_exists($ID, $HistoryArray) == false) {
				$Item = end($HistoryArray);
			}else{
				$Item = $HistoryArray[$ID];
			}

			return $Item;
		}

		function GetHistoryURL($Table, $ID = 0) {
			$Item = CSearch::GetHistory($Table, $ID);

			if($Item === false) return "";

			return $Item["URL"];
		}

		//=====================================================================
		// Add Methods
		//=====================================================================
		function AddColumn($Name, $Column, $Width, $SearchType = 0, $FTable = "", $FLink = "", $FColumn = "", $Callback = "", $FTableBusinessesID = 0) {
			$Item = new CSearchColumn();

			$Item->Name		= $Name;
			$Item->Column	= $Column;

			$Item->Width		= $Width;
			$Item->SearchType	= $SearchType;

			$Item->FTableBusinessesID = $FTableBusinessesID;
			$Item->FTable	= $FTable;
			$Item->FLink	= $FLink;
			$Item->FColumn	= $FColumn;

			$Item->Callback	= $Callback;

			$this->ItemList[] = $Item;
		}
		
		function AddHiddenColumn($Column, $SearchType = 0) {
			$Item = new CSearchColumn();

			$Item->Column		= $Column;
			$Item->SearchType	= $SearchType;
			$Item->Hidden		= true;

			$this->ItemList[]	= $Item;
		}

		function AddRestriction($Column, $Value, $FTable = "", $Type = "Normal") {
			$this->Restrictions[] = Array($Column, $Value, $FTable, $Type);
		}
		
		function SetWhereOperator($Value) {
			$this->WhereOperator = $Value;
		}
	};

	//=========================================================================
?>
