<?
	die();

	include("Config.php");
	include("Auto.php");

	new CApp();

	echo "<pre>";

	$List = Array(
		816 => "tarr4207",
		818 => "vorb1244",
		833 => "sarr4757",
		827 => "lutt3799",
		832 => "nied1028",
		825 => "lamm9992",

		856 => "eick9657",
		186 => "engl2667",
		768 => "kell3260",

		805 => "snod0826",
		750 => "deck7032",
		751 => "dero0276",
		933 => "pill0494",
		795 => "heck0113",
		811 => "zawa0112",
		813 => "zurb9182",

		166 => "dunc0721",
		653 => "gumb3394",
 		 10 => "koes9575",
		111 => "mowe9691",
		692 => "nix3931",
		504 => "rich5942",
		569 => "sieg9259",
		 15 => "stew4659",
		525 => "trib2217",
		513 => "whee4928",
		663 => "zehr4094",

		936 => "brau3385",
		923 => "surf1132",
		812 => "zues5599",
		810 => "wool2354",
		870 => "sims8844"
	);

	foreach($List as $PID => $Username) {
		$Provider = new CProviders();
		$Provider->OnLoad($PID);

		$User = new CUsers();
		$User->OnLoad($Provider->UsersID);

		CTable::Update("Users", $User->ID, Array("Username" => $Username));
	}
?>
