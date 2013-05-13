<?
	//==========================================================================
	/*
		A class mainly for Modules/Plugins to store settings on a per User basis

		Modules/Plugins already use this automatically via the Settings class
		member (see CModule.php and CPlugin.php)

		4/20/2009
	*/
	//==========================================================================
	class CSettings {
		private $ID		= 0;
		private $Data	= Array();

		public	$Options = Array(); /*
										Array(
											"Name"		=> "Value Name",
											"Type"		=> [Textbox (Default), Dropdown]
											"
										)
									*/

		function __destruct() {
			$this->OnSave();
		}

		function OnLoad($Name, $Type) {
			if(!CSecurity::IsLoggedIn()) return false;

			$TableObject = new CTable("Settings");

			if($TableObject->OnLoadAll("WHERE `UsersID` = ".intval(CSecurity::$User->ID)." && `Name` = '".mysql_real_escape_string($Name)."' && `Type` = '".mysql_real_escape_string($Type)."'") === false) {
				$Data = Array(
					"UsersID"	=> intval(CSecurity::$User->ID),
					"Name"		=> $Name,
					"Type"		=> $Type,
					"Data"		=> serialize(Array())
				);

				CTable::Add("Settings", $Data);
			}else{
				$this->ID = $TableObject->Rows->ID;

				$this->Data = unserialize($TableObject->Rows->Data);

				if($this->Data === false) {
					$this->Data = Array();
				}

				$this->Data = $this->StripSlashesRecursive($this->Data);
			}

			return true;
		}

		function OnSave() {
			if($this->ID <= 0) return;

			$Data = Array(
				"Data" => serialize($this->Data)
			);

			CTable::Update("Settings", $this->ID, $Data);
		}

		function SetValue($Name, $Value) {
			$this->Data[$Name] = $Value;
		}

		function GetValue($Name) {
			if(!isset($this->Data[$Name])) return false;

			return $this->Data[$Name];
		}

		function GetAllValues() {
			return $this->Data;
		}

		function DeleteValue($Name) {
			unset($this->Data[$Name]);
		}

		function StripSlashesRecursive($Data) {
			if(is_array($Data)) {
				foreach($Data as $Key => $Value) {
					unset($Data[$Key]);

					$Key = stripslashes($Key);

					$Data[$Key] = $Value;

					if(is_array($Data)) {
						$Data[$Key] = $this->StripSlashesRecursive($Data[$Key]);
					}else{
						$Data[$Key] = stripslashes($Value);
					}
				}
			}

			return $Data;
		}
	};

	//==========================================================================
?>
