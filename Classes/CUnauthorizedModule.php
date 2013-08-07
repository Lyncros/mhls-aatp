<?php
	
	class CUnauthorizedModule extends CTemplateModule {
		//-------------------//
		// CModule Overrides //
		//-------------------//
		
		function OnAJAX($Action) {			
			return Array(1, "");
		}
				
		function OnRender() {
			$page = $_GET["Page"];
			
			$templateName = $this->GetTemplateName($page);
			$params = $this->GetTemplateParams($page);
			
			$template = $this->Twig->loadTemplate($templateName.".phtml");
			$template->display($params);
		}
	}
?>