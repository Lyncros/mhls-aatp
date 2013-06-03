<?
	//==========================================================================
	define("CRESOURCES_DATA_PATH",		"Resources");
	define("CZIP_DATA_PATH",			"Archives");

	//==========================================================================
	define("CLOCATION_MODE_ALL",		0); //Show everything
	define("CLOCATION_MODE_BUSINESS",	1); //Only show locations specified by the Business
	define("CLOCATION_MODE_LAST",		2);

	//==========================================================================
	define("CSEARCHCOLUMN_SEARCHTYPE_NOSEARCH", 0);
	define("CSEARCHCOLUMN_SEARCHTYPE_LOOSE",	1);
	define("CSEARCHCOLUMN_SEARCHTYPE_EXACT",	2);

	//==========================================================================
	define("CURL_BROWSER_TYPE_UNKNOWN", 0);
	define("CURL_BROWSER_TYPE_FIREFOX", 1);
	define("CURL_BROWSER_TYPE_IE",		2);
	define("CURL_BROWSER_TYPE_CHROME",	3);
	define("CURL_BROWSER_TYPE_SAFARI",	4);
	define("CURL_BROWSER_TYPE_ANDROID",	5);
	define("CURL_BROWSER_TYPE_IPHONE",	6);
	define("CURL_BROWSER_TYPE_IPOD",	7);
	define("CURL_BROWSER_TYPE_OPERA",	8);

	//==========================================================================
	define("DEV", 1);

	//==========================================================================
	define("PROJECTS_EXPORT_WITHOUT_AUTH", "ProjectsExport");
	define("CSV_COLUMN_DELIMITER", ";");
	define("CSV_ITEM_DELIMITER", ",");
	
	//==========================================================================
	class Config {
		static $Options = Array(
			"mySQL" => Array(
				"Host"			=> "localhost",
				"Username"		=> "root",
				"Password"		=> "1234",
				"Database"		=> "aatp"
			),

			"Dev" => Array(
				//"PathProduction"	=> "/data/aatp/",
				//"PathProductionFTP"	=> "/data/aatp/",
				//"ProductionDomain"	=> "http://aatp.mhlearningsolutions.com",
				"PathDev"			=> "/aatp/",
				"PathDevFTP"		=> "/aatp/",
				"DevDomain"			=> "http://localhost/aatp"
			),

			"Attention" => Array(
				"DocumentTemplates"	=> "A script requested a document template that does not exist.",
				"EmailTemplates"	=> "A webpage attempted to send out an email using a template that does not exist."
			),

			"Login" => Array(
				"MaxAttempts"	=> 15,
				"BanMinutes"	=> 10,

				"LostPassword" => Array(
					"FromName"	=> "Lost Password",
					"FromEmail"	=> "no-reply@pne.com",
					"Subject"	=> "Lost Password",
					"Content"	=> "Please keep your information in safe place, and don't give it out to anyone.<br/><br/><b>Username:</b> [[Username]]<br/><b>Password:</b> [[Password]]"
				)
			),

			"System" => Array(
				"Email"			=> "admin@pne.com",
				"Timezone"		=> "America/Ft_Wayne",
				"AdminForceSSL"	=> true,
				"Cookie"		=> Array(
					"Enabled"	=> true,
					"Expire"	=> 2592000 //30 Days
				),
				"Version"		=> "2.0.0"
			),

			"Module" => Array(

				//--------------------------------------------------------------
				// Users
				//--------------------------------------------------------------
				"Users" => Array(
					"New" => Array(
						"FromName"	=> "New Account",
						"FromEmail"	=> "no-reply@[[System Action=\"GetShortDomain\"]]",
						"Subject"	=> "New Account",
						"Content"	=> "You have been signed up for a new account with [[Business Action=\"GetCompany\"]]. Your login information is below. You can change your password under Settings once logged in.<br/><br/>
						<b>Username:</b> [[Data Name=\"Username\"]]<br/>
						<b>Password:</b> [[Data Name=\"Password\"]]<br/><br/>
						Please keep this information in a safe place and do not give it out to anyone."
					),

					"New Password" => Array(
						"FromName"	=> "Updated Password",
						"FromEmail"	=> "no-reply@[[System Action=\"GetShortDomain\"]]",
						"Subject"	=> "Updated Password",
						"Content"	=> "Your password has been updated for your account with [[Business Action=\"GetCompany\"]]. Your new login information is below. You can change your password under Settings once logged in.<br/><br/>
						<b>Username:</b> [[Data Name=\"Username\"]]<br/>
						<b>Password:</b> [[Data Name=\"Password\"]]<br/><br/>
						Please keep this information in a safe place and do not give it out to anyone."
					)
				)
			),

			"Template" => Array(
			)
		);
	}

	//==========================================================================
	if(defined("YPPDEV")) {
	}

	//==========================================================================
?>
