<?
	//==========================================================================
	/*
		Class Auto-Loader

		5/6/2010 8:40 AM
	*/
	//==========================================================================
	ini_set("display_errors", "On");
	error_reporting(E_ALL);

	spl_autoload_register('aatp_autoload');
	
	function aatp_autoload($Classname) {		
		include("Classes/".$Classname.".php");
	}

	//==========================================================================
?>
