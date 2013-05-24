<?
	$Project = $this->Parent->TableObject;
	CForm::RandomPrefix();
?>
<h1>New Project</h1>

<table width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td width="100%" valign="top">

		<table class="CForm_Table TableFormGroup">
		<?
			echo CForm::AddTextbox("Product Number", "ProductNumber", $Project->ProductNumber);
			echo CForm::AddTextbox("Project Value", "ProjectValue", $Project->ProjectValue);
			
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
			echo CForm::AddListbox("District Manager", "DistrictManagerUsersID", $DMArray, $Project->DistrictManagerUsersID);
			
			// Sales Rep
			$RepArray = Array("" => "");
			$RepGroup = new CUsersGroups();
			$RepGroup->OnLoadAll("WHERE `Name` = 'Sales Rep'");
			$Reps = new CUsers();
			if($Reps->OnLoadAll("WHERE `UsersGroupsID` = ".$RepGroup->ID." && `Active` = 1 ORDER BY `LastName`") !== false) {
				foreach($Reps->Rows as $Row) {
					$RepArray[$Row->ID] = $Row->LastName . ", " . $Row->FirstName;
				}
			}
			echo CForm::AddListbox("Sales Rep", "SalesRepUsersID", $RepArray, $Project->SalesRepUsersID);
			
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
			echo CForm::AddListbox("LSC", "LSCUsersID", $LSCArray, $Project->LSCUsersID);
			
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
			echo CForm::AddListbox("LSS", "LSSUsersID", $LSSArray, $Project->LSSUsersID);
			
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
			echo CForm::AddListbox("LSR", "LSRUsersID", $LSRArray, $Project->LSRUsersID);
			
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
			echo CForm::AddListbox("Junior Creative Analyst", "JuniorCreativeAnalystUsersID", $JCAArray, $Project->JuniorCreativeAnalysts);
			
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
			echo CForm::AddListbox("Creative Analyst", "CreativeAnalystUsersID", $CAArray, $Project->CreativeAnalysts);
			
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
			echo CForm::AddListbox("Creative Consultant", "CreativeConsultantUsersID", $CCArray, $Project->CreativeConsultants);
			
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
			echo CForm::AddListbox("Institutional Sales Rep", "InstitutionalSalesRepUsersID", $RepArray, $Project->InstitutionalSalesRepUsersID);
			
			echo CForm::AddTextbox("Business Analyst", "BusinessAnalyst", $Project->BusinessAnalyst);
			
			// Product Manager
			$PMArray = Array("" => "");
			$PMGroup = new CUsersGroups();
			$PMGroup->OnLoadAll("WHERE `Name` = 'Product Manager'");
			$PMs = new CUsers();
			if($PMs->OnLoadAll("WHERE `UsersGroupsID` = ".$PMGroup->ID." && `Active` = 1 ORDER BY `LastName`") !== false) {
				foreach($PMs->Rows as $Row) {
					$PMArray[$Row->ID] = $Row->LastName . ", " . $Row->FirstName;
				}
			}
			echo CForm::AddListbox("Product Manager", "ProductManagerUsersID", $PMArray, $Project->ProductManagerUsersID);
			
			echo CForm::AddTextbox("Primary Customer", "PrimaryCustomer", $Project->PrimaryCustomer);
			echo CForm::AddTextbox("Customer Phone", "CustomerPhone", $Project->CustomerPhone);
			echo CForm::AddTextbox("Customer Email", "CustomerEmail", $Project->CustomerEmail);
			echo CForm::AddTextbox("Lead Author", "LeadAuthor", $Project->LeadAuthor);
			echo CForm::AddTextbox("Title", "Title", $Project->Title);
			echo CForm::AddTextbox("MHID", "MHID", $Project->MHID);
			echo CForm::AddTextbox("Ed", "Ed", $Project->Ed);
			echo CForm::AddTextbox("Imp", "Imp", $Project->Imp);
			echo CForm::AddTextbox("Net Price", "NetPrice", $Project->NetPrice);
			echo CForm::AddTextbox("Estimated UMC", "EstimatedUMC", $Project->EstimatedUMC);
			echo CForm::AddTextbox("Actual UMC", "ActualUMC", $Project->ActualUMC);
			echo CForm::AddTextbox("School", "School", $Project->School);
			echo CForm::AddTextbox("Status", "Status", $Project->Status);
			echo CForm::AddDatepicker("Course Start Date", "CourseStartDate", ($Project->CourseStartDate > 0 ? $Project->CourseStartDate : ""));
			echo CForm::AddDatepicker("Due Date", "DueDate", ($Project->DueDate > 0 ? $Project->DueDate : ""));
			echo CForm::AddTextbox("QOH", "QOH", $Project->QOH);
			echo CForm::AddDatepicker("QOH Date", "QOHDate", ($Project->QOHDate > 0 ? $Project->QOHDate : ""));
	
			$Types = new CProductTypes();
			$Types->OnLoadAll("WHERE `Active` = 1 ORDER BY `Name` ASC");
			$ValuesArray = Array();
			foreach(CForm::RowsToArray($Types->Rows, "Name") as $Key => $Value) {
				$ValuesArray[] = json_encode(Array("id" => $Key, "text" => $Value));
			}
			$SelectedArray = Array();
			$SelectedArray2 = Array();
			foreach(@$Project->ProductTypes as $Key => $Value) {
				$SelectedArray[] = json_encode(Array("id" => $Key, "text" => $Value));
				$SelectedArray2[] = $Key;
			}
			echo CForm::AddStatic("Product Type(s)", "<input type='text' name='".CForm::GetPrefix()."ProductTypes' id='".CForm::GetPrefix()."ProductTypes' value='' style='width:300px;'>"); 
			
			//*
			echo "
			<script type='text/javascript'>
				$('#".CForm::GetPrefix()."ProductTypes').select2({
					minimumResultsForSearch		: 20,
					multiple					: true,
					data						: [".str_replace('"id"', 'id', str_replace('"text"', 'text', implode(",", $ValuesArray)))."],
					createSearchChoice			: function(term, data) {
						if ($(data).filter(function() {
							return this.text.localeCompare(term) === 0;
						}).length === 0) {
							//return MProjects.AddProductType(term);
							return {id:term, text:term};
						}
					}
				});
				$('#".CForm::GetPrefix()."ProductTypes').select2('val', [";
				$Separator = "";
				foreach(@$Project->ProductTypes as $Key => $Value) {
					echo $Separator . "{id:".$Key.", text:\"".$Value."\"}";
					$Separator = ",";
				}
				echo "])
			</script>
			";
			//*/
			/*
			 *initSelection				: function (element) {
						var data = [];
						$(element.val().split('|')).each(function () {
							data.push({id: this, text: this});
						});
						return data;
					},*/
	
			echo CForm::AddTextbox("Stat Sponsor Code", "StatSponsorCode", $Project->StatSponsorCode);
			echo CForm::AddTextbox("2012 YTD Sales Net Units", "2012YTDSalesNetUnits", $Project->{"2012YTDSalesNetUnits"});
			echo CForm::AddTextbox("2012 YTD Sales Net Revenue", "2012YTDSalesNetRevenue", $Project->{"2012YTDSalesNetRevenue"});
			echo CForm::AddTextbox("2012 YTD Sales Gross Units", "2012YTDSalesGrossUnits", $Project->{"2012YTDSalesGrossUnits"});
			echo CForm::AddTextbox("2012 YTD Sales Gross Revenue", "2012YTDSalesGrossRevenue", $Project->{"2012YTDSalesGrossRevenue"});
			echo CForm::AddTextbox("2011 Sales Net Units", "2011SalesNetUnits", $Project->{"2011SalesNetUnits"});
			echo CForm::AddTextbox("2011 Sales Net Revenue", "2011SalesNetRevenue", $Project->{"2011SalesNetRevenue"});
			echo CForm::AddTextbox("2011 Sales Gross Units", "2011SalesGrossUnits", $Project->{"2011SalesGrossUnits"});
			echo CForm::AddTextbox("2011 Sales Gross Revenue", "2011SalesGrossRevenue", $Project->{"2011SalesGrossRevenue"});
			echo CForm::AddTextarea("Lead Notes", "LeadNotes", $Project->LeadNotes);
			echo CForm::AddTextbox("Request Plant", "RequestPlant", $Project->RequestPlant);
			echo CForm::AddTextbox("Plant Paid", "PlantPaid", $Project->PlantPaid);
			echo CForm::AddTextbox("Plant Left", "PlantLeft", $Project->PlantLeft);
			echo CForm::AddTextbox("Vender Used", "VenderUsed", $Project->VenderUsed);
			echo CForm::AddDatepicker("Date Paid", "DatePaid", ($Project->DatePaid > 0 ? $Project->DatePaid : ""));			
			echo CForm::AddTextbox("ISBN-10", "ISBN10", $Project->ISBN10);
			echo CForm::AddTextbox("ISBN-13", "ISBN13", $Project->ISBN13);
			echo CForm::AddTextbox("Custom ISBN", "CustomISBN", $Project->CustomISBN);
		?>
		</table>

	</td>
</tr>
<tr>
	<td colspan="3" align="right">
	
	<input type="hidden" value="<?=intval($Project->ID);?>" name="ID" id="ID"/>
	<div class="Button" value="Save" onClick="MProjects.Save('<?=CForm::GetPrefix();?>');">save</div>
	<div class='Button' value='Cancel' onClick="CModule.Load('Projects');">cancel</div>
	
	</td>
</tr>
</table>
