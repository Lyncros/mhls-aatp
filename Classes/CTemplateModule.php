<?php

/*
 * Class for use Twig template engine.
 */
	require_once './Libraries/Twig/Autoloader.php';
	
	class CTemplateModule extends CModuleGeneric {
		
		public $ViewsFolder = "";
		
		function __construct() {
			parent::__construct();
						
			Twig_Autoloader::register();
			$loader = new Twig_Loader_Filesystem($this->ViewsFolder);
			$twig = new Twig_Environment($loader, array(
			    'cache' => './Libraries/Twig/cache',
			));
		}
		
		function OnRenderContent() {
			echo "lLALALLALA";
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

	}
?>