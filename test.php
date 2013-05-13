<?
	if($_SERVER["REMOTE_ADDR"] !== "75.145.183.249") {
		header("Location: /");
		die();
	}
	
	require_once("Config.php");
	require_once("Auto.php");

	new CApp();

	ini_set("display_errors", "On");
	error_reporting(E_ALL);

	//CTable::RunQuery("UPDATE `Users` SET `Password` = '".CEncrypt::Encrypt("sleepy123")."' WHERE `ID` = 3");
	///*
	$User = new CUsers();
	$User->OnLoadAll("ORDER BY `ID` ASC");
	foreach($User->Rows as $User) {
		echo $User->ID;
		echo "&nbsp;&nbsp;:&nbsp;&nbsp;";
		echo $User->Username;
		echo "&nbsp;&nbsp;:&nbsp;&nbsp;";
		echo CEncrypt::Decrypt($User->Password);
		echo "<br/>";
	}
	
	
	//*/
?>
