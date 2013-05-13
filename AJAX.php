<?
	//==========================================================================
	/*
		Controller for AJAX calls. Loads requested class, calls that class, and
		then returns Data.

		PaymentGateway AJAX functions can only be used Administrators

		5/6/2010 9:08 AM
	*/
	//==========================================================================
	require_once("Config.php");
	require_once("Auto.php");

	//==========================================================================
	$theApp = new CApp();

	//==========================================================================
	$ReturnCode		= 1; //0 = Bad, 1 = Good, < 0 = No Return Code Sent
	$ReturnText		= "NULL";

	$Request		= CData::SanitizeFilename($_POST["AJAX_Request"]);
	$RequestType	= $_POST["AJAX_Type"];
	$RequestAction	= $_POST["AJAX_Action"];

	if(!isset($_POST["AJAX_Request"]))	$Request		= CData::SanitizeFilename($_GET["AJAX_Request"]);
	if(!isset($_POST["AJAX_Type"]))		$RequestType	= $_GET["AJAX_Type"];
	if(!isset($_POST["AJAX_Action"]))	$RequestAction	= $_GET["AJAX_Action"];

	//==========================================================================
	list($ReturnCode, $ReturnText) = CAJAX::Call($Request, $RequestType, $RequestAction, $_POST);

	//==========================================================================
	if($ReturnCode >= 0) {
		echo $ReturnCode."\n";
	}

	echo $ReturnText;

	//==========================================================================
?>
