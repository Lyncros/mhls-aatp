<?
	//$Project = $this->Parent->TableObject;
	
	$ProjectID = intval($_POST["ProjectID"]);
	
	$Prefix = "NewMessageForm".time();
	CForm::SetPrefix($Prefix);
?>
	
	
	<div class='Button' value='AddResource' onClick="$('#AddResource').slideDown();" style='float:left; width:100px; position:relative; margin-left:0px; padding:0px 10px; margin-top:20px; margin-bottom:-38px; z-index:1000;'>Add New Resource</div>
	<div class='AddResource' id='AddResource' style='z-index:1001;'>
		<div class='ProjectWrapper'>
			<div class='ProjectContainer'>
				<center>
					<table style='width:95%;'>
						<?
							echo CForm::AddRow("<div class='CloseAddResource' id='CloseAddResource' onClick=\"$('#AddResource').slideUp();\"></div>");
							
							$Cats = new CResourcesCategories();
							$Cats->OnLoadAll("WHERE `Active` = 1 ORDER BY `Name` ASC");
							echo CForm::AddListbox("Categories", "Categories", CForm::RowsToArray($Cats->Rows, "Name"), Array(), "Please select at least one Category");
						
							echo CForm::AddTextbox("Title", "Title", "");
							echo CForm::AddUpload("Filename", "Filename");
							echo CForm::AddHidden("ProjectsID", $ProjectID);
						?>
						<tr>
							<td colspan='2'><div style='position:relative; height:20px;'><input type='button' class='CWindow_Save' style='bottom:2px;' onClick="if(MProjects.AddFile('<?=$Prefix;?>')) $('#AddResource').slideUp();" value='Save'/></div></td>
						</tr>
					</table>
				</center>
			</div>
		</div>
	</div>

<?

	function OnFilename($Value) {
		return "<a href='http://".$_SERVER["HTTP_HOST"]."/Projects?ResourcesID=".$Value."' target='_blank'>View/Download</a>";
	}
	
	function OnCreated($Value) {
		return date('n/j/Y g:ia', $Value);
	}
	
	function OnUser($Value) {
		$User = new CUsers();
		$User->OnLoad($Value);
		return $User->LastName . ", " . $User->FirstName;
	}

	$Search = new CSearch("ProjectsResources");
	
	//$Search->AddColumn("ID", "ID", "0px;display:none", CSEARCHCOLUMN_SEARCHTYPE_NOSEARCH, "", "", "", "OnRow");
	$Search->AddColumn("Title", "Title", "50%;text-align:left", CSEARCHCOLUMN_SEARCHTYPE_NOSEARCH);
	$Search->AddColumn("View/Download", "ID", "15%;text-align:left", CSEARCHCOLUMN_SEARCHTYPE_NOSEARCH, "", "", "", "OnFilename");
	$Search->AddColumn("Uploaded", "Created", "20%;text-align:left", CSEARCHCOLUMN_SEARCHTYPE_NOSEARCH, "", "", "", "OnCreated");
	$Search->AddColumn("By", "CreatedUsersID", "15%;text-align:left", CSEARCHCOLUMN_SEARCHTYPE_NOSEARCH, "", "", "", "OnUser");
	
	$Search->AddRestriction("ProjectsID", $ProjectID);
	
	$Search->SetDefaultColumn(2, 1);
	
	$Search->OnInit();
	
	$Search->OnRender();
	$Search->OnRenderPages();
	
	//var_dump($_SERVER);
	
	//echo "<pre>";
	//var_dump($_SERVER);
	//echo "</pre>";
	
	
?>
