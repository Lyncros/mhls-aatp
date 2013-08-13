<?php	
	class CUnauthorizedModule extends CTemplateModule {
		//-------------------//
		// CModule Overrides //
		//-------------------//
		
		function OnRender() {
			$Action = $this->GetActionName();
			
			$template = $this->LoadTemplate($Action);
			
			$template->display($this->GetTemplateParams($Action));
		}
		
		private function GetActionName()
		{
			$ActionName = $_GET["Page"];
			
			return ($ActionName == null)?"Index":$ActionName;
		}		
	}
?>