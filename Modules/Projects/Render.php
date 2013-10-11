<?
	$Settings = $this->Parent->Settings;

	$OneDay		= 60 * 60 * 24;

	$DayOfWeek	= date("w", $Date);
	$Date		= mktime(2, 0, 0, date("m", $Date), date("d", $Date) - $DayOfWeek, date("Y", $Date));
    $SearchTable = "ProjectsView";
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
		
		$LastTouchedDays = " <span class='LastTouched' style='cursor:pointer;' onClick=\"MProjects.ShowPreviewBox(this, 'LastTouched', ".$Project->ID.");\">".$Project->GetLastTouchedDays()."</span>";

		$MilestonePercentage		= $Project->GetMilestoneCompletionPercentage();
		$MilestoneBarWidth			= round(136 * $MilestonePercentage) - 2 >= 0 ? round(136 * $MilestonePercentage) - 2 : 0;

		$Return = "
		<div class='ProjectWrapper' id='ProjectOverview".$Project->ID."' style='position:relative;'>
			<div class='ProjectContainer'>
				<table style='width:100%;' cellpadding='0' cellspacing='0'>
					<tr>
						<td style='vertical-align:top; padding:7px 11px; width:135px;'>
							<a style='text-decoration:none;color:#4e5260;' target='_blank' href='".$Project->GetExternalProjectLinkURL()."'><div style='font-weight:bold; font-size:14px;'>".$Project->ProductNumber."</div></a>
							<div>".$Project->School."</p>
							<div style='font-weight:bold; font-size:14px;'>".$Project->PrimaryCustomer."</div>							
						</td>
						<td style='vertical-align:top; padding-top:7px; padding-bottom:7px;'><div class='Separator'></div></td>
						<td style='vertical-align:top; padding:7px 11px; width:195px;'>
							<div style='color:#d74c4c; font-weight:bold; font-size:14px;'>Dates</div>
							<p><b>Due:</b> ".($Project->DueDate > 0 ? date('n/j/Y', $Project->DueDate) : ($Project->CourseStartDate > 0 ? date('n/j/Y', $Project->CourseStartDate) : "N/A"))."</p>
							<p><b>Last Touched:</b> ".date('n/j/Y', $Project->GetLastModified()).$LastTouchedDays."</p>							
						</td>
						<td style='vertical-align:top; padding-top:7px; padding-bottom:7px;'><div class='Separator'></div></td>
						<td style='vertical-align:top; padding:7px 11px; width:145px;'>
							<div style='color:#0685c5; font-weight:bold; font-size:14px;'>Project Details</div>
							<p><b>LSC:</b> ".$Project->GetUsers("LSCs")."</p>
                            <p><b>Product Solutions:</b> ".$Project->GetProductSolutions()."</p>
						</td>
						<td style='vertical-align:top; padding-top:7px; padding-bottom:7px;'><div class='Separator'></div></td>
						<td style='vertical-align:top; padding:7px 11px; width:160px;'>
							<div style='color:#4f911e; font-weight:bold; font-size:14px; margin-bottom:10px;'>Milestone Completion</div>
							<div class='CompletionWrapper'>
								<div class='CompletionBar' style='width:".$MilestoneBarWidth."px;'></div>
								<div class='CompletionPercentage'>".number_format($MilestonePercentage * 100)."%</div>
							</div>
							<p style='padding-top:10px;'><b>Status:</b> ".$Project->GetFriendlyStatus()."</p>
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
	
	function BuildSortParameters($OrderField, $OrderDirDefault = "0") {
		$Temp = $_GET;
		if($Temp["CSearch_OrderBy"] == $OrderField) {
			$Temp["CSearch_OrderByDir"]	= $Temp["CSearch_OrderByDir"] == "0" ? "1" : "0";
		} else {
			$Temp["CSearch_OrderBy"]	= $OrderField;
			$Temp["CSearch_OrderByDir"]	= $OrderDirDefault;
		}
		
		return $Temp;
	}
    
    function BuildIDList($Field) {
        $Value = $Field."Value";
        if($_GET["Filters"]) {
            $Filters = json_decode(urldecode($_GET["Filters"]));
            if($Filters->{$Field} === true && $Filters->{$Value}) {
                $ArrayValues = Array();
                foreach($Filters->{$Value} as $ID => $Boolean) {
                    if($Boolean) {
                        $ArrayValues[] = $ID;
                    }
                }
                return implode(",", $ArrayValues);
            }
        } 
    }   

	$Search = new CSearch($SearchTable);
	
	$Search->SetItemsPerPage(50);
	
	/* 0 */ $Search->AddColumn("ID", "ID", "0px;display:none", CSEARCHCOLUMN_SEARCHTYPE_NOSEARCH, "", "", "", "OnRow");
	/* 1 */ $Search->AddColumn("Product #", "ProductNumber", "0px;display:none", CSEARCHCOLUMN_SEARCHTYPE_LOOSE, "", "", "", "OnHide");
	/* 2 */ $Search->AddColumn("School", "School", "0px;display:none", CSEARCHCOLUMN_SEARCHTYPE_LOOSE, "", "", "", "OnHide");
	/* 3 */ $Search->AddColumn("Primary Customer", "PrimaryCustomer", "0px;display:none", CSEARCHCOLUMN_SEARCHTYPE_LOOSE, "", "", "", "OnHide");
	/* 4 */ $Search->AddColumn("Customer Phone", "CustomerPhone", "0px;display:none", CSEARCHCOLUMN_SEARCHTYPE_LOOSE, "", "", "", "OnHide");
	/* 5 */ $Search->AddColumn("Stat Sponsor Code", "StatSponsorCode", "0px;display:none", CSEARCHCOLUMN_SEARCHTYPE_LOOSE, "", "", "", "OnHide");
	/* 6 */ $Search->AddColumn("Created", "Created", "0px;display:none", CSEARCHCOLUMN_SEARCHTYPE_LOOSE, "", "", "", "OnHide");
	/* 7 */ $Search->AddColumn("DueDate", "DueDate", "0px;display:none", CSEARCHCOLUMN_SEARCHTYPE_LOOSE, "", "", "", "OnHide");	
	/* 8 */ $Search->AddColumn("LSCs", "LSCs", "0px;display:none", CSEARCHCOLUMN_SEARCHTYPE_LOOSE, "", "", "", "OnHide",0,"IF(ISNULL(LSCs),1,0),LSCs");
	/* 9 */ //$Search->AddColumn("Product Type", "ProductType", "0px;display:none", CSEARCHCOLUMN_SEARCHTYPE_LOOSE, "", "", "", "OnHide");
    /*10 */ $Search->AddHiddenColumn("Tags", CSEARCHCOLUMN_SEARCHTYPE_LOOSE);
	
	// Non-deleted Projects
	$Search->AddAndRestriction("Deleted", "0");
	
	// Where Operator
	if($_GET["Filters"]) {
		$Filters = json_decode(urldecode($_GET["Filters"]));		
		if($Filters->FilterOperator)		
			$Search->SetWhereOperator($Filters->FilterOperator);			
		else
			$Search->SetWhereOperator("OR");
	}
	else
		$Search->SetWhereOperator("OR");
	
	//$Search->SetDefaultColumn(0, 1);	
	$FilterOptions = Array(
		//"ProjectNumber"			=> "Project Number",
		//"ProjectName"				=> "Project Name",
		//"Institution"				=> "Institution",
		//"DueDate"					=> "Due Date",
		//"LastTouched"				=> "Last Touched",
		//"TouchCount"				=> "Touch Count",
		//"ProjectValue"			=> "Project Value",
		//"ProjectType"				=> "Project Type",
		//"DistrictManager"			=> "District Manager",
		//"SalesRep"				=> "Sales Rep",
		"LSC"						=> "LSC",
		"LSS"						=> "LSS",
		//"LSR"						=> "LSR",
		"CreativeContact"			=> "Creative Contact",
		"InstitutionalSalesRep"		=> "Enterprise Sales Rep",
		"Status"					=> "Status",
		//"ProductManager"			=> "Product Manager",
		//"CourseStartDate"			=> "Course Start Date",
		//"RequestType"				=> "Request Type",
		//"InstitutionContact"		=> "Institution Contact",
        "ProductSolution"           => "Product solution"
	);
	
		
    // LSCs
    $SubOptions_LSC = Array();
    $LSCGroup = new CUsersGroups();
    $LSCGroup->OnLoadAll("WHERE `Name` = 'Learning Solutions Consultant'");
    $LSCs = new CUsers();
    if($LSCs->OnLoadAll("WHERE `UsersGroupsID` = ".$LSCGroup->ID." && `Active` = 1 ORDER BY `LastName`") !== false) {
        $SubOptions_LSC = $LSCs->Rows->RowsToAssociativeArrayWithMultipleColumns("LastName,FirstName");

        $LSCValues = BuildIDList("LSC");
        if (!empty($LSCValues)) {
            $SubQuery = "`ID` IN (SELECT `ProjectsID` FROM `ProjectsLSCs` WHERE `UsersID` IN ($LSCValues))";
            $Search->AddRestriction($SubQuery, "", "", "Custom");
        }
    }

    // LSSs
    $SubOptions_LSS = Array();
    $LSSGroup = new CUsersGroups();
    $LSSGroup->OnLoadAll("WHERE `Name` = 'Learning Solutions Specialist'");
    $LSSs = new CUsers();
    if($LSSs->OnLoadAll("WHERE `UsersGroupsID` = ".$LSSGroup->ID." && `Active` = 1 ORDER BY `LastName`") !== false) {
        $SubOptions_LSS = $LSSs->Rows->RowsToAssociativeArrayWithMultipleColumns("LastName,FirstName");

        $LSSValues = BuildIDList("LSS");
        if(!empty($LSSValues)) {
            $SubQuery = "`ID` IN (SELECT `ProjectsID` FROM `ProjectsLSSs` WHERE `UsersID` IN ($LSSValues))";
            $Search->AddRestriction($SubQuery, "", "", "Custom");
        }
    }
        
		
    // Creative Contact (combination of old Associate Creative Analyst, Junior Creative Analyst, Creative Analyst and Creative Consultant)
    $SubOptions_CreativeContact = Array();
    $CCGroup = new CUsersGroups();
    $CCGroup->OnLoadAll("WHERE `Name` = 'Creative Contact'");
    $CreativeContacts = new CUsers();
    if($CreativeContacts->OnLoadAll("WHERE `UsersGroupsID` = ".$CCGroup->ID." && `Active` = 1 ORDER BY `LastName`")) {
        foreach($CreativeContacts->Rows as $Row) {
            $SubOptions_CreativeContact[$Row->ID] = $Row->LastName . ", " . $Row->FirstName;
        }
        if($_GET["Filters"]) {
            $Filters = json_decode(urldecode($_GET["Filters"]));
            if($Filters->CreativeContact === true && $Filters->CreativeContactValue) {
                $CreativeContactsValues = Array();
                foreach($Filters->CreativeContactValue as $ID => $Boolean) {
                    if($Boolean) {
                        $CreativeContactsValues[] = $ID;
                    }
                }
                if(count($CreativeContactsValues) > 0)
                {
                    $SubQuery = "`ID` IN (SELECT `ProjectsID` FROM `ProjectsCreativeContacts` WHERE `UsersID` IN (".implode(",", $CreativeContactsValues)."))";
                    $Search->AddRestriction($SubQuery, "", "", "Custom");
                }
            }
        } 
        else if(CSecurity::GetUsersGroupsID() == 15)
        {
            //TODO: Fix this!
            $Filters							= Array();
            $Filters["CreativeContact"]			= true;
            $Filters["CreativeContactValue"]	= Array(
                CSecurity::GetUsersID()		=> true
            );
            $_GET["Filters"] = urlencode(json_encode($Filters));
            $CreativeContactsValues = Array(CSecurity::GetUsersID());
            $SubQuery = "`ID` IN (SELECT `ProjectsID` FROM `ProjectsCreativeContacts` WHERE `UsersID` = ".CSecurity::GetUsersID().")";
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
            if($Filters->InstitutionalSalesRep === true && $Filters->InstitutionalSalesRepValue) {
                $InstitutionalSalesRepValues = Array();
                foreach($Filters->InstitutionalSalesRepValue as $ID => $Boolean) {
                    if($Boolean) {
                        $InstitutionalSalesRepValues[] = $ID;
                    }
                }
                if(count($InstitutionalSalesRepValues) > 0)
                {
                    $SubQuery = "`ID` IN (SELECT `ProjectsID` FROM `ProjectsInstitutionalSalesReps` WHERE `UsersID` IN (".implode(",", $InstitutionalSalesRepValues)."))";
                    $Search->AddRestriction($SubQuery, "", "", "Custom");
                }
            }
        }
    }


    // Status		
    $SubOptions_Status = CProjects::GetAllStatus();
    $StatusValues = BuildIDList("Status");
    if (!empty($StatusValues)) {
       $SubQuery = "`Status` IN ($StatusValues)";
       $Search->AddRestriction($SubQuery, "", "", "Custom"); 
    }

    $SubOptions_ProductSolution = Array();
    $CProductSolutions = new CProductSolutions();
    if ($CProductSolutions->OnLoadAll("WHERE `Active` = 1")) {
        $SubOptions_ProductSolution = $CProductSolutions->Rows->RowsToAssociativeArray("Name");

        $ProductSolutionValues = BuildIDList("ProductSolution");
        if (!empty($ProductSolutionValues)) {
            $SubQuery = "`ID` IN (SELECT `ProjectsID` FROM `ProjectsProductSolutions` WHERE `ProductSolutionsID` IN ($ProductSolutionValues))";
            $Search->AddRestriction($SubQuery, "", "", "Custom");
        }
    }
		
		echo "<div id='SearchResultsContainer' style='position:absolute; top:10px; left:25px; width:4050px;'>";
		
		// Sort By Name (column 1)
		$OrderByParams = BuildSortParameters("1");
		$OrderByStyle = "'height:28px; line-height:28px; float:left; margin-top:45px; margin-left:0px;width:90px;'";
		
		echo "<div class='Button' value='SortName' onClick='CModule.Load(\"Projects\", ".json_encode($OrderByParams).")' 
				style=".$OrderByStyle.">By Name - ".($OrderByParams["CSearch_OrderByDir"] == "0" ? "A-Z" : "Z-A")."</div>";

		// Sort by Date Created (column 6)
		$OrderByParams = BuildSortParameters("6", "1");
		$OrderByStyle = "'height:28px; line-height:28px; float:left; margin-top:45px; margin-left:-1px;width:180px;'";
		
		echo "<div class='Button' value='SortDate' onClick='CModule.Load(\"Projects\", ".json_encode($OrderByParams).")' 
				style=".$OrderByStyle.">By creation date - ".($OrderByParams["CSearch_OrderByDir"] == "1" ? "newest" : "oldest")." on top</div>";
		
		// Sort by Due Date (column 7)
		$OrderByParams = BuildSortParameters("7", "1");
		$OrderByStyle = "'height:28px; line-height:28px; float:left; margin-top:45px; margin-left:-1px;width:180px;'";
		
		echo "<div class='Button' value='SortDueDate' onClick='CModule.Load(\"Projects\", ".json_encode($OrderByParams).")' 
				style=".$OrderByStyle.">By due date - ".($OrderByParams["CSearch_OrderByDir"] == "1" ? "newest" : "oldest")." on top</div>";
				
		// Sort by LSCs last name (column 8)
		$OrderByParams = BuildSortParameters("8");
		$OrderByStyle = "'height:28px; line-height:28px; float:left; margin-top:45px; margin-left:-1px;width:180px;'";
		
		echo "<div class='Button' value='SortLSCs' onClick='CModule.Load(\"Projects\", ".json_encode($OrderByParams).")' 
				style=".$OrderByStyle.">By LSCs Last Name - ".($OrderByParams["CSearch_OrderByDir"] == "0" ? "A-Z" : "Z-A")."</div>";
				
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
				<div style='float:right;margin-top: 5px;' class='Icon_Delete' title='Delete Filter' onclick='MProjects.DeleteFilter();'></div>
				<script type='text/javascript'>$('#FilterProfiles').select2();</script>
				<div style='width:70%;margin-right:55px;padding-top:5px;padding-bottom:7px;'>Choose your filtering options&nbsp;</div>
				<style>
				  #feedback { font-size: 10px; }
				  #selectable .ui-selecting { background: #FECA40; }
				  #selectable .ui-selected { background: rgb(114, 107, 97); color: white; border-color: rgb(114, 107, 97);}
				  #selectable { list-style-type: none; margin: 0; padding: 0; width: 50px;}
				  #selectable li { vertical-align:middle;margin: 0px; padding: 1px; float: left; width: 50px; height: 18px; font-size: 13px; text-align: center; color: rgb(66, 56, 41); border: 1px solid rgb(66, 56, 41);}
				</style>";
				 
				$Filters = json_decode(urldecode($_GET["Filters"]));
				echo "<div style='float: right;margin-right:10px;'><ul id='selectable'>";
					if(isset($Filters->FilterOperator) && $Filters->FilterOperator=='OR')
					{
						echo '<li>AND</li><li class="ui-selected">OR</li>';
					} else {
						echo '<li class="ui-selected">AND</li><li>OR</li>';
					}
				echo "</ul>" .
					'<script>
						$(function() {
							$("#selectable" ).selectable();
							$("#selectable" ).on("selectableselected", function( event, ui ) {
								$("#FilterOperator option").attr("selected",false);
								$("#FilterOperator option[value="+ui.selected.outerText+"]").attr("selected",true);
							} );
						});
					</script>' . "
				<select id='FilterOperator' class='' style='width:55px;display:none;'>";
					if(isset($Filters->FilterOperator) && $Filters->FilterOperator=='OR')					
						echo "<option value='AND'>AND</option><option value='OR' selected>OR</option>";					
					else
						echo "<option value='AND' selected>AND</option><option value='OR'>OR</option>";
					echo 
				"</select></div>";
				
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
				<div class='Button' value='Save' onClick=\"MProjects.SaveFilter();\" style='box-shadow:none; margin-top:5px; margin-left:4px;'>save/edit</div>
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
		echo "<h1 style='position:absolute; top:0px;'>".$Search->NumRows." Project".($Search->NumRows == 1 ? "" : "s")."</h1>";
		
		echo "
		<div class='FirstPaginator ProjectWrapper' id='ProjectList'>
			<div class='ProjectContainer'>";
				$Search->OnRenderPages();
			echo "
			</div>
		</div>";
		
		$Search->OnRender("border:none !important; margin-top:131px;", "padding:0px;border:none;background:none;");
		
		echo "
		<div class='ProjectWrapper' id='ProjectList'>
			<div class='ProjectContainer'>";
				$Search->OnRenderPages();
			echo "
			</div>
		</div>

		<h1 id='ProjectDetailsHeader' style='position:absolute; text-transform:none; top:0px; left:835px; line-height:18px;'>Project Details</h1>
		<div id='MoveBackButton' onClick=\"MProjects.MoveToList();\" class='Back' style='top:0px; left:1534px;'></div>
		<div class='ProjectWrapper' id='ProjectDetailsContainer' style='position:absolute; top:50px; left:835px;'></div>

		<h1 id='MessageBoardHeader' style='position:absolute; text-transform:none; top:0px; left:1645px; line-height:18px;'>Message Board</h1>
		<div onClick=\"MProjects.MoveToDetails();\" class='Back' style='top:0px; left:2344px;'></div>
		<div class='ProjectMessages' id='ProjectMessagesContainer' style='position:absolute; top:50px; left:1645px;'></div>

		<h1 id='ResourceCenterHeader' style='position:absolute; text-transform:none; top:0px; left:2455px; line-height:18px;'>Resource Center</h1>
		<div onClick=\"MProjects.MoveToMessages();\" class='Back' style='top:0px; left:3154px;'></div>
		<div class='ProjectResources' id='ProjectResourcesContainer' style='position:absolute; top:50px; left:2455px;'></div>

		<h1 id='NotificationsHeader' style='position:absolute; text-transform:none; top:0px; left:3265px; line-height:18px;'>Notifications</h1>
		<div onClick=\"MProjects.MoveToDetails();\" class='Back' style='top:0px; left:3964px;'></div>
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
