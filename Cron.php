<?
	//==========================================================================
	/*
		Daily Cron

		Cron job is setup on jhspecialty.com domain on Plesk

		8/17/2011 7:16 AM
	*/
	//==========================================================================
	
	require_once("Config.php");	
	require_once("Auto.php");

	new CApp();
	
	echo "<pre>";
	
	CCron::OnRun();

	//==========================================================================
	if(
		!file_exists("./ExportData/_JH_CPT.xml") || 
		!file_exists("./ExportData/_JH_Divisions.xml") || 
		!file_exists("./ExportData/_JH_providers.xml") || 
		!file_exists("./ExportData/_JH_Clients.xml") || 
		!file_exists("./ExportData/_JH_ICD9.xml") || 
		!file_exists("./ExportData/_JH_Join.xml")
	) die("No Data to Parse");

	//==========================================================================
	// Purge (for Testing)
	//==========================================================================
	//mysql_query("TRUNCATE `CPTCodes`");
	//mysql_query("TRUNCATE `ICD9Codes`");

	//mysql_query("TRUNCATE `Authorizations`");
	//mysql_query("TRUNCATE `Patients`");
	//mysql_query("TRUNCATE `Providers`");
	//mysql_query("TRUNCATE `ProvidersBenefits`");
	//mysql_query("TRUNCATE `ProvidersPayTypes`");

	//mysql_query("DELETE FROM `Users` WHERE `ID` > 1");

	//==========================================================================
	// CPT Codes
	//==========================================================================
	CTable::RunQuery("UPDATE `CPTCodes` SET `Active` = 0");

	$Data = simplexml_load_file("./ExportData/_JH_CPT.xml");

	foreach($Data->_JH_CPT as $Item) {
		$Code = (string)($Item->cCPTCode);


		if($Code == "") continue;
		$Data = Array(
			"Code"			=> $Code,
			"Description"	=> (string)($Item->cDescription),
			"Active"		=> 1
		);

		$Temp = new CTable("CPTCodes");
		if($Temp->OnLoadAll("WHERE `Code` = '".mysql_real_escape_string($Code)."'") !== false) {
			CTable::Update("CPTCodes", $Temp->ID, $Data);
		}else{
			CTable::Add("CPTCodes", $Data);
		}
	}

	//==========================================================================
	// ICD9 Codes
	//==========================================================================
	CTable::RunQuery("UPDATE `ICD9Codes` SET `Active` = 0");

	$Data = simplexml_load_file("./ExportData/_JH_ICD9.xml");

	foreach($Data->_JH_ICD9 as $Item) {
		$Code = (string)($Item->dCode);

		if($Code == "") continue;

		$Data = Array(
			"Code"			=> $Code,
			"Description"	=> (string)($Item->dDescription),
			"Active"		=> 1
		);

		$Temp = new CTable("ICD9Codes");
		if($Temp->OnLoadAll("WHERE `Code` = '".mysql_real_escape_string($Code)."'") !== false) {
			CTable::Update("ICD9Codes", $Temp->ID, $Data);
		}else{
			CTable::Add("ICD9Codes", $Data);
		}
	}

	//==========================================================================
	// Providers
	//==========================================================================
	$Data = simplexml_load_file("./ExportData/_JH_providers.xml");

	foreach($Data->_JH_providers as $Item) {
		$ProvidersID	= $Item->pKey;
		$UsersID		= 0;

		$Active			= intval($Item->pActive);

		//----------------------------------------------------------------------
		// User Creation
		//----------------------------------------------------------------------
		$Temp = new CTable("Providers");
		$User = new CTable("Users");

		if($Temp->OnLoadByID($ProvidersID) === false || $User->OnLoadByID($Temp->UsersID) === false) {
			$Username = substr(strtolower((string)($Item->pLastName)), 0, 4).substr(md5(microtime(true)), 0, 4);
			$Password = substr(md5(microtime(true)), 0, 8);

			$CompanyID = (string)($Item->pCompany);

			if($CompanyID == 40) $CompanyID = 2; // KOI
			if($CompanyID > 2)	 $CompanyID = 1;

			$Data = Array(
				"CompaniesID"		=> $CompanyID,
				"ThirdPartyCompaniesID"	=> 0,
				"UsersGroupsID"		=> 1, // Providers
				"Timestamp"			=> time(),
				"TimestampUpdated"	=> time(),
				"TimestampImported"	=> time(),
				"Type"				=> "Provider",
				"SuperAdmin"		=> 0,

				"Username"			=> $Username,
				"Password"			=> CEncrypt::Encrypt($Password),

				"FirstName"			=> ucwords(strtolower((string)($Item->pFirstName))),
				"LastName"			=> ucwords(strtolower((string)($Item->pLastName))),
				"Address1"			=> ucwords(strtolower((string)($Item->pAddress1))),
				"Address2"			=> ucwords(strtolower((string)($Item->pAddress2))),
				"City"				=> ucwords(strtolower((string)($Item->pCity))),
				"State"				=> strtoupper((string)($Item->pState)),
				"Zip"				=> (string)($Item->pPostal),
				"Active"			=> $Active
			);

			$UsersID = CTable::Add("Users", $Data);

			//echo "Add User: $Item->pFirstName $Item->pLastName - $ProvidersID<br/>";
		}else{
			$UsersID = $User->ID;

			//CTable::Update("Users", $UsersID, Array("Active" => $Active));
		}

		//----------------------------------------------------------------------
		// Providers
		//----------------------------------------------------------------------
		$Data = Array(
			"ID"				=> $ProvidersID,
			"UsersID"			=> $UsersID,
			"TimestampImported" => time(),
			"CompanyFID"		=> (string)($Item->pCompany),
			"FID"				=> (string)($Item->pID),
			"FIDType"			=> (string)($Item->pIDType),
			"PayrollFID"		=> (string)($Item->pPayrollID),

			"Name"				=> ucwords(strtolower((string)($Item->pName))),
			"FirstName"			=> ucwords(strtolower((string)($Item->pFirstName))),
			"MiddleInitial"		=> strtoupper((string)($Item->pMI)),
			"LastName"			=> ucwords(strtolower((string)($Item->pLastName))),
			"Address1"			=> ucwords(strtolower((string)($Item->pAddress1))),
			"Address2"			=> ucwords(strtolower((string)($Item->pAddress2))),
			"City"				=> ucwords(strtolower((string)($Item->pCity))),
			"State"				=> strtoupper((string)($Item->pState)),
			"Zip"				=> (string)($Item->pPostal),

			"RateColumn"		=> (string)($Item->pRateColumn),
			"Certification"		=> (string)($Item->pCertification),
			"DefaultTaxonomy"	=> (string)($Item->pDefaultTaxonomy),
			"UC"				=> (string)($Item->PUC),
			"NPINumber"			=> (string)($Item->pNPINumber),
			"AcceptingNewAuths"	=> (string)($Item->pAcceptingNewAuths),
			"ForceNPI"			=> (string)($Item->pForceNPI),
			"EDSID"				=> (string)($Item->pEDSID)
		);

		$NumRows = CTable::NumRows("Providers", "WHERE `ID` = ".intval($ProvidersID));
		if($NumRows === false || $NumRows <= 0) {
			CTable::Add("Providers", $Data);
		}else{
			CTable::Update("Providers", $ProvidersID, Array("UsersID" => $UsersID));
			//CTable::Update("Providers", $ProvidersID, $Data);
		}
	}

	//==========================================================================
	// Clients
	//==========================================================================
	$Data = simplexml_load_file("./ExportData/_JH_Clients.xml");

	foreach($Data->_JH_Clients as $Item) {
		$PatientsID	= $Item->cKey;

		//----------------------------------------------------------------------
		// Patients
		//----------------------------------------------------------------------
		$Data = Array(
			"ID"				=> $PatientsID,
			"CompanyFID"		=> (string)($Item->cCompany),
			"FID"				=> (string)($Item->cID),

			"FirstName"			=> ucwords(strtolower((string)($Item->cFirstName))),
			"MiddleInitial"		=> strtoupper((string)($Item->cMI)),
			"LastName"			=> ucwords(strtolower((string)($Item->cLastName))),
			"Address1"			=> ucwords(strtolower((string)($Item->cAddress1))),
			"Address2"			=> ucwords(strtolower((string)($Item->cAddress2))),
			"City"				=> ucwords(strtolower((string)($Item->cCity))),
			"State"				=> strtoupper((string)($Item->cState)),
			"Zip"				=> (string)($Item->cPostal),

			"Gender"			=> (string)($Item->cGender),
			"DOB"				=> (string)($Item->cDOB),
			"EthnicCode"		=> (string)($Item->cEthnicCode),
			"Medicaid"			=> (string)($Item->cMedicaid),
			"Diagnosis"			=> (string)($Item->cDiagnosis)
		);

		$NumRows = CTable::NumRows("Patients", "WHERE `ID` = ".intval($PatientsID));
		if($NumRows === false || $NumRows <= 0) {
			CTable::Add("Patients", $Data);
		}else{
			CTable::Update("Patients", $PatientsID, $Data);
		}
	}

	//==========================================================================
	// Authorizations
	//==========================================================================
	CTable::RunQuery("UPDATE `AuthorizationsCodes` SET `Imported` = 0");
	CTable::RunQuery("UPDATE `AuthorizationsCodes` SET `Imported` = 1 WHERE `Manual` = 1");

	$Data = simplexml_load_file("./ExportData/_JH_Join.xml");

	foreach($Data->_JH_Join as $Item) {
		$AuthorizationsID	= $Item->aKey;

		$UsersID = 0;

		$Provider = new CProviders();
		if($Provider->OnLoad($Item->aProvider) !== false) $UsersID = $Provider->UsersID;

		//----------------------------------------------------------------------
		// Authorizations
		//----------------------------------------------------------------------
		$Data = Array(
			"ID"					=> $AuthorizationsID,
			"TimestampImported"		=> time(),
			"CompanyFID"			=> (string)($Item->aCompany),
			"PatientsID"			=> (string)($Item->aClient),
			"ProvidersID"			=> (string)($Item->aProvider),
			"UsersID"				=> $UsersID,
			"IssueDate"				=> (string)($Item->aIssueDate),
			"StartDate"				=> (string)($Item->aStartDate),
			"EndDate"				=> (string)($Item->aEndDate),
			"Number"				=> (string)($Item->aNumber),
			"ServiceCoordinator"	=> (string)($Item->aServiceCoordinator),
			"ServiceCoordination"	=> (string)($Item->aServiceCoordination),
			"FreqQuantity"			=> (string)($Item->aFreqQuantity),
			"FreqPeriod"			=> (string)($Item->aFreqPeriod),
			"FreqMinutes"			=> (string)($Item->aFreqMinutes),
			"Primary"				=> (string)($Item->aPrimary),
			"HeadofHousehold"		=> (string)($Item->aHeadofHousehold),
			"AddedfromEDS278"		=> (string)($Item->aAddedfromEDS278),
			"Inactive"				=> (string)($Item->aInactive)
		);

		$NumRows = CTable::NumRows("Authorizations", "WHERE `ID` = ".intval($AuthorizationsID));
		if($NumRows === false || $NumRows <= 0) {
			CTable::Add("Authorizations", $Data);
		}else{
			CTable::Update("Authorizations", $AuthorizationsID, $Data);
		}

		//----------------------------------------------------------------------
		// CPT Codes
		for($i = 1;$i <= 9;$i++) {
			$Code = (string)($Item->{"aCPTCode".($i == 1 ? "" : $i)});
			if($Code == "") continue;

			$Data = Array(
				"AuthorizationsID"	=> $AuthorizationsID,
				"Type"				=> "CPT",
				"Code"				=> $Code,
				"Imported"			=> 1
			);

			$Temp = new CTable("AuthorizationsCodes");
			if($Temp->OnLoadAll("WHERE `AuthorizationsID` = ".intval($AuthorizationsID)." && `Type` = 'CPT' && `Code` = '".mysql_real_escape_string($Code)."'") === false) {
				CTable::Add("AuthorizationsCodes", $Data);
			}else{
				CTable::Update("AuthorizationsCodes", $Temp->ID, Array("Imported" => 1));
			}
		}

		// EIProcedure Codes
		for($i = 1;$i <= 9;$i++) {
			$Code = (string)($Item->{"aEIProcedure".($i == 1 ? "" : $i)});
			if($Code == "") continue;

			$Data = Array(
				"AuthorizationsID"	=> $AuthorizationsID,
				"Type"				=> "EIProcedure",
				"Code"				=> $Code,
				"Imported"			=> 1
			);

			$Temp = new CTable("AuthorizationsCodes");
			if($Temp->OnLoadAll("WHERE `AuthorizationsID` = ".intval($AuthorizationsID)." && `Type` = 'EIProcedure' && `Code` = '".mysql_real_escape_string($Code)."'") === false) {
				CTable::Add("AuthorizationsCodes", $Data);
			}else{
				CTable::Update("AuthorizationsCodes", $Temp->ID, Array("Imported" => 1));
			}
		}
	}

	CTable::RunQuery("DELETE FROM `AuthorizationsCodes` WHERE `Imported` = 0");

	//==========================================================================
	$Files = scandir("./ExportData/", 1);

	array_pop($Files);
	array_pop($Files);

	foreach($Files as $File) {
		if(stripos($File, ".xml") === false && stripos($File, ".xsd") === false) continue;

		$Filename = "./ExportData/".$File;

		rename("./ExportData/".$File, "./ExportData/BK/".$File);
	}

	//==========================================================================
?>
