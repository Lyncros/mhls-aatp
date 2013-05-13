<?
	//==========================================================================
	/*
		Main entrance point, immediately runs CApp

		4/10/2009
	*/
	//==========================================================================
	
	require_once("Config.php");
	require_once("Auto.php");

	// Redirect HTTPS
	if(CURL::GetDomain() == "therapyclipboard.com") {
		Header("Location: ".CURL::FormatURL("http://www.therapyclipboard.com", Array(), true, true));
		die();
	}

	if(CURL::GetSubDomain() == "ajax") {
		require("AJAX.php");
	}else{
		$theApp = new CApp();
		$theApp->OnExecute();
	}

	//==========================================================================
?>
