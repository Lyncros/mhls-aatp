<?
	//==========================================================================
	/*
		JH Specialty, Inc.

		A class that will send a data request to JH Data.
	*/
	//==========================================================================
	class CJHData {
		public static $Username = "jhspec";
		public static $Password = "sleepy123";

		public static $Serialize = true;

		public static function Send($Service, $Command, $Data) {
			$Handle = curl_init("http://data.jhspecialty.com/go.php"); 

			curl_setopt($Handle, CURLOPT_HEADER, 0);			//No Header in Response
			curl_setopt($Handle, CURLOPT_RETURNTRANSFER, 1);	//Return data
			curl_setopt($Handle, CURLOPT_PORT, 80);				//HTTP port
			//@curl_setopt($Handle, CURLOPT_HTTPPOST, TRUE);		//POST method

			$URLFields = "<?xml version='1.0'?>
			<JHData xml:lang='en-US'>
				<Authentication>
					<Username>".self::$Username."</Username>
					<Password>".self::$Password."</Password>
				</Authentication>";

			if(self::$Serialize) {
				$URLFields .= "<Serialize>1</Serialize>";
			}

			$URLFields .= "
				<Service>".$Service."</Service>
				<Command>".$Command."</Command>
				<Data>
					".$Data."
				</Data>
			</JHData>
			";

			$URLFields = "Data=".base64_encode(urlencode($URLFields));

			curl_setopt($Handle, CURLOPT_POSTFIELDS, $URLFields);			//POST our Data

			$Return = curl_exec($Handle);

			if(self::$Serialize) {
				$Return = unserialize($Return);
			}

			curl_close($Handle); 

			return $Return;
		}
	};

	//==========================================================================
?>
