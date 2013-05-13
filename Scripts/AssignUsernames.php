<?
	die();

	chdir("../");

	include("Config.php");
	include("Auto.php");

	new CApp();

	echo "<pre>";

//	$Users = new CUsers();
//	$Users->OnLoad(308);

//	echo CEncrypt::Decrypt($Users->Password);

	$Users = new CUsers();
	$Users->OnLoadAll("WHERE `Active` = 1 && `ID` > 1");

	foreach($Users->Rows as $Row) {
		echo "\"".$Row->FirstName."\",";
		echo "\"".$Row->LastName."\",";
		echo "\"".$Row->Username."\",";
		echo "\"".CEncrypt::Decrypt($Row->Password)."\"\n";
	}

	die();

	echo "<b>Users.csv</b><br/><br/>";
	$Handle = fopen("./Temp/Users.csv", "r");

	while(($Data = fgetcsv($Handle)) !== false) {
		$FirstName  = $Data[0];
		$LastName	= $Data[1];
		$Username	= $Data[2];
		$Password	= $Data[3];

		$Data = Array(
			"Username" => $Username,
			"Password" => CEncrypt::Encrypt($Password)
		);

		$User = new CUsers();
		if($User->OnLoadAll("WHERE `FirstName` LIKE '".mysql_real_escape_string($FirstName)."' && `LastName` LIKE '".mysql_real_escape_string($LastName)."'") !== false) {
			CTable::Update("Users", $User->ID, $Data);
		}else{
			echo "Cannot find User: $FirstName $LastName (".implode(" ", $Name).")<br/>";
		}
	}

	fclose($Handle);

	die();

	//=========================================================================
	$Users = new CUsers();
	$Users->OnLoadAll("WHERE `ID` > 1");

	foreach($Users->Rows as $Row) {
		$SSN = substr(microtime(true) * rand(), 0, 4);

		$Username = substr($Row->LastName, 0, 4).substr($SSN, -4, 4);

		$Data = Array(
			"Username" => strtolower($Username),
			"Password" => CEncrypt::Encrypt(substr(md5(microtime(true)), 0, 8))
		);

		CTable::Update("Users", $Row->ID, $Data);
	}

	//=========================================================================
	echo "<b>koicontractvendors.csv</b><br/><br/>";
	$Handle = fopen("./Temp/koicontractvendors.csv", "r");

	while(($Data = fgetcsv($Handle)) !== false) {
		$Name		= explode(" ", $Data[4]);
		$SSN		= $Data[6];
		
		if(strlen($SSN) <= 3) $SSN = substr(microtime(true) * rand(), 0, 4);

		$FirstName	= trim($Name[0]);
		$LastName	= trim($Name[count($Name) - 1]);

		$Username	= substr($LastName, 0, 4).substr($SSN, -4, 4);

		$Data = Array(
			"Username" => strtolower($Username)
		);

		$User = new CUsers();
		if($User->OnLoadAll("WHERE `FirstName` LIKE '".mysql_real_escape_string($FirstName)."' && `LastName` LIKE '".mysql_real_escape_string($LastName)."'") !== false) {
			CTable::Update("Users", $User->ID, $Data);
		}else{
			echo "Cannot find User: $FirstName $LastName (".implode(" ", $Name).")<br/>";
		}
	}

	fclose($Handle);

	//=========================================================================
	echo "<hr/>";
	echo "<b>koiemployeelist.csv</b><br/><br/>";
	$Handle = fopen("./Temp/koiemployeelist.csv", "r");

	while(($Data = fgetcsv($Handle)) !== false) {
		$Name		= explode(",", $Data[2]);
		$Name		= implode(" ", $Name);
		$Name		= explode(" ", $Name);
		$SSN		= $Data[4];
		
		if(strlen($SSN) <= 3) $SSN = substr(microtime(true) * rand(), 0, 4);

		$FirstName	= trim($Name[2]);
		$LastName	= trim($Name[0]);

		$Username	= substr($LastName, 0, 4).substr($SSN, -4, 4);

		$Data = Array(
			"Username" => strtolower($Username)
		);

		$User = new CUsers();
		if($User->OnLoadAll("WHERE `FirstName` LIKE '".mysql_real_escape_string($FirstName)."' && `LastName` LIKE '".mysql_real_escape_string($LastName)."'") !== false) {
			CTable::Update("Users", $User->ID, $Data);
		}else{
			echo "Cannot find User: $FirstName $LastName (".implode(" ", $Name).")<br/>";
		}
	}

	fclose($Handle);

	//=========================================================================
	echo "<hr/>";
	echo "<b>PNEcontractlist.csv</b><br/><br/>";
	$Handle = fopen("./Temp/PNEcontractlist.csv", "r");

	while(($Data = fgetcsv($Handle)) !== false) {
		$Name		= explode(" ", $Data[4]);
		$SSN		= $Data[6];
		
		if(strlen($SSN) <= 3) $SSN = substr(microtime(true) * rand(), 0, 4);

		$FirstName	= trim($Name[0]);
		$LastName	= trim($Name[count($Name) - 1]);

		$Username	= substr($LastName, 0, 4).substr($SSN, -4, 4);

		$Data = Array(
			"Username" => strtolower($Username)
		);

		$User = new CUsers();
		if($User->OnLoadAll("WHERE `FirstName` LIKE '".mysql_real_escape_string($FirstName)."' && `LastName` LIKE '".mysql_real_escape_string($LastName)."'") !== false) {
			CTable::Update("Users", $User->ID, $Data);
		}else{
			echo "Cannot find User: $FirstName $LastName (".implode(" ", $Name).")<br/>";
		}
	}

	fclose($Handle);

	//=========================================================================
	echo "<hr/>";
	echo "<b>PNEemployeelist.csv</b><br/><br/>";
	$Handle = fopen("./Temp/PNEemployeelist.csv", "r");

	while(($Data = fgetcsv($Handle)) !== false) {
		$Name		= explode(",", $Data[2]);
		$Name		= implode(" ", $Name);
		$Name		= explode(" ", $Name);
		$SSN		= $Data[4];
		
		if(strlen($SSN) <= 3) $SSN = substr(microtime(true) * rand(), 0, 4);

		$FirstName	= trim($Name[2]);
		$LastName	= trim($Name[0]);

		$Username	= substr($LastName, 0, 4).substr($SSN, -4, 4);

		$Data = Array(
			"Username" => strtolower($Username)
		);

		$User = new CUsers();
		if($User->OnLoadAll("WHERE `FirstName` LIKE '".mysql_real_escape_string($FirstName)."' && `LastName` LIKE '".mysql_real_escape_string($LastName)."'") !== false) {
			CTable::Update("Users", $User->ID, $Data);
		}else{
			echo "Cannot find User: $FirstName $LastName (".implode(" ", $Name).")<br/>";
		}
	}

	fclose($Handle);

	//=========================================================================
	echo "<hr/>";
	echo "<b>clipboardusers.csv</b><br/><br/>";

	$Handle = fopen("./Temp/clipboardusers.csv", "r");

	while(($Data = fgetcsv($Handle)) !== false) {
		$Name		= explode(" ", $Data[0]);
		$Username	= $Data[1];
		$Access		= $Data[2];

		$Data = Array(
			"UsersGroupsID" => 1,
			"Username"		=> strtolower($Username)
		);

		if($Access == "Master") {
			$Data["UsersGroupsID"]	= 0;
			$Data["Type"]			= "Admin";
			$Data["SuperAdmin"]		= 1;
		}

		$User = new CUsers();
		if($User->OnLoadAll("WHERE `FirstName` LIKE '".mysql_real_escape_string($Name[0])."' && `LastName` LIKE '".mysql_real_escape_string($Name[1])."'") !== false) {
			CTable::Update("Users", $User->ID, $Data);
		}else{
			echo "Cannot find User: ".implode(" ", $Name)."<br/>";
		}
	}

	fclose($Handle);

	//=========================================================================
?>
