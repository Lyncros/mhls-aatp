<?php // -*- php -*-
	$App = $this->Parent->Parent;

	$FileControlTheme	= $this;
	$ThemePath			= $FileControlTheme->Path;

	if(!CSecurity::$User) {
		Header("Location: /");
		die();
	}
	
	$SuperToolsActive  = "";
	$SuperToolsModules = Array(
		"Institutions" 				=> "Institutions",
		"ProductSolutions" 			=> "Product Solutions",
		"Milestones"				=> "Milestones",
		"ToDos"						=> "To Dos",
		"ToDosLists"				=> "To Do Lists",
		"ResourcesCategories"		=> "Resource Categories",
		"ProductTypes"				=> "Product Types",
		"Users" 					=> "Users",
//		"UsersGroups"
	);
	
	if(array_key_exists($App->GetModuleName(), $SuperToolsModules)) {
		$SuperToolsActive = "Active";
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>The Almighty App For All Things Project</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<script type="text/javascript" src="./js/Include.php"></script>
	<?
		$App->OnRenderJS();
	?>
	<?
		$FileControlTheme->LoadFile("style.css", CFILE_TYPE_CSS);
		$FileControlTheme->LoadFile("jqueryui/ui.all.css", CFILE_TYPE_CSS);
		$FileControlTheme->LoadFile("Icons.css", CFILE_TYPE_CSS);
		$FileControlTheme->LoadFile("CBannedIPs.css", CFILE_TYPE_CSS);
		$FileControlTheme->LoadFile("CBox.css", CFILE_TYPE_CSS);
		$FileControlTheme->LoadFile("Select2.css", CFILE_TYPE_CSS);
		$FileControlTheme->LoadFile("CForm.css", CFILE_TYPE_CSS);
		$FileControlTheme->LoadFile("CMenu.css", CFILE_TYPE_CSS);
		$FileControlTheme->LoadFile("CPageNotice.css", CFILE_TYPE_CSS);
		$FileControlTheme->LoadFile("CSearch.css", CFILE_TYPE_CSS);
		$FileControlTheme->LoadFile("CTabs.css", CFILE_TYPE_CSS);
		$FileControlTheme->LoadFile("CTooltip.css", CFILE_TYPE_CSS);
		$FileControlTheme->LoadFile("CTray.css", CFILE_TYPE_CSS);
		$FileControlTheme->LoadFile("CWindow.css", CFILE_TYPE_CSS);
		
	
		$App->OnRenderCSS();
	?>
	<link rel="stylesheet" type="text/css" media="print" href="/Theme/Default/Default/style-print.css" />
	<?
	if($App->GetModuleName() == "Projects") {
	?>
	<style type='text/css'>
		.ProjectDetails .ProjectContainer table tbody tr td {
			border:					none;
		}
		.CSearch_Pages {
			background:				#ffffff !important;
			font-family:			myriad-pro;
		}
	</style>
	<?
	}
	?>
</head>
<body>
	
<?=($_SERVER["REMOTE_ADDR"] == "75.145.183.249" ? "<div id='Debugging' style='position:absolute; top:108px; left:20px; color:red; background:white; border:1px solid black; width:405px; padding:10px; line-height:20px; text-align:left;'>DEBUGGING:</div>" : "");?>

<script type="text/javascript" src="http://use.typekit.com/srq1ehx.js"></script>
<script type="text/javascript">try{Typekit.load();}catch(e){}</script>

<div id="CPageNotice"></div>
<div id="CLoading">Loading</div>


<div class="HeaderWrapper">
	<div class="Header">
		<div class="HeaderLogo" onClick="CModule.Load('Dashboard');"></div>
		<div class="HeaderWelcome">			
			<?
			
				$NumAlerts = CAlerts::GetNumUnreadAlerts(CSecurity::GetUsersID());
				$NumAlerts = 2;
			/*	
				$LoginAttempts = new CLoginAttempts();
				if($LoginAttempts->OnLoadAll("WHERE `UsersID` = ".intval(CSecurity::GetUsersID())." ORDER BY `Timestamp` DESC LIMIT 1, 1") === false) {
					$LastLogin = "<i>Never</i>";
				}else{
					$LastLogin = date("F j, Y", $LoginAttempts->Timestamp);
				}
			<span class="HeaderWelcomeName">Hello, <?=CSecurity::$User->FirstName;?> <? if($NumAlerts > 0) { ?><div class="HeaderWelcomeAlerts" onClick="CModule.Load('Alerts');"><?=($NumAlerts);?></div><? } ?></span><br/>
			<span class="HeaderWelcomeTime">Last Login, <?=$LastLogin;?></span>
			*/
			?>
		</div>
		<div class="HeaderNav">
			<a href="/MyAccount" id='MyAccount'></a>
			<!--<a href="/Messages" id='Messages'><div id='UnreadMessageCount'><?=$NumAlerts;?></div></a>-->
			<a href="/Institutions" id='SuperTools' rel="<?=$SuperToolsActive;?>"></a>
		</div>
	</div>
</div>
<div class="BodyWrapper">
	<?
		$Modules = Array(
			"Projects"		=> "Project Management",
		);
		// 2013.05.25 Issue: remove the Audit Bill Management tab from the superadmin role (http://projects.lyncros.com/redmine/issues/118)
		//if(CSecurity::GetUsersGroupsID() == 1) {			
		//	$Modules["AuditBills"] = "Audit Bill Management";
		//}
		
		foreach($Modules as $ModuleName => $Name) {
			if(CSecurity::CanAccess($ModuleName)) {
				echo "<div class='TopTab ".($App->GetModuleName() == $ModuleName ? "TopTabActive" : "")."' onClick=\"CModule.Load('".$ModuleName."');\">".$Name."</div>";
			}
		}
	?>
	<div class="Body">
		<ul class="Sidebar">
			<?
				if(array_key_exists($App->GetModuleName(), $SuperToolsModules)) {
					foreach($SuperToolsModules as $Name => $ReadableName) {
						echo "<li class='".($App->GetModuleName() == $Name ? "SidebarActive" : "")."'><div class='SidebarIcon SidebarIcon".$Name."' onClick=\"CModule.Load('".$Name."', {});\" title=\"$ReadableName\"></div></li>";
						echo "<li style='padding-left:2px; height:2px;'><div class='SidebarSeparator'></div></li>";					
					}
				}else
				if($App->GetModuleName() == "AuditBills") {
					$Pages = Array(
						""						=> "Dashboard",
						"New"					=> "Step1",
						"Step2"					=> "Step2",
						"Step3"					=> "Step3",
						"Step4"					=> "Step4",
					);
	
					foreach($Pages as $PageName => $Name) {
						echo "<li class='".($_GET["Page"] == $PageName ? "SidebarActive" : "")."'><div class='SidebarIcon SidebarIcon".$Name."' title=\"$Name\" onClick=\"CModule.Load('".$App->GetModuleName()."', {'Page' : '".$PageName."'});\"></div></li>";	// <a href='/".$ModuleName."' style='".($App->GetModuleName() == $ModuleName ? "color: white;" : "")."'>".$Name."</a>
						echo "<li style='padding-left:2px; height:2px;'><div class='SidebarSeparator'></div></li>";
					}
				}else
				if($App->GetModuleName() == "Projects") {
					$Pages = Array(
						""						=> "Projects",
					);
					if(CSecurity::$User->CanAccess("ProjectDetails", "Add")) {
						//echo "<div class='ButtonAdd' onClick=\"CModule.Load('Projects', {'Page' : 'Add'});\" style='position:absolute; top:0px; right:3500px;'>Add</div>";
						$Pages["Add"] = "AddProject";
					}
	
					foreach($Pages as $PageName => $Name) {
						if($PageName == "") {
							echo "<li class='".($_GET["Page"] == $PageName ? "SidebarActive" : "")."' ".($_GET["ID"] ? "style='height:190px;'" : "")."><div ". ($_GET["Page"] == $PageName ? "id='ProjectListSideBarIcon'" : ""). " class='SidebarIcon SidebarIcon".$Name."' title=\"$Name\" onClick=\"".($_GET["Page"] == $PageName ? "MProjects.MoveToList();" : "CModule.Load('".$App->GetModuleName()."');")."\"></div>";
							//if($PageName == "" && $_GET["ID"]) {
								echo "<br><br>";
								echo "<div class='SidebarSubicon SidebarSubiconProjectDetails' title='Project Details' onClick=\"MProjects.MoveToDetails();\"></div>";
								echo "<div class='SidebarSubicon SidebarSubiconMilestones' title='Milestones' onClick=\"MProjects.MoveToMilestones();\"></div>";
								echo "<div class='SidebarSubicon SidebarSubiconMessageBoard' title='Message Board' onClick=\"MProjects.MoveToMessages();\"></div>";
								echo "<div class='SidebarSubicon SidebarSubiconNotifications' title='Notifications' onClick=\"MProjects.MoveToNotifications();\"></div>";
							//}
							echo "</li>";	// <a href='/".$ModuleName."' style='".($App->GetModuleName() == $ModuleName ? "color: white;" : "")."'>".$Name."</a>
							echo "<li style='padding-left:2px; height:2px;'><div class='SidebarSeparator'></div></li>";
						} else {
							echo "<li class='".($_GET["Page"] == $PageName ? "SidebarActive" : "")."'><div class='SidebarIcon SidebarIcon".$Name."' title=\"$Name\" onClick=\"CModule.Load('".$App->GetModuleName()."', {'Page' : '".$PageName."'});\"></div></li>";	// <a href='/".$ModuleName."' style='".($App->GetModuleName() == $ModuleName ? "color: white;" : "")."'>".$Name."</a>
							echo "<li style='padding-left:2px; height:2px;'><div class='SidebarSeparator'></div></li>";
						}
					}
				}else{
					echo "<li class='SidebarActive'><div class='SidebarIcon SidebarIcon".$App->GetModuleName()."' onClick=\"CModule.Load('".$App->GetModuleName()."', {});\"></div></li>";				
				}
				
			?>
		</ul>
		<div class="Content">
			<!--<div class="TopBar"><div id="CPageNotice"></div><div id='CRefresh_Suggest' class='CRefresh_Suggest'></div></div>-->

