<?
	include_once("./Libraries/Twig/Error.php");
	include_once("./Libraries/Twig/NodeOutputInterface.php");
	include_once("./Libraries/Twig/NodeInterface.php");
	include_once("./Libraries/Twig/Node.php");
	include_once("./Libraries/Twig/Node/Print.php");
	include_once("./Libraries/Twig/Node/Module.php");

	function Twig_Include_Recursive($Folder) {
		global $Level;

		$Files = scandir($Folder, 1);

		array_pop($Files);
		array_pop($Files);

		foreach($Files as $File) {
			if(is_dir($Folder.$File)) {
				Twig_Include_Recursive($Folder.$File."/");
				continue;
			}

			if(stripos($File, ".php") !== false) {
				include_once($Folder.$File);
			}
		}
	}

	Twig_Include_Recursive("./Libraries/Twig/");
?>
