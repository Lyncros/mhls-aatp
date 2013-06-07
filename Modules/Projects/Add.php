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
			echo CForm::AddTextbox("Project Number", "ProductNumber", $Project->ProductNumber);
			
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
			
			echo CForm::AddTextbox("Primary Customer", "PrimaryCustomer", $Project->PrimaryCustomer);
			echo CForm::AddTextbox("School", "School", $Project->School);
			echo CForm::AddDropdown("Status", "Status", CProjects::GetAllStatus(), $Project->Status);			
			
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
