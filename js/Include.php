<?
	//==========================================================================
	/*
		This file should eventually minify, and cache a JS file for the
		production environment

		We should also get rid of anything not really being used
	*/
	//==========================================================================
	include_once("JSmin.php");

	//==========================================================================
	chdir("../");

	include_once("Config.php");
	include_once("Auto.php");

	$theApp = new CApp();

	//==========================================================================
	ob_start("ob_gzhandler");

	header("Content-type: text/javascript; charset=utf-8");
	//header("Cache-Control: must-revalidate");
	//header("Expires: ".gmdate("D, d M Y H:i:s", time() + (1))." GMT");

	$Files = Array(
		"./jquery/jquery.1.7.2.min.js",
		"./jquery/jquery.ui.1.8.22.min.js",						// UI Interface
//		"./jquery/jquery.listen.js",						// For Tree
//		"./jquery/jquery.tree.js",							// Tree Control
		"./jquery/jquery.flot.js",							// Graphs
		"./jquery/jquery.autocomplete.js",					// Autocomplete
		//"./jquery/jquery.selectboxes.js",					// Dropdown Boxes (extra Options)		
		"./jquery/jquery.hotkeys.js",						// Hotkeys
//		"./jquery/jquery.dump.js",							// Dump Objects
		"./jquery/jquery.lightbox.js",						// Lightbox
		"./jquery/jquery.scrollto.js",						// Scroll To
//		"./jquery/jquery.chosen.js",						// Chosen
		"./jquery/jquery.select2.2.1.min.js",				// Select2
		"./jquery/jquery.validate.js",
		"./jquery/jquery.validate.additional-methods.js",
		
		"./uploadify/swfobject.js",							// Uploadify
		"./uploadify/jquery.uploadify.v2.1.4.min.js",		// Uploadify

		"./jquery/jquery.backgroundColor.js",				// Color Picker
		"./jquery/jquery.colorpicker.js",					// Color Picker

		"./phpjs/phpjs.js",									// PHP functions in JS

		//"./fckeditor/fckeditor.js",						// FCKeditor - Deprecated
		"./ckeditor/ckeditor.js",							// CKeditor

		"./json/json.js",									// JSON stringify and parse

		"./swfupload/swfupload.js",							// SWF Upload (Flash Uploader)

		//"./jh/DateExtensions.js",							// Extends Data function with more functionality

		//"./sIFR/sifr.js",									// sIFR, Flash Text replacement
		//"./sIFR/sifr-config.js",
		
		"CLoading.js",
		"CPicPreview.js",
		"CTooltip.js",
		"CURL.js",

		//Core
		"CAJAX.js",
		"CBox.js",
		"CForm.js",
		"CKeyboard.js",
		"CModule.js",
		"CRefresh.js",
		"CWindow.js"
	);

	if(CSecurity::IsLoggedIn()) {
		$Files[] = "CSecurity.js";
		$Files[] = "CBannedIPs.js";
		$Files[] = "CMenu.js";
		$Files[] = "CPageNotice.js";
		$Files[] = "CPlugin.js";
		$Files[] = "CSearch.js";
		$Files[] = "CSideBar.js";
		$Files[] = "CTree.js";
	}

	if(CSecurity::IsSuperAdmin()) {
		$Files[] = "CPaymentGateways.js";
	}

	//if(defined("YPPDEV")) {
		foreach($Files as $File) {
			echo file_get_contents("./js/".$File);
		}
	/*}else{
		$Time = filemtime("./js/Cache.js");

		if($Time + (60 * 60 * 24) < time()) {
			$FileHandle = fopen("./js/Cache.js", "w");

			foreach($Files as $File) {
				echo $Content = file_get_contents("./js/".$File);

				fwrite($FileHandle, $Content);
			}

			fclose($FileHandle);
		}else{
			echo file_get_contents("./js/Cache.js");
		}
	}*/
?>
