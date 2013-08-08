<?php

	class MProjectCreatorHome extends CUnauthorizedModule {
		
		function __construct() {
			parent::__construct("./Modules/ProjectCreatorHome/Views");			
		}
	
		function IndexParams() {
			$data = array();
			$data['activeSidebarNode'] = 'PrivateOffers';
			$data['menuItems'] = CSidebarMenu::BuildProjectsFormsSideMenu();
			
			return $data;
		}		
	};
?>
