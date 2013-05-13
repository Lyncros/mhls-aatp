<? 
	chdir("../");

	include("Config.php");
	include("Auto.php");

	new CApp();

	$User = new CUsers();
	$User->OnLoad(2);
	echo $User->Username."<br/>";
	echo $User->GetPassword();
	echo "<br/><br/>";
?>
