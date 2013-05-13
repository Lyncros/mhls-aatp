<?
	//==========================================================================
	/*
		Class Auto-Loader

		5/6/2010 8:40 AM
	*/
	//==========================================================================
	ini_set("display_errors", "On");
	error_reporting(E_ALL);

	//set_include_path(".:Classes");
	
	function __autoload($Classname) {
		//die($Classname);
		include("Classes/".$Classname.".php");
	}

	//==========================================================================
?>
