<?php

/*
 * Class for use Twig template engine.
 */

	class CTemplateModule extends CModuleGeneric {
		
		function __construct($ViewsFolder) {
			parent::__construct();
			
			require_once './Libraries/Twig/Autoloader.php';
			Twig_Autoloader::register();
			$loader = new Twig_Loader_Filesystem($ViewsFolder);
			$twig = new Twig_Environment($loader, array(
			    'cache' => './Libraries/Twig/cache',
			));
		}
		
		function OnRenderContent() {
			echo "lLALALLALA";
		}
	}
?>