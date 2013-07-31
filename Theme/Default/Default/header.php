<?php // -*- php -*-
    $App = $this->Parent->Parent;
	
    $FileControlTheme	= $this;
    $ThemePath			= $FileControlTheme->Path;

    if(!CSecurity::$User) {
        Header("Location: /");
        die();
    }
    
    $ProjectsSidebarModules = CSidebarMenu::BuildProjectsSideMenu();
	
	$SuperToolsActive  = "";
	$SuperToolsSidebarModules = Array(
		//"Institutions" 				=> "Institutions",
		"ProductSolutions" 			=> "Product Solutions",
		"Milestones"				=> "Milestones",
		"ToDos"						=> "To Dos",
		"ToDosLists"				=> "To Do Lists",
		//"ResourcesCategories"		=> "Resource Categories",
		"ProductTypes"				=> "Product Types",
		"Users" 					=> "Users",
		"Vendors"					=> "Vendors",
		//"UsersGroups"
	);

	/**
	 * If current page is equals to the one in GET, page is active.
	 * If no page is in GET, check if the page is module's default and module is the current to display. 
	 */
	function GetActiveInactiveStyle($PageName, $ModuleName = null, $App = null) {
		if (isset($_GET["Page"])) {
			return $_GET["Page"] == $PageName ? "SidebarActive" : "";
		} else {
			return ($PageName == $ModuleName && $App->GetModuleName() == $ModuleName) ? "SidebarActive": "";
		}
	}
	
	/**
	 * Class name for sidebar should be
	 * - Module name, if the page is the default.
	 * - Module name + Page name otherwise
	 */
	function GetSidebarClass($PageName, $ModuleName) {
		$ClassPrefix = "SidebarIcon";
		return $ClassPrefix.($PageName == $ModuleName ? $PageName : $ModuleName.$PageName);
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
			<a href="/ProductSolutions" id='SuperTools' rel='<?= array_key_exists($App->GetModuleName(), $SuperToolsSidebarModules) ? "Active" : "" ?>'></a>
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
                if(array_key_exists($App->GetModuleName(), $SuperToolsSidebarModules)) {
                    foreach($SuperToolsSidebarModules as $Name => $ReadableName) {
                          echo "<li class='".($App->GetModuleName() == $Name ? "SidebarActive" : "")."'>
                               <div style='text-align:center;line-height: 13px;padding-top: 5px;'>$ReadableName</div>
                               <div class='SuperToolsSidebarIcon SidebarIcon".$Name."' onClick=\"CModule.Load('".$Name."', {});\" title=\"$ReadableName\"></div></li>";
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
						echo "<li class='".GetActiveInactiveStyle($PageName)."'><div class='SidebarIcon SidebarIcon".$Name."' title=\"$Name\" onClick=\"CModule.Load('".$App->GetModuleName()."', {'Page' : '".$PageName."'});\"></div></li>";	// <a href='/".$ModuleName."' style='".($App->GetModuleName() == $ModuleName ? "color: white;" : "")."'>".$Name."</a>
						echo "<li style='padding-left:2px; height:2px;'><div class='SidebarSeparator'></div></li>";
                    }    
                } else if (array_key_exists($App->GetModuleName(), $ProjectsSidebarModules)) {
                    
                    foreach ($ProjectsSidebarModules as $moduleName => $menus) {
						foreach($menus as $menu) { /* @var $menu CSidebarMenu */
							
                            if ($menu->canView(CSecurity::$User)) {
                                echo "<li class='".GetActiveInactiveStyle($menu->name, $moduleName, $App)."'>
                                        <div class='SidebarIcon ".GetSidebarClass($menu->name, $moduleName)."' 
                                            onClick=\"CModule.Load('".$moduleName."', {'Page' : '".$menu->name."'});\" 
                                            title=\"$menu->displayName\"></div>";
                                
                                if ($menu->hasChildrens()) {
                                    foreach ($menu->submenus as $submenu) { /* @var $submenu CSidebarSubmenu */
                                        echo "<div class='SidebarSubicon SidebarSubicon".$submenu->name."'
                                                title='".$submenu->displayName."' 
                                                onClick='".$submenu->jsAction."'></div>";
                                    }
                                }
                                
                                echo "</li>";
                                echo "<li style='padding-left:2px; height:2px;'><div class='SidebarSeparator'></div></li>";					
                            }
						}
					}
				} else {
					echo "<li class='SidebarActive'>
					<div class='SidebarIcon SidebarIcon".$App->GetModuleName()."' onClick=\"CModule.Load('".$App->GetModuleName()."', {});\"></div>
					</li>";				
				}
				
			?>
		</ul>
		<div class="Content">
			<!--<div class="TopBar"><div id="CPageNotice"></div><div id='CRefresh_Suggest' class='CRefresh_Suggest'></div></div>-->

