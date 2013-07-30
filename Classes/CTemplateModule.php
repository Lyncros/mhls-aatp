<?php

	require_once './Libraries/Twig/Autoloader.php';
	
	class CTemplateModule extends CModuleGeneric {
		
		const DATE_FORMAT = "n/j/Y";
		
		public $ViewsFolder = "";
		public $JSFile 		= "";
		private $twig;

		function __construct() {
			parent::__construct();
						
			Twig_Autoloader::register();
			$loader = new Twig_Loader_Filesystem($this->ViewsFolder);
			$this->twig = new Twig_Environment($loader, array(
			    //FIXME: 'cache' => './Libraries/Twig/cache',
			));
		}
		
		/*
		 * Renders a template, passing its parameters.
		 * By default, the template name is the same as the page requested.
		 * And the params will be built by a function called like the page requested plus string Params.
		 */
		function OnRenderContent() {
			$page = $_GET["Page"];
			
			$templateName = $this->GetTemplateName($page);
			$params = $this->GetTemplateParams($page);
			
			$template = $this->twig->loadTemplate($templateName.".phtml");
			$template->display($params);
		}
		
		function GetTemplateName($page) {
			$templateName = $page;

			$getTemplateName = $page."Template";
			if (method_exists($this, $getTemplateName)) {
				$templateName = $this->{$getTemplateName}();
			}
			
			return $templateName;
		}
		
		function GetTemplateParams($page) {
			$params = array();

			$getParams = $page."Params";
			if (method_exists($this, $getParams)) {
				$params = $this->{$getParams}();
			}
			
			return $params;
		}
		
		//----------------------------------------------------------------------
		function OnRenderJS() { 
			if (!empty($this->JSFile));
				$this->FileControl->LoadFile($this->JSFile, CFILE_TYPE_JS);
		}
				
		//----------------------------------------------------------------------
		function OnRenderCSS() { 
			$this->FileControl->LoadFile("style.css", CFILE_TYPE_CSS);
		}
		
		//----------------------------------------------------------------------
		function OnRender() {
			$Page = $_GET["Page"];

			parent::OnRender();
		}
		
		//----------------------------------------------------------------------
		function OnExecute() {
			return parent::OnExecute();
		}

		//----------------------------------------------------------------------
		function OnAJAX($Action) {
			if(parent::CanAccess($Action) == false) {
				return Array(0, "You do not have permission to perform this action");
			}

			return parent::OnAJAX($Action);
		}
		
		function FormatDate($timestamp) {
			return date(self::DATE_FORMAT, $timestamp);
		}
	}
?>