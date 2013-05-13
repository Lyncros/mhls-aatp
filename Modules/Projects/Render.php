<?
	$Settings = $this->Parent->Settings;

	$OneDay		= 60 * 60 * 24;

	$DayOfWeek	= date("w", $Date);
	$Date		= mktime(2, 0, 0, date("m", $Date), date("d", $Date) - $DayOfWeek, date("Y", $Date));
?>


	

<?
	function OnCreated($Value) {
		return date('n/j/Y', strtotime($Value));
	}
	
	function OnUser($Value) {
		$User = new CUsers();
		$User->OnLoad($Value);
		return $User->LastName . ", " . $User->FirstName;
	}
	
	function OnFilename($Value) {
		return "<a href='".$_SERVER["REQUEST_URI"]."ResourcesID=".$Value."' target='_blank'>Download</a>";
	}

	function OnRow($Value, $Obj) {
		$Project = new CProjects();
		$Project->OnLoad($Obj->ID);
		
		/*if($Project->GetLastTouchedDays() >= 1)*/ $LastTouchedDays = " <span class='LastTouched' style='cursor:pointer;' onClick=\"MProjects.ShowPreviewBox(this, 'LastTouched', ".$Project->ID.");\">".$Project->GetLastTouchedDays()."</span>";

		$MilestonePercentage		= 0;
		$Numerator					= 0;
		if(count($Project->Milestones) >= 1) {
			foreach($Project->Milestones as $Milestone) {
				if($Milestone->Status == "Complete") $Numerator++;
			}
			$MilestonePercentage	= $Numerator / count($Project->Milestones);
		}
		$MilestoneBarWidth			= round(136 * $MilestonePercentage) - 2 >= 0 ? round(136 * $MilestonePercentage) - 2 : 0;


		$Return = "
		<div class='ProjectWrapper' id='ProjectOverview".$Project->ID."' style='position:relative;'>
			<div class='ProjectContainer'>
				<table style='width:100%;' cellpadding='0' cellspacing='0'>
					<tr>
						<td style='vertical-align:top; padding-top:22px; padding-left:11px;'><div class='ProductIcon'></div></td>
						<td style='vertical-align:top; padding:22px 11px; width:135px;'>
							<div style='font-weight:bold; font-size:14px;'>".$Project->ProductNumber."</div>
							<div>".$Project->School."</p>
							<div style='font-weight:bold; font-size:14px;'>".$Project->PrimaryCustomer."</div>
							<p>(".substr($Project->CustomerPhone, 0, 3).") ".substr($Project->CustomerPhone, 3, 3)."-".substr($Project->CustomerPhone, 6, 4)."</p>
						</td>
						<td style='vertical-align:top; padding-top:20px;'><div class='Separator'></div></td>
						<td style='vertical-align:top; padding:22px 11px; width:195px;'>
							<div style='color:#d74c4c; font-weight:bold; font-size:14px;'>Dates</div>
							<p><b>Due:</b> ".($Project->DueDate > 0 ? date('n/j/Y', $Project->DueDate) : ($Project->CourseStartDate > 0 ? date('n/j/Y', $Project->CourseStartDate) : "N/A"))."</p>
							<p><b>Last Touched:</b> ".date('n/j/Y', $Project->GetLastModified()).$LastTouchedDays."</p>
							<p><b>LS Submit:</b> ".date('n/j/Y', $Project->Created)."</p>
							<p><b>Class Start:</b> ".($Project->CourseStartDate > 0 ? date('n/j/Y', $Project->CourseStartDate) : "N/A")."</p>
						</td>
						<td style='vertical-align:top; padding-top:20px;'><div class='Separator'></div></td>
						<td style='vertical-align:top; padding:22px 11px; width:145px;'>
							<div style='color:#0685c5; font-weight:bold; font-size:14px;'>Project Details</div>
							<p><b>CA:</b> ".$Project->GetUsers("CreativeAnalysts")."</p>
							<p><b>LSC:</b> ".$Project->GetUsers("LSCs")."</p>
							<p><b>Sales Rep:</b> ".$Project->GetUsers("SalesReps")."</p>
							<!--<p>".$Project->StatSponsorCode."</p>-->
							<p><b>Product Type(s):</b> ".$Project->GetProductTypesList()."</p>
							<p style='color:#0685c5; text-decoration:underline; cursor:pointer;' onClick=\"MProjects.ShowPreviewBox(this, 'LeadNotes', ".$Project->ID.");\">Lead Notes</p>
						</td>
						<td style='vertical-align:top; padding-top:20px;'><div class='Separator'></div></td>
						<td style='vertical-align:top; padding:22px 11px; width:150px;'>
							<div style='color:#4f911e; font-weight:bold; font-size:14px;'>Project Value</div>
							<p style='font-size:18px; font-weight:bold;'>$".number_format($Project->ProjectValue, 2)."</p>
							<div style='color:#4f911e; font-weight:bold; font-size:14px; margin-top:12px; margin-bottom:6px;'>Milestone Completion</div>
							<div class='CompletionWrapper'>
								<div class='CompletionBar' style='width:".$MilestoneBarWidth."px;'></div>
								<div class='CompletionPercentage'>".number_format($MilestonePercentage * 100)."%</div>
							</div>
						</td>
						<td style='padding-right:13px;'>
							<input type='hidden' id='Project".$Project->ID."Header' value=\"<strong>".$Project->ProductNumber." // ".$Project->School."</strong><br><span style='font-size:11px; color:#0685c5; font-style:italic;'>".$Project->Title."</span>\">
							<div onClick=\"
								MProjects.ViewDetails('".$Project->ID."', function() {
									MProjects.MoveToDetails(".$Project->ID.");
								});
								MProjects.ViewMessages('".$Project->ID."');
								MProjects.ViewResources('".$Project->ID."');
								MProjects.ViewNotifications('".$Project->ID."');
							\" class='ProjectDetailsLink'></div>
						</td>
					</tr>
				</table>
			</div>
		</div>
		";
		
		return $Return;
	}
	
	function OnHide($Value) {
		return "<div style='display:none;'>$Value</div>";
	}

	$Search = new CSearch("Projects");
	
	/* 0 */ $Search->AddColumn("ID", "ID", "0px;display:none", CSEARCHCOLUMN_SEARCHTYPE_NOSEARCH, "", "", "", "OnRow");
	/* 1 */ $Search->AddColumn("Product #", "ProductNumber", "0px;display:none", CSEARCHCOLUMN_SEARCHTYPE_LOOSE, "", "", "", "OnHide");
	/* 2 */ $Search->AddColumn("School", "School", "0px;display:none", CSEARCHCOLUMN_SEARCHTYPE_LOOSE, "", "", "", "OnHide");
	/* 3 */ $Search->AddColumn("Primary Customer", "PrimaryCustomer", "0px;display:none", CSEARCHCOLUMN_SEARCHTYPE_LOOSE, "", "", "", "OnHide");
	/* 4 */ $Search->AddColumn("Customer Phone", "CustomerPhone", "0px;display:none", CSEARCHCOLUMN_SEARCHTYPE_LOOSE, "", "", "", "OnHide");
	/* 5 */ $Search->AddColumn("Stat Sponsor Code", "StatSponsorCode", "0px;display:none", CSEARCHCOLUMN_SEARCHTYPE_LOOSE, "", "", "", "OnHide");
	/* 6 */ $Search->AddColumn("Created", "Created", "0px;display:none", CSEARCHCOLUMN_SEARCHTYPE_LOOSE, "", "", "", "OnHide");
	/* 7 */ //$Search->AddColumn("Product Type", "ProductType", "0px;display:none", CSEARCHCOLUMN_SEARCHTYPE_LOOSE, "", "", "", "OnHide");
	
	// Non-deleted Projects
	$Search->AddRestriction("Deleted", "0");
	
	// Where Operator
	$Search->SetWhereOperator = $_GET["FilterOperator"];
	
	//$Search->SetDefaultColumn(0, 1);
	
	$FilterOptions = Array(
		//"ProjectNumber"			=> "Project Number",
		//"ProjectName"				=> "Project Name",
		//"Institution"				=> "Institution",
		//"DueDate"					=> "Due Date",
		//"LastTouched"				=> "Last Touched",
		//"TouchCount"				=> "Touch Count",
		//"ProjectValue"			=> "Project Value",
		"ProjectType"				=> "Project Type",
		"DistrictManager"			=> "District Manager",
		"SalesRep"					=> "Sales Rep",
		"LSC"						=> "LSC",
		"LSS"						=> "LSS",
		"LSR"						=> "LSR",
		"CreativeAnalyst"			=> "Creative Analyst",
		"InstitutionalSalesRep"		=> "Institutional Sales Rep",
		"ProductManager"			=> "Product Manager",
		"CourseStartDate"			=> "Course Start Date",
		//"RequestType"				=> "Request Type",
		//"InstitutionContact"		=> "Institution Contact",
	);
	
		// Project Types
		$SubOptions_ProjectType = Array();
		$ProductTypes = new CProductTypes();
		if($ProductTypes->OnLoadAll("WHERE `Active` = 1 ORDER BY `Name`") !== false) {
			foreach($ProductTypes->Rows as $Row) {
				$SubOptions_ProjectType[$Row->ID] = $Row->Name;
			}
			if($_GET["Filters"]) {
				$Filters = json_decode(urldecode($_GET["Filters"]));
				if($Filters->ProjectType === true && $Filters->ProjectTypeValue) {
					$ProjectTypeValues = Array();
					foreach($Filters->ProjectTypeValue as $ID => $Boolean) {
						if($Boolean) {
							$ProjectTypeValues[] = $ID;
						}
					}
					$SubQuery = "(SELECT `ProductTypesID` FROM `ProjectsProductTypes` WHERE `ProjectsID` = `Projects`.`ID`) IN (".implode(",", $ProjectTypeValues).")";
					$Search->AddRestriction($SubQuery, "", "", "Custom");
				}
			}
		}
		
		// District Managers
		$SubOptions_DistrictManager = Array();
		$DMGroup = new CUsersGroups();
		$DMGroup->OnLoadAll("WHERE `Name` = 'District Manager'");
		$DMs = new CUsers();
		if($DMs->OnLoadAll("WHERE `UsersGroupsID` = ".$DMGroup->ID." && `Active` = 1 ORDER BY `LastName`") !== false) {
			foreach($DMs->Rows as $Row) {
				$SubOptions_DistrictManager[$Row->ID] = $Row->LastName . ", " . $Row->FirstName;
			}
			if($_GET["Filters"]) {
				$Filters = json_decode(urldecode($_GET["Filters"]));
				if($Filters->DistrictManager === true && $Filters->DistrictManagerValue) {
					$DistrictManagerValues = Array();
					foreach($Filters->DistrictManagerValue as $ID => $Boolean) {
						if($Boolean) {
							$DistrictManagerValues[] = $ID;
						}
					}
					$SubQuery = "`ID` IN (SELECT `ProjectsID` FROM `ProjectsDistrictManagers` WHERE `UsersID` IN (".implode(",", $DistrictManagerValues)."))";
					$Search->AddRestriction($SubQuery, "", "", "Custom");
				}
			}
		}
		
		// Sales Reps
		$SubOptions_SalesRep = Array();
		$RepGroup = new CUsersGroups();
		$RepGroup->OnLoadAll("WHERE `Name` = 'Sales Rep'");
		$Reps = new CUsers();
		if($Reps->OnLoadAll("WHERE `UsersGroupsID` = ".$RepGroup->ID." && `Active` = 1 ORDER BY `LastName`") !== false) {
			foreach($Reps->Rows as $Row) {
				$SubOptions_SalesRep[$Row->ID] = $Row->LastName . ", " . $Row->FirstName;
			}
			if($_GET["Filters"]) {
				$Filters = json_decode(urldecode($_GET["Filters"]));
				if($Filters->SalesRep === true && $Filters->SalesRepValue) {
					$RepValues = Array();
					foreach($Filters->SalesRepValue as $ID => $Boolean) {
						if($Boolean) {
							$RepValues[] = $ID;
						}
					}
					$SubQuery = "`ID` IN (SELECT `ProjectsID` FROM `ProjectsSalesReps` WHERE `UsersID` IN (".implode(",", $RepValues)."))";
					$Search->AddRestriction($SubQuery, "", "", "Custom");
				}
			}
		}
		
		// LSCs
		$SubOptions_LSC = Array();
		$LSCGroup = new CUsersGroups();
		$LSCGroup->OnLoadAll("WHERE `Name` = 'Learning Solutions Consultant'");
		$LSCs = new CUsers();
		if($LSCs->OnLoadAll("WHERE `UsersGroupsID` = ".$LSCGroup->ID." && `Active` = 1 ORDER BY `LastName`") !== false) {
			foreach($LSCs->Rows as $Row) {
				$SubOptions_LSC[$Row->ID] = $Row->LastName . ", " . $Row->FirstName;
			}
			if($_GET["Filters"]) {
				$Filters = json_decode(urldecode($_GET["Filters"]));
				if($Filters->LSC === true && $Filters->LSCValue) {
					$LSCValues = Array();
					foreach($Filters->LSCValue as $ID => $Boolean) {
						if($Boolean) {
							$LSCValues[] = $ID;
						}
					}
					$SubQuery = "`Projects`.`ID` IN (SELECT `ProjectsID` FROM `ProjectsLSCs` WHERE `UsersID` IN (".implode(",", $LSCValues)."))";
					$Search->AddRestriction($SubQuery, "", "", "Custom");
				}
			}
		}
		
		// LSSs
		$SubOptions_LSS = Array();
		$LSSGroup = new CUsersGroups();
		$LSSGroup->OnLoadAll("WHERE `Name` = 'Learning Solutions Specialist'");
		$LSSs = new CUsers();
		if($LSSs->OnLoadAll("WHERE `UsersGroupsID` = ".$LSSGroup->ID." && `Active` = 1 ORDER BY `LastName`") !== false) {
			foreach($LSSs->Rows as $Row) {
				$SubOptions_LSS[$Row->ID] = $Row->LastName . ", " . $Row->FirstName;
			}
			if($_GET["Filters"]) {
				$Filters = json_decode(urldecode($_GET["Filters"]));
				if($Filters->LSS === true && $Filters->LSSValue) {
					$LSSValues = Array();
					foreach($Filters->LSSValue as $ID => $Boolean) {
						if($Boolean) {
							$LSSValues[] = $ID;
						}
					}
					$SubQuery = "`ID` IN (SELECT `ProjectsID` FROM `ProjectsLSSs` WHERE `UsersID` IN (".implode(",", $LSSValues)."))";
					$Search->AddRestriction($SubQuery, "", "", "Custom");
				}
			}
		}
		
		// LSRs
		$SubOptions_LSR = Array();
		$LSRGroup = new CUsersGroups();
		$LSRGroup->OnLoadAll("WHERE `Name` = 'Learning Solutions Representative'");
		$LSRs = new CUsers();
		if($LSRs->OnLoadAll("WHERE `UsersGroupsID` = ".$LSRGroup->ID." && `Active` = 1 ORDER BY `LastName`") !== false) {
			foreach($LSRs->Rows as $Row) {
				$SubOptions_LSR[$Row->ID] = $Row->LastName . ", " . $Row->FirstName;
			}
			if($_GET["Filters"]) {
				$Filters = json_decode(urldecode($_GET["Filters"]));
				if($Filters->LSR === true && $Filters->LSRValue) {
					$LSRValues = Array();
					foreach($Filters->LSRValue as $ID => $Boolean) {
						if($Boolean) {
							$LSRValues[] = $ID;
						}
					}
					$SubQuery = "`ID` IN (SELECT `ProjectsID` FROM `ProjectsLSRs` WHERE `UsersID` IN (".implode(",", $LSRValues)."))";
					$Search->AddRestriction($SubQuery, "", "", "Custom");
				}
			}
		}
		
		// Creative Analysts
		$SubOptions_CreativeAnalyst = Array();
		$CreativeAnalystGroup = new CUsersGroups();
		$CreativeAnalystGroup->OnLoadAll("WHERE `Name` = 'Creative Analyst'");
		$CreativeAnalysts = new CUsers();
		if($CreativeAnalysts->OnLoadAll("WHERE `UsersGroupsID` = ".$CreativeAnalystGroup->ID." && `Active` = 1 ORDER BY `LastName`") !== false) {
			foreach($CreativeAnalysts->Rows as $Row) {
				$SubOptions_CreativeAnalyst[$Row->ID] = $Row->LastName . ", " . $Row->FirstName;
			}
			if($_GET["Filters"]) {
				$Filters = json_decode(urldecode($_GET["Filters"]));
				if($Filters->CreativeAnalyst === true && $Filters->CreativeAnalystValue) {
					$CreativeAnalystValues = Array();
					foreach($Filters->CreativeAnalystValue as $ID => $Boolean) {
						if($Boolean) {
							$CreativeAnalystValues[] = $ID;
						}
					}
					$SubQuery = "`ID` IN (SELECT `ProjectsID` FROM `ProjectsCreativeAnalysts` WHERE `UsersID` IN (".implode(",", $CreativeAnalystValues)."))";
					$Search->AddRestriction($SubQuery, "", "", "Custom");
				}
			}else
			if(CSecurity::GetUsersGroupsID() == 7)
			{
				$Filters							= Array();
				$Filters["CreativeAnalyst"]			= true;
				$Filters["CreativeAnalystValue"]	= Array(
					CSecurity::GetUsersID()		=> true
				);
				$_GET["Filters"] = urlencode(json_encode($Filters));
				$CreativeAnalystValues = Array(CSecurity::GetUsersID());
				$SubQuery = "`ID` IN (SELECT `ProjectsID` FROM `ProjectsCreativeAnalysts` WHERE `UsersID` = ".CSecurity::GetUsersID().")";
				$Search->AddRestriction($SubQuery, "", "", "Custom");
			}
		}
		
		// Institutional Sales Reps
		$SubOptions_InstitutionalSalesRep = Array();
		$SalesRepGroup = new CUsersGroups();
		$SalesRepGroup->OnLoadAll("WHERE `Name` = 'Institutional Sales Rep'");
		$SalesReps = new CUsers();
		if($SalesReps->OnLoadAll("WHERE `UsersGroupsID` = ".$SalesRepGroup->ID." && `Active` = 1 ORDER BY `LastName`") !== false) {
			foreach($SalesReps->Rows as $Row) {
				$SubOptions_InstitutionalSalesRep[$Row->ID] = $Row->LastName . ", " . $Row->FirstName;
			}
			if($_GET["Filters"]) {
				$Filters = json_decode(urldecode($_GET["Filters"]));
				if($Filters->SalesRep === true && $Filters->SalesRepValue) {
					$SalesRepValues = Array();
					foreach($Filters->SalesRepValue as $ID => $Boolean) {
						if($Boolean) {
							$SalesRepValues[] = $ID;
						}
					}
					$SubQuery = "`ID` IN (SELECT `ProjectsID` FROM `ProjectsInstitutionalSalesReps` WHERE `UsersID` IN (".implode(",", $SalesRepValues)."))";
					$Search->AddRestriction($SubQuery, "", "", "Custom");
				}
			}
		}
		
		// Product Managers
		$SubOptions_ProductManager = Array();
		$ProductManagerGroup = new CUsersGroups();
		$ProductManagerGroup->OnLoadAll("WHERE `Name` = 'Product Manager'");
		$ProductManagers = new CUsers();
		if($ProductManagers->OnLoadAll("WHERE `UsersGroupsID` = ".$ProductManagerGroup->ID." && `Active` = 1 ORDER BY `LastName`") !== false) {
			foreach($ProductManagers->Rows as $Row) {
				$SubOptions_ProductManager[$Row->ID] = $Row->LastName . ", " . $Row->FirstName;
			}
			if($_GET["Filters"]) {
				$Filters = json_decode(urldecode($_GET["Filters"]));
				if($Filters->ProductManager === true && $Filters->ProductManagerValue) {
					$ProductManagerValues = Array();
					foreach($Filters->ProductManagerValue as $ID => $Boolean) {
						if($Boolean) {
							$ProductManagerValues[] = $ID;
						}
					}
					$SubQuery = "`ID` IN (SELECT `ProjectsID` FROM `ProjectsProductManagers` WHERE `UsersID` IN (".implode(",", $ProductManagerValues)."))";
					$Search->AddRestriction($SubQuery, "", "", "Custom");
				}
			}
		}
		
		// Course Start Date
		$SubOptions_CourseStartDate = "Dates";
		if($_GET["Filters"]) {
			$Filters = json_decode(urldecode($_GET["Filters"]));
			if($Filters->CourseStartDate === true) {
				$SubQuery = "";
				if(($TimestampStart = strtotime($Filters->CourseStartDateValue->Start)) > 0) {
					$SubQuery .= " `CourseStartDate` >= ".$TimestampStart;
				}
				if(($TimestampEnd = strtotime($Filters->CourseStartDateValue->End)) > 0) {
					if($SubQuery) $SubQuery .= " && ";
					$SubQuery .= " `CourseStartDate` <= ".$TimestampEnd;
				}
				$Search->AddRestriction($SubQuery, "", "", "Custom");
			}
		}
		
		// Sort and SortDir
		if($_GET["Sort"]) {
			$OrderColumn = 0;
			if($_GET["Sort"] == "Name") {
				$OrderColumn = 1;
			} else
			if($_GET["Sort"] == "Date") {
				$OrderColumn = 6;
			}
			
			if($_GET["SortDir"] == "DESC") {
				//$OrderQuery .= " DESC";
				$Search->SetDefaultColumn($OrderColumn, 1);
			} else {
				//$OrderQuery .= " ASC";
				$Search->SetDefaultColumn($OrderColumn, 0);
			}
		}
	
	echo "
	<div id='SearchResultsContainer' style='position:absolute; top:25px; left:25px; width:4050px;'>";
		
		// Sort By Name
		$Temp = $_GET;
		if($Temp["Sort"] == "Name") {
			if($Temp["SortDir"] == "ASC") {
				$Temp["SortDir"]	= "DESC";
			} else {
				$Temp["SortDir"]	= "ASC";
			}
		} else {
			$Temp["Sort"]		= "Name";
			$Temp["SortDir"]	= "ASC";
		}
		echo "<div class='Button' value='SortName' onClick='CModule.Load(\"Projects\", ".json_encode($Temp).")' style='height:28px; line-height:28px; float:left; margin-top:62px; margin-left:0px;'>Name ".($Temp["SortDir"] == "ASC" ? "A-Z" : "Z-A")."</div>";
		
		// Sort by Date Created
		$Temp = $_GET;
		if($Temp["Sort"] == "Date") {
			if($Temp["SortDir"] == "DESC") {
				$Temp["SortDir"]	= "ASC";
			} else {
				$Temp["SortDir"]	= "DESC";
			}
		} else {
			$Temp["Sort"]		= "Date";
			$Temp["SortDir"]	= "ASC";
		}
		echo "<div class='Button' value='SortDate' onClick='CModule.Load(\"Projects\", ".json_encode($Temp).")' style='height:28px; line-height:28px; float:left; margin-top:62px; margin-left:-1px;'>".($Temp["SortDir"] == "ASC" ? "Newest" : "Oldest")." on Top</div>";
		
		echo "
		<form id='SearchForm' method='get' style='top:0px;'>
			<input type='hidden' name='Filters' id='Filters' value='".($_GET["Filters"] ? urldecode($_GET["Filters"]) : "{}")."'>
			<input id='SearchQuery' name='CSearch_Keywords' type='text' name='Query' value='".htmlspecialchars($_GET["CSearch_Keywords"])."' placeholder='search'>
			<input id='SearchSubmit' type='submit' value=''>
		</form>

		<div id='Filter'>
			<div id='FilterOptions'>
				<select id='FilterProfiles' onChange=\"if($(this).val() != 0) { $('#Filters').val($(this).val()); $('#SearchForm').submit(); }\" style='width:90%;'>
					<option value='0'>-- Saved Filters --</option>
					<option value='{}'>Clear All</option>";
					$FilterProfiles = new CFilterProfiles();
					if($FilterProfiles->OnLoadAll("WHERE `UsersID` = ".CSecurity::GetUsersID()." ORDER BY `Name`") !== false) {
						foreach($FilterProfiles->Rows as $Row) {
							if($Row->Name != 'Temporary')
							{
								$Selected = urldecode($_GET["Filters"]) == $Row->Options ? "selected" : "";
								echo "<option value='{$Row->Options}' {$Selected}>{$Row->Name}</option>";
							}
						}
					}
				echo "
				</select>
				<div style='float: right;margin-top: 5px;' class='Icon_Delete' title='Delete Filter' onclick='MProjects.DeleteFilter();'></div>
				<script type='text/javascript'>$('#FilterProfiles').select2();</script>
				<p>Choose your filtering options&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<select id='FilterOperator' class='' style='width:55px;'>
					<option value='AND'>AND</option>
					<option value='OR'>OR</option>
				</select>
				</p>";
				foreach($FilterOptions as $Key => $Value) {
					$FilterOptionContainerActive	= "";
					$FilterOptionActive				= "";
					if($_GET["Filters"]) {
						$Filters = json_decode(urldecode($_GET["Filters"]));
						if($Filters->{$Key}) {
							$FilterOptionContainerActive	= "FilterOptionContainerActive";
							$FilterOptionActive				= "FilterOptionActive";
						}
					}
					echo "
					<div class='FilterOptionContainer $FilterOptionContainerActive' id='".$Key."' value='".$Value."'>
						<div class='FilterOption $FilterOptionActive'></div>
						<div style='float:left; line-height:10px; padding-left:8px;'>".$Value."</div>
						<br style='clear:both;'>";
						if(!empty(${"SubOptions_".$Key})) {
							echo "<div class='SubOptions' ".($FilterOptionActive ? "style='display:block'" : "").">";
							if(is_array(${"SubOptions_".$Key})) {
								foreach(${"SubOptions_".$Key} as $ID => $Name) {
									$FilterSubOptionContainerActive	= "";
									$FilterSubOptionActive				= "";
									if($_GET["Filters"]) {
										$Filters = json_decode(urldecode($_GET["Filters"]));
										if($Filters->{$Key."Value"}->$ID) {
											$FilterSubOptionContainerActive		= "FilterSubOptionContainerActive";
											$FilterSubOptionActive				= "FilterSubOptionActive";
										}
									}
									echo "
									<div class='FilterSubOptionContainer $FilterSubOptionContainerActive' id='".$Key."Value' value='".$ID."'>
										<div class='FilterSubOption $FilterSubOptionActive'></div>
										<div style='float:left; line-height:10px; padding-left:8px;'>".$Name."</div>
										<br style='clear:both;'>
									</div>";
								}
							} else
							if(${"SubOptions_".$Key} == "Dates") {
								if($_GET["Filters"]) {
									$Filters		= json_decode(urldecode($_GET["Filters"]));
									$StartValue		= strtotime($Filters->{$Key."Value"}->Start) > 0 ? date('F j, Y', strtotime($Filters->{$Key."Value"}->Start)) : "";
									$EndValue		= strtotime($Filters->{$Key."Value"}->End) > 0 ? date('F j, Y', strtotime($Filters->{$Key."Value"}->End)) : "";
								}
								echo "<input class='FilterSubDate' type='text' name='".$Key."Value' id='".$Key."ValueStart' value='".$StartValue."'> to <input class='FilterSubDate' type='text' name='".$Key."Value' id='".$Key."ValueEnd' value='".$EndValue."'>";
								echo "<script type='text/javascript'>
									$(document).ready(function(){
										$(\"#".$Key."ValueStart\").datepicker({
											showOn:				'both' ,
											buttonImageOnly:	true ,
											buttonText:			'' ,
											dateFormat:			'MM d, yy' ,
										});
										$(\"#".$Key."ValueEnd\").datepicker({
											showOn:				'both' ,
											buttonImageOnly:	true ,
											buttonText:			'' ,
											dateFormat:			'MM d, yy' ,
										});
									});
								</script>
								";
							}
							echo "</div>";
						}
						echo "
					</div>";
				}
				//<input type='checkbox' name='".$Key."' id='".$Key."' style='display:none;'>
				echo "				
				<div class='Button' value='Apply' onClick=\"MProjects.ApplyFilter();\" style='box-shadow:none; margin-top:0px; margin-left:4px;'>apply</div>
				<br style='clear:both;'>
				<input type='text' class='CForm_Textbox' id='SaveFilterName' placeholder='Filter Name' style='margin-top:5px; float:left; width:160px; height:22px;'>
				<div class='Button' value='Save' onClick=\"MProjects.SaveFilter();\" style='box-shadow:none; margin-top:5px; margin-left:4px;'>save</div>
			</div>
		</div>";
		
		echo "<div id='ActiveFilterList'>";
		if($_GET["Filters"]) {
			$Filters = json_decode(urldecode($_GET["Filters"]));
			$Separator = "";
			$ActiveFilterList = "";
			foreach($Filters as $Key => $Value) {
				if($Filters->{$Key} === true) {
					$ActiveFilterList .= $Separator . $FilterOptions[$Key];
					$Separator = "&nbsp;|&nbsp;";
				}
			}
			echo $ActiveFilterList;
		}
		echo "</div>";

		$Search->OnInit();
		echo "<h1 style='position:absolute; top:12px;'>".$Search->NumRows." Project".($Search->NumRows == 1 ? "" : "s")."</h1>";
		
		echo "
		<div class='FirstPaginator ProjectWrapper' id='ProjectList'>
			<div class='ProjectContainer'>";
				$Search->OnRenderPages();
			echo "
			</div>
		</div>";
		
		$Search->OnRender("border:none !important; margin-top:150px;", "padding:0px;border:none;background:none;");
		
		echo "
		<div class='ProjectWrapper' id='ProjectList'>
			<div class='ProjectContainer'>";
				$Search->OnRenderPages();
			echo "
			</div>
		</div>

		<h1 id='ProjectDetailsHeader' style='position:absolute; text-transform:none; top:0px; left:835px; line-height:18px;'>Project Details</h1>
		<div onClick=\"MProjects.MoveToList();\" class='Back' style='top:0px; left:1534px;'></div>
		<div class='ProjectWrapper' id='ProjectDetailsContainer' style='position:absolute; top:50px; left:835px;'></div>

		<h1 id='MessageBoardHeader' style='position:absolute; text-transform:none; top:0px; left:1645px; line-height:18px;'>Message Board</h1>
		<div onClick=\"MProjects.MoveToDetails();\" class='Back' style='top:0px; left:2344px;'></div>
		<div class='ProjectMessages' id='ProjectMessagesContainer' style='position:absolute; top:50px; left:1645px;'></div>

		<h1 id='ResourceCenterHeader' style='position:absolute; text-transform:none; top:0px; left:2455px; line-height:18px;'>Resource Center</h1>
		<div onClick=\"MProjects.MoveToMessages();\" class='Back' style='top:0px; left:3154px;'></div>
		<div class='ProjectResources' id='ProjectResourcesContainer' style='position:absolute; top:50px; left:2455px;'></div>

		<h1 id='NotificationsHeader' style='position:absolute; text-transform:none; top:0px; left:3265px; line-height:18px;'>Notifications</h1>
		<div onClick=\"MProjects.MoveToNotifications();\" class='Back' style='top:0px; left:3964px;'></div>
		<div class='ProjectNotifications' id='ProjectNotificationsContainer' style='position:absolute; top:50px; left:3265px;'></div>
	</div>
	<div id='HeightCalculator' style='position:relative; width:756px; height:400px; z-index:-1;'></div>
	<script type='text/javascript'>
		$(function(){
			$('#HeightCalculator').animate({
				height : $('#SearchResultsContainer').css('height')
			}, 500, 'swing'
			);
		});
		
		";
		if($_GET["ID"] > 0) {
			echo "
			$(function(){
				MProjects.ViewDetails('".intval($_GET["ID"])."', function() {";
					if($_GET["Page"] == "Messages") {
						echo "MProjects.MoveToMessages();";
					} else
					if($_GET["Page"] == "Resources") {
						echo "MProjects.MoveToResources();";
					} else {
						echo "MProjects.MoveToDetails(".intval($_GET["ID"]).");";
					}
				echo "});
				MProjects.ViewMessages('".intval($_GET["ID"])."');
				MProjects.ViewResources('".intval($_GET["ID"])."');
				MProjects.ViewNotifications('".intval($_GET["ID"])."');
			});
			";
		}
		echo "
	</script>
	<input type='hidden' id='ProjectPosition'>
	
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	";

	
?>
