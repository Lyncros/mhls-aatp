<?
	//$Project = $this->Parent->TableObject;
	
	$ProjectID = intval($_POST["ProjectID"]);
	
	CForm::SetPrefix("");

	$Project = new CProjects();
	$Project->OnLoad($ProjectID);
	
	/*if($Project->GetLastTouchedDays() >= 1)*/ $LastTouchedDays = " <span class='LastTouched' style='cursor:pointer;' onClick=\"MProjects.ShowPreviewBox(this, 'LastTouched', ".$Project->ID.");\">".$Project->GetLastTouchedDays()."</span>";
	
	$MilestonePercentage		= 0;
	$Numerator					= 0;
	if(count($Project->Milestones) >= 1) {
		foreach($Project->Milestones as $Milestone) {
			if($Milestone->Status == "Complete") $Numerator++;
		}
		$MilestonePercentage	= $Numerator / count($Project->Milestones);
	}
	$MilestoneBarWidth			= round(672 * $MilestonePercentage) - 2 >= 0 ? round(672 * $MilestonePercentage) - 2 : 0;

	
	echo "
	<script>
		$(document).ready(function() {
			$('#MoveBackButton').attr('onClick','MProjects.MoveToList($ProjectID);');
			$('#ProjectListSideBarIcon').attr('onClick','MProjects.MoveToList($ProjectID);');			
		});	
	</script>	
	<div class='ProjectContainer' style='padding-bottom:45px;'>
		<table style='width:100%;' cellpadding='0' cellspacing='0'>
			<tr>
				<td style='width:33px; padding-top:17px;'></td>
				<td colspan='7' style='padding-top:20px; font-size:24px;'>
					<div style='float:left;'>Project Details</div>";
					if(CSecurity::$User->CanAccess("ProjectDetails", "Edit")) {
						echo "<div class='Icon_Edit' style='float:left; margin-left:12px;' onClick=\"$('#ProjectDetailsReadOnly').slideToggle(); $('#ProjectDetailsEdit').slideToggle();\"></div>";
					}
					echo "
				</td>
				<td style='width:33px;'></td>
			</tr>
			<tr>
				<td></td>
				<td>
					<div id='ProjectDetailsReadOnly'>
						<table>
							<tr>
								<td style='vertical-align:top; padding:22px 11px; padding-left:0px; width:135px;'>
									<div style='font-weight:bold; font-size:14px;'>".$Project->PrimaryCustomer."</div>
									<p>(".substr($Project->CustomerPhone, 0, 3).") ".substr($Project->CustomerPhone, 3, 3)."-".substr($Project->CustomerPhone, 6, 4)."</p>
									<br>
									<p style='font-size:18px; font-weight:bold;'>$".number_format($Project->ProjectValue, 2)."</p>
								</td>
								<td style='vertical-align:top; padding-top:20px;'><div class='Separator'></div></td>
								<td style='vertical-align:top; padding:22px 11px; width:195px;'>
									<div style='color:#d74c4c; font-weight:bold; font-size:14px;'>Dates</div>
									<p><b>Due:</b> ".($Project->DueDate > 0 ? date('n/j/Y', $Project->DueDate) : ($Project->CourseStartDate > 0 ? date('n/j/Y', $Project->CourseStartDate) : ""))."</p>
									<p><b>Last Touched:</b> ".date('n/j/Y', $Project->GetLastModified()).$LastTouchedDays."</p>
									<p><b>LS Submit:</b> ".date('n/j/Y', $Project->Created)."</p>
									<p><b>Class Start:</b> ".($Project->CourseStartDate > 0 ? date('n/j/Y', $Project->CourseStartDate) : "N/A")."</p>
								</td>
								<td style='vertical-align:top; padding-top:20px;'><div class='Separator'></div></td>
								<td style='vertical-align:top; padding:22px 11px; width:135px;'>
									<div style='color:#0685c5; font-weight:bold; font-size:14px;'>Project Details</div>
									<p><b>LSC:</b> ".$Project->GetUsers("LSCs")."</p>
										<table style='border-spacing:0px'>
											<tr>
												<td style='width:62px'><b>Milestone Template:</b></td>
												<td>".$Project->GetProductTypesList()."</td>
											</tr>
										</table>
								</td>
								<td style='vertical-align:top; padding-top:20px;'><div class='Separator'></div></td>
								<td style='vertical-align:top; padding:22px 11px; padding-right:0px; width:160px;'>
									<div style='color:#0685c5; font-weight:bold; font-size:14px;'>&nbsp;</div>
									<p><b>ISBN-10:</b> ".$Project->ISBN10."</p>
									<p><b>CA:</b> ".$Project->GetUsers("CreativeAnalysts")."</p>
									<p style='color:#0685c5; text-decoration:underline; cursor:pointer;' onClick=\"MProjects.ShowPreviewBox(this, 'ProjectLink', ".$Project->ID.");\">Direct Project Link</p>
								</td>
							</tr>
						</table>
					</div>";
					
					if(CSecurity::$User->CanAccess("ProjectDetails", "Edit")) {
						echo "
						<div id='ProjectDetailsEdit' style='display:none;'>
							<table class='CForm_Table'>";
								CForm::RandomPrefix();
								echo CForm::AddRow("<div class='Button' value='Save' onClick=\"if($(this).text() == 'show advanced') { $('tr.Advanced').slideDown(); $(this).text('hide advanced'); } else { $('tr.Advanced').slideUp(); $(this).text('show advanced'); }\" style='float:left; width:150px; margin:0px 0px 10px 0px;'>show advanced</div>", "Basic");
								echo CForm::AddTextbox("Project Number", "ProductNumber", $Project->ProductNumber, "", "", "Basic");
								echo CForm::AddTextbox("Project Value", "ProjectValue", $Project->ProjectValue, "", "", "Advanced");
								
								// District Manager
								$DMArray = Array("" => "");
								$DMGroup = new CUsersGroups();
								$DMGroup->OnLoadAll("WHERE `Name` = 'District Manager'");
								$DMs = new CUsers();
								if($DMs->OnLoadAll("WHERE `UsersGroupsID` = ".$DMGroup->ID." && `Active` = 1 ORDER BY `LastName`") !== false) {
									foreach($DMs->Rows as $Row) {
										$DMArray[$Row->ID] = $Row->LastName . ", " . $Row->FirstName;
									}
								}
								echo CForm::AddListbox("District Manager", "DistrictManagerUsersID", $DMArray, $Project->DistrictManagers, "", "", "", "Advanced");
	
								// LSC
								$LSCArray = Array("" => "");
								$LSCGroup = new CUsersGroups();
								$LSCGroup->OnLoadAll("WHERE `Name` = 'Learning Solutions Consultant'");
								$LSCs = new CUsers();
								if($LSCs->OnLoadAll("WHERE `UsersGroupsID` = ".$LSCGroup->ID." && `Active` = 1 ORDER BY `LastName`") !== false) {
									foreach($LSCs->Rows as $Row) {
										$LSCArray[$Row->ID] = $Row->LastName . ", " . $Row->FirstName;
									}
								}
								echo CForm::AddListbox("LSC", "LSCUsersID", $LSCArray, $Project->LSCs, "", "", "", "Basic");
								
								// LSS
								$LSSArray = Array("" => "");
								$LSSGroup = new CUsersGroups();
								$LSSGroup->OnLoadAll("WHERE `Name` = 'Learning Solutions Specialist'");
								$LSSs = new CUsers();
								if($LSSs->OnLoadAll("WHERE `UsersGroupsID` = ".$LSSGroup->ID." && `Active` = 1 ORDER BY `LastName`") !== false) {
									foreach($LSSs->Rows as $Row) {
										$LSSArray[$Row->ID] = $Row->LastName . ", " . $Row->FirstName;
									}
								}
								echo CForm::AddListbox("LSS", "LSSUsersID", $LSSArray, $Project->LSSs, "", "", "", "Basic");
								
								// LSR
								$LSRArray = Array("" => "");
								$LSRGroup = new CUsersGroups();
								$LSRGroup->OnLoadAll("WHERE `Name` = 'Learning Solutions Representative'");
								$LSRs = new CUsers();
								if($LSRs->OnLoadAll("WHERE `UsersGroupsID` = ".$LSRGroup->ID." && `Active` = 1 ORDER BY `LastName`") !== false) {
									foreach($LSRs->Rows as $Row) {
										$LSRArray[$Row->ID] = $Row->LastName . ", " . $Row->FirstName;
									}
								}
								echo CForm::AddListbox("LSR", "LSRUsersID", $LSRArray, $Project->LSRs, "", "", "", "Basic");
								
								// Junior Creative Analyst
								$JCAArray = Array("" => "");
								$JCAGroup = new CUsersGroups();
								$JCAGroup->OnLoadAll("WHERE `Name` = 'Junior Creative Analyst'");
								$JCAs = new CUsers();
								if($JCAs->OnLoadAll("WHERE `UsersGroupsID` = ".$JCAGroup->ID." && `Active` = 1 ORDER BY `LastName`") !== false) {
									foreach($JCAs->Rows as $Row) {
										$JCAArray[$Row->ID] = $Row->LastName . ", " . $Row->FirstName;
									}
								}
								echo CForm::AddListbox("Associate Creative Analyst", "JuniorCreativeAnalystUsersID", $JCAArray, $Project->JuniorCreativeAnalysts, "", "", "", "Basic");
								
								// Creative Analyst
								$CAArray = Array("" => "");
								$CAGroup = new CUsersGroups();
								$CAGroup->OnLoadAll("WHERE `Name` = 'Creative Analyst'");
								$CAs = new CUsers();
								if($CAs->OnLoadAll("WHERE `UsersGroupsID` = ".$CAGroup->ID." && `Active` = 1 ORDER BY `LastName`") !== false) {
									foreach($CAs->Rows as $Row) {
										$CAArray[$Row->ID] = $Row->LastName . ", " . $Row->FirstName;
									}
								}
								echo CForm::AddListbox("Creative Analyst", "CreativeAnalystUsersID", $CAArray, $Project->CreativeAnalysts, "", "", "", "Basic");
								
								// Creative Consultant
								$CCArray = Array("" => "");
								$CCGroup = new CUsersGroups();
								$CCGroup->OnLoadAll("WHERE `Name` = 'Creative Consultant'");
								$CCs = new CUsers();
								if($CCs->OnLoadAll("WHERE `UsersGroupsID` = ".$CCGroup->ID." && `Active` = 1 ORDER BY `LastName`") !== false) {
									foreach($CCs->Rows as $Row) {
										$CCArray[$Row->ID] = $Row->LastName . ", " . $Row->FirstName;
									}
								}
								echo CForm::AddListbox("Creative Consultant", "CreativeConsultantUsersID", $CCArray, $Project->CreativeConsultants, "", "", "", "Basic");
								
								// Institutional Sales Rep
								$RepArray = Array("" => "");
								$RepGroup = new CUsersGroups();
								$RepGroup->OnLoadAll("WHERE `Name` = 'Institutional Sales Rep'");
								$Reps = new CUsers();
								if($Reps->OnLoadAll("WHERE `UsersGroupsID` = ".$RepGroup->ID." && `Active` = 1 ORDER BY `LastName`") !== false) {
									foreach($Reps->Rows as $Row) {
										$RepArray[$Row->ID] = $Row->LastName . ", " . $Row->FirstName;
									}
								}
								echo CForm::AddListbox("Institutional Sales Rep", "InstitutionalSalesRepUsersID", $RepArray, $Project->InstitutionalSalesReps, "", "", "", "Advanced");
								
								//echo CForm::AddTextbox("Business Analyst", "BusinessAnalyst", $Project->BusinessAnalyst);
								
								echo CForm::AddTextbox("Primary Customer", "PrimaryCustomer", $Project->PrimaryCustomer, "", "", "Advanced");
								echo CForm::AddTextbox("Customer Phone", "CustomerPhone", $Project->CustomerPhone, "", "", "Advanced");
								echo CForm::AddTextbox("Customer Email", "CustomerEmail", $Project->CustomerEmail, "", "", "Advanced");
								echo CForm::AddTextbox("Lead Author", "LeadAuthor", $Project->LeadAuthor, "", "", "Advanced");
								echo CForm::AddTextbox("Title", "Title", $Project->Title, "", "", "Advanced");
								echo CForm::AddTextbox("MHID", "MHID", $Project->MHID, "", "", "Basic");
								echo CForm::AddTextbox("Ed", "Ed", $Project->Ed, "", "", "Advanced");
								echo CForm::AddTextbox("Imp", "Imp", $Project->Imp, "", "", "Advanced");
								echo CForm::AddTextbox("Net Price", "NetPrice", $Project->NetPrice, "", "", "Advanced");
								echo CForm::AddTextbox("Estimated UMC", "EstimatedUMC", $Project->EstimatedUMC, "", "", "Advanced");
								echo CForm::AddTextbox("Actual UMC", "ActualUMC", $Project->ActualUMC, "", "", "Advanced");
								echo CForm::AddTextbox("School", "School", $Project->School, "", "", "Basic");
								echo CForm::AddDropdown("Status", "Status", CProjects::GetAllStatus(), $Project->Status);
								echo CForm::AddDatepicker("Course Start Date", "CourseStartDate", ($Project->CourseStartDate > 0 ? $Project->CourseStartDate : ""), "", "", "", "Basic");
								echo CForm::AddDatepicker("Due Date", "DueDate", ($Project->DueDate > 0 ? $Project->DueDate : ""), "", "", "", "Basic");
								echo CForm::AddTextbox("QOH", "QOH", $Project->QOH, "", "", "Advanced");
								echo CForm::AddDatepicker("QOH Date", "QOHDate", ($Project->QOHDate > 0 ? $Project->QOHDate : ""), "", "", "", "Advanced");
	
	
								// Milestone template (former Product Types)
								$Mt = new CProductTypes();
								$Mt->OnLoadAll("WHERE `Active` = 1 ORDER BY `Name` ASC");
								$ValuesArray = array(0 => "N/A") + CForm::RowsToArray($Mt->Rows, "Name");
								$PTIds = array_keys($Project->ProductTypes);
								echo CForm::AddDropdown("Milestone template", "ProductTypes", $ValuesArray, $PTIds[0]);
		
								echo CForm::AddTextbox("Stat Sponsor Code", "StatSponsorCode", $Project->StatSponsorCode, "", "", "Advanced");
								echo CForm::AddTextbox("2012 YTD Sales Net Units", "2012YTDSalesNetUnits", $Project->{"2012YTDSalesNetUnits"}, "", "", "Advanced");
								echo CForm::AddTextbox("2012 YTD Sales Net Revenue", "2012YTDSalesNetRevenue", $Project->{"2012YTDSalesNetRevenue"}, "", "", "Advanced");
								echo CForm::AddTextbox("2012 YTD Sales Gross Units", "2012YTDSalesGrossUnits", $Project->{"2012YTDSalesGrossUnits"}, "", "", "Advanced");
								echo CForm::AddTextbox("2012 YTD Sales Gross Revenue", "2012YTDSalesGrossRevenue", $Project->{"2012YTDSalesGrossRevenue"}, "", "", "Advanced");
								echo CForm::AddTextbox("2011 Sales Net Units", "2011SalesNetUnits", $Project->{"2011SalesNetUnits"}, "", "", "Advanced");
								echo CForm::AddTextbox("2011 Sales Net Revenue", "2011SalesNetRevenue", $Project->{"2011SalesNetRevenue"}, "", "", "Advanced");
								echo CForm::AddTextbox("2011 Sales Gross Units", "2011SalesGrossUnits", $Project->{"2011SalesGrossUnits"}, "", "", "Advanced");
								echo CForm::AddTextbox("2011 Sales Gross Revenue", "2011SalesGrossRevenue", $Project->{"2011SalesGrossRevenue"}, "", "", "Advanced");
								echo CForm::AddTextbox("Request Plant", "RequestPlant", $Project->RequestPlant, "", "", "Basic");
								echo CForm::AddTextbox("Plant Paid", "PlantPaid", $Project->PlantPaid, "", "", "Basic");
								echo CForm::AddTextbox("Plant Left", "PlantLeft", $Project->PlantLeft, "", "", "Basic");
								echo CForm::AddTextbox("Vendor", "VenderUsed", $Project->VenderUsed, "", "", "Basic");
								echo CForm::AddDatepicker("Date Paid", "DatePaid", ($Project->DatePaid > 0 ? $Project->DatePaid : ""), "", "", "", "Basic");
								echo CForm::AddTextbox("ISBN-10", "ISBN10", $Project->ISBN10, "", "", "Basic");
								echo CForm::AddTextbox("Custom ISBN", "CustomISBN", $Project->CustomISBN, "", "", "Basic");
								echo CForm::AddTextbox("Spec doc link", "SpecDocLink", $Project->SpecDocLink, "", "", "Basic");
								echo CForm::AddTextbox("Connect Request ID link", "ConnectRequestIDLink", $Project->ConnectRequestIDLink, "", "", "Basic");
								echo CForm::AddHidden("ID", $Project->ID);
								echo "								
								<tr>
									<td></td>
									<td>
										<div class='Button' value='Save' onClick=\"MProjects.Save('".CForm::GetPrefix()."');\">save</div>
										<div class='Button' value='Cancel' onClick=\"MProjects.ViewDetails(".$Project->ID.");\">cancel</div>";
										if(CSecurity::$User->CanAccess("ProjectDetails", "Delete")) echo "<div class='Button' value='Delete' onClick=\"MProjects.DeleteProject('".CForm::GetPrefix()."', ".$Project->ID.");\">delete</div>";
										echo "
										<br style='clear:both;'><br>
									</td>
								</tr>
							</table>
						</div>";
					}
					echo "
				</td>
				<td style='width:30px;'></td>
			</tr>
			<tr>
				<td></td>
				<td colspan='7' style='border-bottom:1px solid #d1d1d1 !important; border-top:1px solid #d1d1d1 !important;'>
					<div style='font-weight:bold; font-size:14px; margin-top:12px; margin-bottom:6px;'>Recent Messages <span style='font-size:10px;'><a style='color:#0685c5;cursor:pointer;text-decoration:underline;' onClick=\"MProjects.MoveToMessages();\">view all</a></span></div>
					<div style='margin-bottom:40px; clear:both;'>";
					$Messages = new CProjectsMessages();
					if($Messages->OnLoadAll("WHERE `ProjectsID` = ".$Project->ID." ORDER BY `Created` DESC LIMIT 5") === false) {		//&& `Created` >= ".(time() - (60 * 60 * 24 * 7))." 
						echo "No recent messages";
						
					} else {
						foreach($Messages->Rows as $Message) {
							$User = new CUsers();
							$User->OnLoad($Message->CreatedUsersID);
							echo "
							<strong>".$Message->Title."</strong>
							<p style='line-height:15px;'>".substr($Message->Content, 0, 300)."... <span style='color:#d74c4c; font-weight:bold; font-style:italic;'>".date('n/j/Y', $Message->Created)." ".$User->LastName.", ".$User->FirstName."</span></p>
							<div style='width:66px; height:0px; border-bottom:1px solid #d1d1d0; margin:10px 0px;'></div>
							";
						}
					}
					echo "
					</div>
				</td>
				<td style='width:30px;'></td>
			</tr>
			<tr><td><div id='MilestoneTarget'></div></td></tr>
			<tr>
				<td></td>
				<td colspan='7'>
					<div style='font-weight:bold; font-size:14px; margin-top:12px; margin-bottom:6px;'>
						<div style='float:left;'>Milestone Completion // <span style='color:#5a8111;'>".number_format($MilestonePercentage * 100)."% Complete</span></div>";
						if(CSecurity::$User->CanAccess("Milestones", "Add")) {
							echo "<div id='AddMilestone' onClick=\"$('#AddNewMilestone').slideDown();\"></div>";
						}
						echo "
						<br style='clear:both;'>
					</div>";
					if(CSecurity::$User->CanAccess("Milestones", "Add")) {
						echo "
						<div id='AddNewMilestone' style='width:100%; margin:10px auto; display:none;'>
							<table width='100%' cellspacing='0' cellpadding='0'>
								<tr>
									<td width='100%' valign='top'>
										<table class='CForm_Table TableFormGroup'>";
										
											CForm::RandomPrefix();
											
											$DefaultMilestones = new CMilestones();
											$DefaultMilestones->OnLoadAll("WHERE `Active` = 1 ORDER BY `Name` ASC");
											$DefaultArray = Array(0 => "-- Default Milestones --");
											foreach($DefaultMilestones->Rows as $Row) {
												$DefaultArray[$Row->ID] = $Row->Name;
											}
											//echo CForm::AddDropdown("Default Milestone", "DefaultMilestone", $DefaultArray);
											echo "
											<tr>
												<td valign='top' align='right' class='CForm_Name'><b onmouseout='CTooltip.Hide();' onmouseover='CTooltip.Show('');'>Default Milestone:</b>&nbsp;</td>
												<td valign='top' class='CForm_Value'>
												<select class='CForm_Dropdown' title='' id='DefaultMilestone' name='DefaultMilestone' onChange=\"MProjects.LoadDefaultMilestone('".CForm::GetPrefix()."', $(this).val());\">";
												foreach($DefaultArray as $Key => $Value) {
													echo "<option value='".$Key."'>".$Value."</option>";
												}
												echo "</select>
												</td>
											</tr>
											";
											$Users = CUsers::GetAllAssignableToMilestone();
											if($Users === false)
												$Users = Array(0 => "Nobody");
											else
												$Users = Array(0 => "Nobody") + $Users->MultipleColumnRowsToArray("LastName,FirstName");
											echo CForm::AddTextbox("Name", "Name", "", "Please enter a Name");
											echo CForm::AddYesNo("Customer Approval", "CustomerApproval", 0, "YesNo");
											echo CForm::AddTextarea("Summary", "Summary", "");
											echo CForm::AddDatepicker("Estimated Start Date", "EstimatedStartDate", time(), "", "");
											echo CForm::AddDatepicker("Expected Delivery Date", "ExpectedDeliveryDate", time(), "", "", "Please enter an Expected Delivery Date");
											echo CForm::AddDatepicker("Actual Delivery Date", "ActualDeliveryDate", time(), "", "");											
											echo CForm::AddTextbox("Plant Allocated", "PlantAllocated", "");
											echo CForm::AddDropdown("Assigned To", "AssignedTo", $Users, "");	
											echo CForm::AddDropdown("Status", "Status", CMilestones::GetStatusList(), "");
											echo CForm::AddHidden("ProjectsID", $ProjectID);
										
										echo "
										</table>
									</td>
								</tr>
								<tr>
									<td colspan='3' align='right'>
									
									<input type='hidden' value='0' name='ID' id='ID'/>
									<div class='Button' value='Save' onClick=\"MProjects.AddMilestone('".CForm::GetPrefix()."');\">save</div>
									<div class='Button' value='Cancel' onClick=\"MProjects.ViewDetails(".$Project->ID.");\">cancel</div>
									
									</td>
								</tr>
							</table>
						</div>";
					}
					echo "
					<div class='CompletionWrapper' style='width:672px;'>
						<div class='CompletionBar' style='width:".$MilestoneBarWidth."px;'></div>
						<div class='CompletionPercentage'>".number_format($MilestonePercentage * 100)."%</div>
					</div>
				</td>
				<td style='width:30px;'></td>
			</tr>
			";
			if(CSecurity::$User->CanAccess("Milestones", "Access")) {
				$i = 1;
				foreach($Project->Milestones as $Milestone) {
					echo "
					<tr>
						<td>".($Milestone->Status === "Complete" ? "<div class='Complete' style='float:right; position:relative; top:2px;'></div>" : "")."</td>
						<td colspan='7'>
							<div style='padding-left:11px; font-weight:bold; font-size:14px; margin-top:12px; margin-bottom:6px; cursor:pointer;' id='MilestoneLabel".$Milestone->ID."'><span style='float:left;'>[".$i.".] ".$Milestone->Name."</span> <div class='MilestoneDownArrow'></div></div>
							<div class='MilestoneDetails' id='Milestone".$Milestone->ID."' style='padding-left:58px; display:none; clear:both; margin:12px 0px; position:relative; top:12px;'>
								<table width='100%' cellspacing='0' cellpadding='0'>
									<tr>
										<td width='100%' valign='top'>
											<table class='CForm_Table TableFormGroup'>";
											
												if(CSecurity::$User->CanAccess("Milestones", "Edit")) {
													CForm::RandomPrefix();
													$Users = CUsers::GetAllAssignableToMilestone();
													if($Users === false)
														$Users = Array(0 => "Nobody");
													else
														$Users = Array(0 => "Nobody") + $Users->MultipleColumnRowsToArray("LastName,FirstName");
													
													echo CForm::AddTextbox("Name", "Name", $Milestone->Name, "Please enter a Name");
													echo CForm::AddYesNo("Customer Approval", "CustomerApproval", $Milestone->CustomerApproval, "YesNo");
													echo CForm::AddTextarea("Summary", "Summary", $Milestone->Summary);
													echo CForm::AddDatepicker("Estimated Start Date", "EstimatedStartDate", ($Milestone->EstimatedStartDate > 0 ? $Milestone->EstimatedStartDate : ""), "", "");
													echo CForm::AddDatepicker("Expected Delivery Date", "ExpectedDeliveryDate", ($Milestone->ExpectedDeliveryDate > 0 ? $Milestone->ExpectedDeliveryDate : ""), "", "", "Please enter an Expected Delivery Date");
													echo CForm::AddDatepicker("Actual Delivery Date", "ActualDeliveryDate", ($Milestone->ActualDeliveryDate > 0 ? $Milestone->ActualDeliveryDate : ""), "", "");
													echo CForm::AddTextbox("Plant Allocated", "PlantAllocated", $Milestone->PlantAllocated);
													echo CForm::AddDropdown("Assigned To", "AssignedTo", $Users, $Milestone->AssignedTo);	
													echo CForm::AddYesNo("Complete", "Status", $Milestone->IsComplete(), "YesNo");
													echo CForm::AddHidden("ProjectsID", $ProjectID);
													echo CForm::AddHidden("MilestoneID", $Milestone->ID);
												} else {
													echo CForm::AddRow("Name", $Milestone->Name);
													echo CForm::AddRow("Customer Approval", $Milestone->CustomerApproval);
													echo CForm::AddRow("Summary", $Milestone->Summary);
													echo CForm::AddRow("Estimated Start Date", ($Milestone->EstimatedStartDate > 0 ? date('F j, Y', $Milestone->EstimatedStartDate) : "N/A"));
													echo CForm::AddRow("Expected Delivery Date", ($Milestone->ExpectedDeliveryDate > 0 ? date('F j, Y', $Milestone->ExpectedDeliveryDate) : "N/A"));
													echo CForm::AddRow("Actual Delivery Date", ($Milestone->ActualDeliveryDate > 0 ? date('F j, Y', $Milestone->ActualDeliveryDate) : "N/A"));
													echo CForm::AddRow("Plant Allocated", $Milestone->PlantAllocated);
													echo CForm::AddRow("Assigned To", $Milestone->AssignedToUser());
													echo CForm::AddRow("Status", $Milestone->Status);
												}
											
											echo "
											</table>
										</td>
									</tr>
									<tr>
										<td colspan='3' align='right'>";
										if(CSecurity::$User->CanAccess("Milestones", "Edit")) {
											echo "
											<input type='hidden' value='0' name='ID' id='ID'/>
											<div class='Button' value='Save' onClick=\"MProjects.AddMilestone('".CForm::GetPrefix()."');\">save</div>
											<div class='Button' value='Cancel' onClick=\"MProjects.ViewDetails(".$Project->ID.");\">cancel</div>";
											if(CSecurity::$User->CanAccess("Milestones", "Delete")) echo "<div class='Button' value='Delete' onClick=\"MProjects.DeleteMilestone('".CForm::GetPrefix()."', ".$Milestone->ID.");\">delete</div>";
										}
										echo "
										</td>
									</tr>
								</table>
							</div>
						</td>
						<td style='width:30px;'>
							<script type='text/javascript'>
								$('#MilestoneLabel".$Milestone->ID."').toggle(function(){
									$(this).css('color', '#0685c5');
									$('#Milestone".$Milestone->ID."').slideDown();
									$(this).children('.MilestoneDownArrow').slideDown();
									$('#HeightCalculator').animate({ height : ($('#ProjectDetailsContainer').height() + 1000) + \"px\" }, 500, 'swing');
								}, function() {
									$(this).css('color', '#222222');
									$('#Milestone".$Milestone->ID."').slideUp();
									$(this).children('.MilestoneDownArrow').slideUp();
									$('#HeightCalculator').animate({ height : ($('#ProjectDetailsContainer').height() + 1000) + \"px\" }, 500, 'swing');
								});
								AdjustHeight = function() {
									$('#HeightCalculator').animate({ height : ($('#ProjectDetailsContainer').height() + 1000) + \"px\" }, 500, 'swing');
								}
								$('#".CForm::GetPrefix()."Status').change(function(){
									if($('#".CForm::GetPrefix()."Status').val() == 'Complete') {
										$('#".CForm::GetPrefix()."ActualDeliveryDate').val('".date('F j, Y')."');
									}
								});
							</script>
						</td>
					</tr>
					";
					$i++;
					
					if(CSecurity::$User->CanAccess("MilestonesToDos", "Access")) {
						$j = 1;
						$ToDos = new CProjectsMilestonesToDos();
						if($ToDos->OnLoadAll("WHERE `MilestoneID` = ".$Milestone->ID." && `Deleted` = 0") !== false) {
							foreach($ToDos->Rows as $ToDo) {
								echo "
								<tr>
									<td colspan='7' style='padding-left:12px;'>
										<table style='width:100%;'>
											<tr>
												<td style='width: 60px;'>".($ToDo->Complete == 1 ? "<div class='TodoComplete'></div>" : "")."</td>
												<td>
													<div style='padding-left:11px; font-weight:bold; font-size:12px; margin-top:12px; margin-bottom:6px; cursor:pointer;' id='ToDoLabel".$ToDo->ID."'><span style='float:left;'>To-Do: ".$ToDo->Name."</span> <div class='ToDoDownArrow'></div></div>
													<div class='MilestoneToDoDetails' id='MilestoneToDo".$ToDo->ID."' style='padding-left:58px; display:none; clear:both; margin:12px 0px; position:relative; top:12px;'>
														<table width='100%' cellspacing='0' cellpadding='0'>
															<tr>
																<td width='100%' valign='top'>
																	<table class='CForm_Table TableFormGroup'>";
																	
																		if(CSecurity::$User->CanAccess("MilestonesToDos", "Edit")) {
																			CForm::RandomPrefix();
																			
																			echo CForm::AddTextbox("Name", "Name", $ToDo->Name, "Please enter a Name");
																			echo CForm::AddYesNo("Complete", "Complete", $ToDo->Complete, "YesNo");
																			echo CForm::AddTextarea("Comment", "Comment", $ToDo->Comment);
																			echo CForm::AddYesNo("Comment Required", "CommentRequired", $ToDo->CommentRequired, "YesNo");
																			echo CForm::AddHidden("MilestoneID", $Milestone->ID);
																			echo CForm::AddHidden("ProjectsID", $ProjectID);
																			echo CForm::AddHidden("ToDoID", $ToDo->ID);
																		} else {
																			echo CForm::AddRow("Name", $ToDo->Name);
																			echo CForm::AddRow("Complete", $ToDo->Complete);
																			echo CForm::AddRow("Comment", $ToDo->Comment);
																			echo CForm::AddRow("Comment Required", $ToDo->CommentRequired);
																		}
																	
																	echo "
																	</table>
																</td>
															</tr>
															<tr>
																<td colspan='3' align='right'>";
																if(CSecurity::$User->CanAccess("MilestonesToDos", "Edit")) {
																	echo "
																	<input type='hidden' value='0' name='ID' id='ID'/>
																	<div class='Button' value='Save' onClick=\"MProjects.AddMilestoneToDo('".CForm::GetPrefix()."');\">save</div>
																	<div class='Button' value='Cancel' onClick=\"MProjects.ViewDetails(".$Project->ID.");\">cancel</div>";
																	if(CSecurity::$User->CanAccess("MilestonesToDos", "Delete")) echo "<div class='Button' value='Delete' onClick=\"MProjects.DeleteMilestoneToDo('".CForm::GetPrefix()."', ".$ToDo->ID.");\">delete</div>";
																	echo "
																	<script type='text/javascript'>
																		$('#ToDoLabel".$ToDo->ID."').toggle(function(){
																			$(this).css('color', '#0685c5');
																			$('#MilestoneToDo".$ToDo->ID."').slideDown();
																			$(this).children('.MilestoneDownArrow').slideDown();
																			$('#HeightCalculator').animate({ height : ($('#ProjectDetailsContainer').height() + 1000) + \"px\" }, 500, 'swing');
																		}, function() {
																			$(this).css('color', '#222222');
																			$('#MilestoneToDo".$ToDo->ID."').slideUp();
																			$(this).children('.MilestoneDownArrow').slideUp();
																			$('#HeightCalculator').animate({ height : ($('#ProjectDetailsContainer').height() + 1000) + \"px\" }, 500, 'swing');
																		});
																	</script>
																	";
																}
																echo "
																</td>
															</tr>
														</table>
													</div>
												</td>
											<tr>
										</table>
									</td>
									<td></td>
								</tr>";
								$j++;
							}
						}
					}
					if(CSecurity::$User->CanAccess("MilestonesToDos", "Add")) {
						echo "
						<tr>
							<td></td>
							<td colspan='7' style='padding-left:12px;'>
								<div style='font-weight:bold; font-size:14px; margin-top:12px; margin-bottom:6px;'>
									<div class='Button' onClick=\"$('#AddMilestone".$Milestone->ID."ToDoList').slideUp(); $('#AddMilestone".$Milestone->ID."ToDo').slideDown();\">new to-do</div>
									<div class='Button' onClick=\"$('#AddMilestone".$Milestone->ID."ToDo').slideUp(); $('#AddMilestone".$Milestone->ID."ToDoList').slideDown();\">new to-do list</div>
									<br style='clear:both;'>
								</div>
								<div id='AddMilestone".$Milestone->ID."ToDoList' style='width:100%; margin:10px auto; display:none;'>
									<table width='100%' cellspacing='0' cellpadding='0'>
										<tr>
											<td width='100%' valign='top'>
												<table class='CForm_Table TableFormGroup'>";
												
													CForm::RandomPrefix();
													
													$DefaultToDosLists = new CToDosLists();
													$DefaultToDosLists->OnLoadAll("WHERE `Active` = 1 ORDER BY `Name` ASC");
													$DefaultArray = Array(0 => "-- Default To-Do Lists --");
													foreach($DefaultToDosLists->Rows as $Row) {
														$DefaultArray[$Row->ID] = $Row->Name;
													}
													//echo CForm::AddDropdown("Default Milestone", "DefaultMilestone", $DefaultArray);
													echo "
													<tr>
														<td valign='top' align='right' class='CForm_Name'><b onmouseout='CTooltip.Hide();' onmouseover='CTooltip.Show('');'>Default To-Do List:</b>&nbsp;</td>
														<td valign='top' class='CForm_Value'>
															<select class='CForm_Dropdown' title='' id='".CForm::GetPrefix()."DefaultMilestoneToDoList' name='".CForm::GetPrefix()."DefaultMilestoneToDoList' onChange=\"MProjects.LoadDefaultToDoList('".CForm::GetPrefix()."', $(this).val());\">";
															foreach($DefaultArray as $Key => $Value) {
																echo "<option value='".$Key."'>".$Value."</option>";
															}
															echo "</select>
														</td>
													</tr>
													<tr>
														<td valign='top' align='right' class='CForm_Name'></td>
														<td valign='top' class='CForm_Value'>
															<div id='MilestoneToDoListMembers'></div>
														</td>
													</tr>
													";
													
													echo CForm::AddHidden("MilestoneID", $Milestone->ID);
													echo CForm::AddHidden("ProjectsID", $ProjectID);
												
												echo "
												</table>
											</td>
										</tr>
										<tr>
											<td colspan='3' align='right'>
											
											<input type='hidden' value='0' name='ID' id='ID'/>
											<div class='Button' value='Save' onClick=\"MProjects.AddMilestoneToDoList('".CForm::GetPrefix()."');\">add to-do's</div>
											<div class='Button' value='Cancel' onClick=\"MProjects.ViewDetails(".$Project->ID.");\">cancel</div>
											
											</td>
										</tr>
									</table>
								</div>
								<div id='AddMilestone".$Milestone->ID."ToDo' style='width:100%; margin:10px auto; display:none;'>
									<table width='100%' cellspacing='0' cellpadding='0'>
										<tr>
											<td width='100%' valign='top'>
												<table class='CForm_Table TableFormGroup'>";
												
													CForm::RandomPrefix();
													
													$DefaultToDos = new CToDos();
													$DefaultToDos->OnLoadAll("WHERE `Active` = 1 ORDER BY `Name` ASC");
													$DefaultArray = Array(0 => "-- Default To-Do's --");
													foreach($DefaultToDos->Rows as $Row) {
														$DefaultArray[$Row->ID] = $Row->Name;
													}
													//echo CForm::AddDropdown("Default Milestone", "DefaultMilestone", $DefaultArray);
													echo "
													<tr>
														<td valign='top' align='right' class='CForm_Name'><b onmouseout='CTooltip.Hide();' onmouseover='CTooltip.Show('');'>Default To-Do:</b>&nbsp;</td>
														<td valign='top' class='CForm_Value'>
															<select class='CForm_Dropdown' title='' id='DefaultToDo' name='DefaultToDo' onChange=\"MProjects.LoadDefaultToDo('".CForm::GetPrefix()."', $(this).val());\">";
															foreach($DefaultArray as $Key => $Value) {
																echo "<option value='".$Key."'>".$Value."</option>";
															}
															echo "</select>
														</td>
													</tr>
													";
													
													echo CForm::AddTextbox("Name", "Name", "", "Please enter a Name");
													echo CForm::AddYesNo("Complete", "Complete", 0, "YesNo");
													echo CForm::AddTextarea("Comment", "Comment", "");
													echo CForm::AddYesNo("Comment Required", "CommentRequired", "", "YesNo");
													echo CForm::AddHidden("MilestoneID", $Milestone->ID);
													echo CForm::AddHidden("ProjectsID", $ProjectID);
												
												echo "
												</table>
											</td>
										</tr>
										<tr>
											<td colspan='3' align='right'>
											
											<input type='hidden' value='0' name='ID' id='ID'/>
											<div class='Button' value='Save' onClick=\"MProjects.AddMilestoneToDo('".CForm::GetPrefix()."');\">save</div>
											<div class='Button' value='Cancel' onClick=\"MProjects.ViewDetails(".$Project->ID.");\">cancel</div>
											
											</td>
										</tr>
									</table>
								</div>
							</td>
							<td></td>
						</tr>
						";
					}
				}
			}
			
			echo "
			<tr><td style='height:30px;'></td></tr>
			<tr><td><div id='ToDoTarget'></div></td></tr>
			<tr>
				<td></td>
				<td colspan='7' style='border-top:1px solid #d1d1d1 !important;'>
					<div style='font-weight:bold; font-size:14px; margin-top:12px; margin-bottom:6px;'>
						<div style='float:left;'>Project To-Do's</div>";
						if(CSecurity::$User->CanAccess("ToDos", "Add")) {
							echo "<div id='AddToDo' onClick=\"$('#AddNewToDo').slideDown();\"></div>";
						}
						echo "
						<br style='clear:both;'>
					</div>";
					if(CSecurity::$User->CanAccess("ToDos", "Add")) {
						echo "
						<div id='AddNewToDo' style='width:100%; margin:10px auto; display:none;'>
							<table width='100%' cellspacing='0' cellpadding='0'>
								<tr>
									<td width='100%' valign='top'>
										<table class='CForm_Table TableFormGroup'>";
										
											CForm::RandomPrefix();
											
											$DefaultToDos = new CToDos();
											$DefaultToDos->OnLoadAll("WHERE `Active` = 1 ORDER BY `Name` ASC");
											$DefaultArray = Array(0 => "-- Default To Do's --");
											foreach($DefaultToDos->Rows as $Row) {
												$DefaultArray[$Row->ID] = $Row->Name;
											}
											//echo CForm::AddDropdown("Default Milestone", "DefaultMilestone", $DefaultArray);
											echo "
											<tr>
												<td valign='top' align='right' class='CForm_Name'><b onmouseout='CTooltip.Hide();' onmouseover='CTooltip.Show('');'>Default To Do:</b>&nbsp;</td>
												<td valign='top' class='CForm_Value'>
												<select class='CForm_Dropdown' title='' id='DefaultToDo' name='DefaultToDo' onChange=\"MProjects.LoadDefaultToDo('".CForm::GetPrefix()."', $(this).val());\">";
												foreach($DefaultArray as $Key => $Value) {
													echo "<option value='".$Key."'>".$Value."</option>";
												}
												echo "</select>
												</td>
											</tr>
											";
											
											echo CForm::AddTextbox("Name", "Name", "", "Please enter a Name");
											echo CForm::AddYesNo("Complete", "Complete", 0, "YesNo");
											echo CForm::AddTextarea("Comment", "Comment", "");
											echo CForm::AddYesNo("Comment Required", "CommentRequired", "", "YesNo");
											//echo CForm::AddHidden("MilestoneID", $Milestone->ID);
											echo CForm::AddHidden("ProjectsID", $ProjectID);
										
										echo "
										</table>
									</td>
								</tr>
								<tr>
									<td colspan='3' align='right'>
									
									<input type='hidden' value='0' name='ID' id='ID'/>
									<div class='Button' value='Save' onClick=\"MProjects.AddProjectToDo('".CForm::GetPrefix()."');\">save</div>
									<div class='Button' value='Cancel' onClick=\"MProjects.ViewDetails(".$Project->ID.");\">cancel</div>
									
									</td>
								</tr>
							</table>
						</div>";
					}
					echo "
				</td>
				<td style='width:30px;'></td>
			</tr>
			";
			if(CSecurity::$User->CanAccess("ToDos", "Access")) {
				$i = 1;
				foreach($Project->ToDos as $ToDo) {
					echo "
					<tr>
						<td>".($ToDo->Status === "Complete" ? "<div class='Complete' style='float:right; position:relative; top:2px;'></div>" : "")."</td>
						<td colspan='7'>
							<div style='padding-left:11px; font-weight:bold; font-size:14px; margin-top:12px; margin-bottom:6px; cursor:pointer;' id='ToDoLabel".$ToDo->ID."'><span style='float:left;'>[".$i.".] ".$ToDo->Name."</span> <div class='ToDoDownArrow'></div></div>
							<div class='ToDoDetails' id='ToDo".$ToDo->ID."' style='padding-left:58px; display:none; clear:both; margin:12px 0px; position:relative; top:12px;'>
								<table width='100%' cellspacing='0' cellpadding='0'>
									<tr>
										<td width='100%' valign='top'>
											<table class='CForm_Table TableFormGroup'>";
											
												if(CSecurity::$User->CanAccess("ToDos", "Edit")) {
													CForm::RandomPrefix();
													$Users = CUsers::GetAllAssignableToTodos();
													if($Users === false)
														$Users = Array(0 => "Nobody");
													else
														$Users = Array(0 => "Nobody") + $Users->MultipleColumnRowsToArray("LastName,FirstName");
														
													echo CForm::AddTextbox("Name", "Name", $ToDo->Name, "Please enter a Name");
													echo CForm::AddYesNo("Complete", "Complete", $ToDo->Complete, "YesNo");
													echo CForm::AddTextarea("Comment", "Comment", $ToDo->Comment);
													echo CForm::AddYesNo("Comment Required", "CommentRequired", $ToDo->CommentRequired, "YesNo");
													echo CForm::AddDropdown("Assigned To", "AssignedTo", $Users, $ToDo->AssignedTo);	
													echo CForm::AddHidden("ProjectsID", $ProjectID);
													echo CForm::AddHidden("ToDoID", $ToDo->ID);
												} else {
													echo CForm::AddRow("Name", $ToDo->Name);
													echo CForm::AddRow("Complete", $ToDo->Complete);
													echo CForm::AddRow("Comment", $ToDo->Comment);
													echo CForm::AddRow("Comment Required", $ToDo->CommentRequired);
													echo CForm::AddRow("Comment Required", $ToDo->AssignedToUser());
												}
											
											echo "
											</table>
										</td>
									</tr>
									<tr>
										<td colspan='3' align='right'>";
										if(CSecurity::$User->CanAccess("ToDos", "Edit")) {
											echo "
											<input type='hidden' value='0' name='ID' id='ID'/>
											<div class='Button' value='Save' onClick=\"MProjects.AddProjectToDo('".CForm::GetPrefix()."');\">save</div>
											<div class='Button' value='Cancel' onClick=\"MProjects.ViewDetails(".$Project->ID.");\">cancel</div>";
											if(CSecurity::$User->CanAccess("ToDos", "Delete")) echo "<div class='Button' value='Delete' onClick=\"MProjects.DeleteToDo('".CForm::GetPrefix()."', ".$ToDo->ID.");\">delete</div>";
										}
										echo "
										</td>
									</tr>
								</table>
							</div>
						</td>
						<td style='width:30px;'>
							<script type='text/javascript'>
								$('#ToDoLabel".$ToDo->ID."').toggle(function(){
									$(this).css('color', '#0685c5');
									$('#ToDo".$ToDo->ID."').slideDown();
									$(this).children('.ToDoDownArrow').slideDown();
									$('#HeightCalculator').animate({ height : ($('#ProjectDetailsContainer').height() + 1000) + \"px\" }, 500, 'swing');
								}, function() {
									$(this).css('color', '#222222');
									$('#ToDo".$ToDo->ID."').slideUp();
									$(this).children('.ToDoDownArrow').slideUp();
									$('#HeightCalculator').animate({ height : ($('#ProjectDetailsContainer').height() + 1000) + \"px\" }, 500, 'swing');
								});
								AdjustHeight = function() {
									$('#HeightCalculator').animate({ height : ($('#ProjectDetailsContainer').height() + 1000) + \"px\" }, 500, 'swing');
								}
								$('#".CForm::GetPrefix()."Status').change(function(){
									if($('#".CForm::GetPrefix()."Status').val() == 'Complete') {
										$('#".CForm::GetPrefix()."ActualDeliveryDate').val('".date('F j, Y')."');
									}
								});
							</script>
						</td>
					</tr>
					";
					$i++;
				}
			}
			echo "
		</table>
	</div>
	";	
?>
