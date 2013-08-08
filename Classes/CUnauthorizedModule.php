<?php	
	class CUnauthorizedModule extends CTemplateModule {
		//-------------------//
		// CModule Overrides //
		//-------------------//
		
		function OnAJAX($Action) {			
			return Array(1, "");
		}
		
		function OnRender() {
			$Action = $this->GetActionName();
			
			$templateName = $this->GetTemplateName($Action);
			$params = $this->GetTemplateParams($Action);
			//die(var_dump($this->Twig));
			$template = $this->Twig->loadTemplate($templateName.".twig");
			$template->display($params);
		}
		
		private function GetActionName()
		{
			$ActionName = $_GET["Page"];
			return ($ActionName == null)?"Index":$ActionName;
		}		
	}
?>